/**
 * =====================================================================
 *  QUEUE-SERVER.JS — mini serveur de diffusion à file d'attente
 * ---------------------------------------------------------------------
 *  Un seul fichier, une seule responsabilité : lire une file d'attente
 *  de vidéos (fichiers locaux OU URLs distantes, ex: un .mp4 hébergé
 *  en ligne) et les encoder en flux HLS continu, pilotable via une
 *  petite API HTTP.
 *
 *  Actions disponibles :
 *   - POST /api/queue        -> ajouter une vidéo (fichier ou URL)
 *   - GET  /api/queue        -> lister la file d'attente
 *   - POST /api/control      -> { action: "start" | "stop" | "next" }
 *   - GET  /api/status       -> statut courant (en cours, prochaine,
 *                                durée, progression, playlist...)
 *
 *  Plan du fichier (tout est volontairement regroupé ici) :
 *   1. CONFIGURATION
 *   2. ÉTAT GLOBAL
 *   3. OUTILS "SOURCE" (fichier local vs URL distante)
 *   4. ENCODEUR HLS PERMANENT
 *   5. LECTURE D'UNE VIDÉO (fichier ou URL)
 *   6. BOUCLE DE DIFFUSION (scheduler)
 *   7. API HTTP (queue, control, status)
 *   8. DÉMARRAGE
 * =====================================================================
 */

const express = require("express");
const cors = require("cors");
const fs = require("fs");
const path = require("path");
const { PassThrough } = require("stream");
const ffmpegStatic = require("ffmpeg-static");
const ffmpeg = require("fluent-ffmpeg");

ffmpeg.setFfmpegPath(ffmpegStatic);

// =====================================================================
// 1. CONFIGURATION
// =====================================================================

const CONFIG = {
	// Dossier des vidéos locales (utilisé seulement si tu ajoutes un
	// simple nom de fichier plutôt qu'une URL complète)
	VIDEO_DIR: path.join(__dirname, "videos"),

	// Dossier où sont écrits les segments/playlist HLS
	OUTPUT_DIR: path.join(__dirname, "flux"),

	// Port d'écoute du serveur
	PORT: 3000,

	// Résolution / cadence de sortie
	WIDTH: 1920,
	HEIGHT: 1080,
	FPS: 25,

	// Paramètres HLS
	HLS_TIME: 6,
	HLS_LIST_SIZE: 8,
};

if (!fs.existsSync(CONFIG.OUTPUT_DIR)) fs.mkdirSync(CONFIG.OUTPUT_DIR, { recursive: true });
if (!fs.existsSync(CONFIG.VIDEO_DIR)) fs.mkdirSync(CONFIG.VIDEO_DIR, { recursive: true });

// =====================================================================
// 2. ÉTAT GLOBAL
// =====================================================================

const state = {
	status: "idle", // playing | stopped | idle
	running: true, // false = diffusion en pause ("stop")
	queue: [
		"/Users/julie/Documents/Eiga No Neko TV/videos/interlude.mp4",
		"/Users/julie/Documents/Eiga No Neko TV/videos/TVアニメ「正反対な君と僕」オープニング映像.mp4",
		"https://mlpfr.ponies.fr/sunil/VFLPS04-1_480p.mp4",
	], // liste des sources (chemins absolus OU URLs http/https)
	index: 0, // position courante dans la queue
	currentFile: null, // source en cours de lecture
	currentDuration: 0, // durée (s) de la vidéo en cours
	startedAt: null, // timestamp de démarrage de la vidéo en cours
	lastError: null,
};

let currentCommand = null; // commande ffmpeg de lecture en cours
let encoderInputStream = null; // flux alimentant l'encodeur HLS permanent
let encoderCommand = null; // commande ffmpeg de l'encodeur permanent

// =====================================================================
// 3. OUTILS "SOURCE" (fichier local vs URL distante)
// =====================================================================

/**
 * Une source est soit :
 *  - une URL distante (http:// ou https://), ex :
 *    https://mlpfr.ponies.fr/sunil/VFLPS04-1_480p.mp4
 *  - un chemin/nom de fichier local, résolu dans VIDEO_DIR
 */

function isRemoteUrl(source) {
	return /^https?:\/\//i.test(source);
}

/**
 * Transforme ce que l'utilisateur envoie ("source": "...") en une
 * source utilisable par ffmpeg, et vérifie qu'elle existe si c'est
 * un fichier local (une URL n'est pas vérifiée à l'ajout, ffmpeg
 * remontera une erreur à la lecture si elle est invalide).
 */
function resolveSource(input) {
	if (isRemoteUrl(input)) return input;

	const localPath = path.isAbsolute(input) ? input : path.join(CONFIG.VIDEO_DIR, input);
	if (!fs.existsSync(localPath)) {
		throw new Error(`Fichier local introuvable : ${input}`);
	}
	return localPath;
}

/** Nom affichable d'une source (nom de fichier, ou URL telle quelle) */
function labelOf(source) {
	if (!source) return null;
	return isRemoteUrl(source) ? source : path.basename(source);
}

// =====================================================================
// 4. ENCODEUR HLS PERMANENT
// =====================================================================

/**
 * Encodeur qui tourne en continu et ne s'arrête jamais : il reçoit
 * les données vidéo (mpegts) via un flux PassThrough et écrit le
 * flux HLS de sortie. C'est lui qui garantit la continuité entre
 * deux vidéos.
 */
function startEncoder() {
	encoderInputStream = new PassThrough();

	encoderCommand = ffmpeg(encoderInputStream)
		.inputOptions(["-fflags", "+genpts"])
		.inputFormat("mpegts")
		.videoCodec("libx264")
		.outputOptions([
			"-preset", "veryfast",
			"-profile:v", "high",
			"-level", "4.0",
			"-pix_fmt", "yuv420p",
			"-r", String(CONFIG.FPS),
			"-g", String(CONFIG.FPS * 2),
			"-keyint_min", String(CONFIG.FPS * 2),
			"-c:a", "aac",
			"-b:a", "192k",
			"-ar", "48000",
			"-hls_time", String(CONFIG.HLS_TIME),
			"-hls_list_size", String(CONFIG.HLS_LIST_SIZE),
			"-hls_flags", "delete_segments+append_list",
		])
		.output(path.join(CONFIG.OUTPUT_DIR, "index.m3u8"))
		.on("start", () => console.log("[Encodeur] démarré"))
		.on("stderr", () => {}) // silencieux (trop verbeux sinon)
		.on("error", (err) => {
			console.error("[Encodeur] erreur :", err.message);
			state.lastError = err.message;
			setTimeout(startEncoder, 1000);
		})
		.on("end", () => {
			console.log("[Encodeur] arrêté, redémarrage...");
			setTimeout(startEncoder, 500);
		});

	encoderCommand.run();
}

// =====================================================================
// 5. LECTURE D'UNE VIDÉO (fichier local ou URL)
// =====================================================================

/**
 * Lit une source (fichier local OU URL http/https, ffmpeg gère les
 * deux de la même façon), la reformate à la résolution cible, et
 * pipe le résultat vers l'encodeur permanent.
 * La promesse se résout à la fin de la lecture (naturelle ou "next").
 */
function playFile(source) {
	return new Promise((resolve) => {
		state.currentFile = source;
		state.startedAt = Date.now();
		state.status = "playing";
		state.currentDuration = 0;
		state.lastError = null;

		console.log("[Lecture]", labelOf(source));

		// Récupère la durée pour le calcul de progression, sans bloquer
		// la lecture (fonctionne aussi sur une URL distante).
		ffmpeg.ffprobe(source, (err, data) => {
			if (!err && data && data.format) state.currentDuration = data.format.duration || 0;
		});

		const command = ffmpeg(source)
			.inputOptions(["-re"]) // lit au rythme réel (temps réel)
			.videoFilters([
				`scale=${CONFIG.WIDTH}:${CONFIG.HEIGHT}:force_original_aspect_ratio=decrease`,
				`pad=${CONFIG.WIDTH}:${CONFIG.HEIGHT}:(ow-iw)/2:(oh-ih)/2`,
			])
			.outputOptions([
				"-r", String(CONFIG.FPS),
				"-c:v", "libx264",
				"-preset", "veryfast",
				"-pix_fmt", "yuv420p",
				"-c:a", "aac",
				"-b:a", "192k",
				"-ar", "48000",
			])
			.format("mpegts");

		let settled = false;
		const finish = () => {
			if (settled) return;
			settled = true;
			currentCommand = null;
			resolve();
		};

		command.on("error", (err) => {
			// Se déclenche aussi bien pour une vraie erreur (ex: URL
			// injoignable) que pour un arrêt volontaire ("next"/"stop").
			if (err.message && !err.message.includes("SIGKILL") && !err.message.includes("was killed")) {
				console.error("[Lecture] erreur :", err.message);
				state.lastError = err.message;
			}
			finish();
		});
		command.on("end", finish);

		const outStream = command.pipe(undefined, { end: false });
		outStream.on("data", (chunk) => {
			if (encoderInputStream && encoderInputStream.writable) {
				encoderInputStream.write(chunk);
			}
		});

		currentCommand = command;
	});
}

/** Coupe la lecture en cours pour enchaîner immédiatement (next/stop) */
function killCurrentPlayback() {
	if (currentCommand) currentCommand.kill("SIGKILL");
}

// =====================================================================
// 6. BOUCLE DE DIFFUSION (scheduler)
// =====================================================================

function sleep(ms) {
	return new Promise((r) => setTimeout(r, ms));
}

/**
 * Boucle infinie : tant qu'il y a des vidéos et que la diffusion est
 * active ("running"), elle les enchaîne dans l'ordre. Si la queue est
 * vide ou si "stop" a été appelé, elle attend sans rien diffuser.
 */
async function scheduler() {
	while (true) {
		if (!state.running) {
			state.status = "stopped";
			await sleep(500);
			continue;
		}

		if (state.queue.length === 0) {
			state.status = "idle";
			state.currentFile = null;
			await sleep(1000);
			continue;
		}

		if (state.index >= state.queue.length) state.index = 0;
		await playFile(state.queue[state.index]);
		if (state.running) state.index++;
	}
}

// =====================================================================
// 7. API HTTP (queue, control, status)
// =====================================================================

const app = express();
app.use(cors());
app.use(express.json());

// Le flux HLS est simplement servi en statique
app.use("/flux", express.static(CONFIG.OUTPUT_DIR));

/**
 * Ajouter une vidéo à la file d'attente.
 * Body JSON : { "source": "https://.../video.mp4" }  ou  { "source": "fichier.mp4" }
 */
app.post("/api/queue", (req, res) => {
	const { source } = req.body || {};
	if (!source || typeof source !== "string") {
		return res.status(400).json({ ok: false, error: "Paramètre 'source' requis (URL ou nom de fichier)" });
	}

	try {
		const resolved = resolveSource(source);
		state.queue.push(resolved);
		res.json({ ok: true, message: "Vidéo ajoutée à la file d'attente", queue: state.queue.map(labelOf) });
	} catch (err) {
		res.status(400).json({ ok: false, error: err.message });
	}
});

// Lister la file d'attente
app.get("/api/queue", (req, res) => {
	res.json({ queue: state.queue.map(labelOf), index: state.index });
});

/**
 * Piloter la diffusion.
 * Body JSON : { "action": "start" | "stop" | "next" }
 */
app.post("/api/control", (req, res) => {
	const { action } = req.body || {};

	switch (action) {
		case "start":
			state.running = true;
			res.json({ ok: true, message: "Diffusion démarrée" });
			break;

		case "stop":
			state.running = false;
			killCurrentPlayback();
			res.json({ ok: true, message: "Diffusion arrêtée" });
			break;

		case "next":
			if (state.queue.length > 0) state.index = (state.index + 1) % state.queue.length;
			killCurrentPlayback();
			res.json({ ok: true, message: "Passage à la vidéo suivante" });
			break;

		default:
			res.status(400).json({ ok: false, error: "Action inconnue. Actions valides : start, stop, next" });
	}
});

// Statut complet du flux
app.get("/api/status", (req, res) => {
	const elapsed = state.startedAt ? (Date.now() - state.startedAt) / 1000 : 0;
	const nextIndex = state.queue.length > 0 ? (state.index + 1) % state.queue.length : -1;

	res.json({
		status: state.status, // playing | stopped | idle
		running: state.running,
		current: labelOf(state.currentFile),
		elapsed: Math.round(elapsed),
		duration: Math.round(state.currentDuration),
		position: state.currentDuration ? Math.min(1, elapsed / state.currentDuration) : 0,
		next: nextIndex >= 0 ? labelOf(state.queue[nextIndex]) : null,
		playlist: state.queue.map(labelOf),
		queueLength: state.queue.length,
		lastError: state.lastError,
	});
});

// =====================================================================
// 8. DÉMARRAGE
// =====================================================================

startEncoder();
setTimeout(scheduler, 1000);

app.listen(CONFIG.PORT, "127.0.0.1", () => {
	console.log("Queue-server en ligne");
	console.log(`Flux HLS : http://127.0.0.1:${CONFIG.PORT}/flux/index.m3u8`);
	console.log(`Ajouter  : POST http://127.0.0.1:${CONFIG.PORT}/api/queue      { "source": "https://.../video.mp4" }`);
	console.log(`Contrôle : POST http://127.0.0.1:${CONFIG.PORT}/api/control    { "action": "start" | "stop" | "next" }`);
	console.log(`Statut   : GET  http://127.0.0.1:${CONFIG.PORT}/api/status`);
});