/**
 * =====================================================================
 *  MINI RÉGIE TV — server.js
 * ---------------------------------------------------------------------
 *  Serveur unique (Node.js + Express + cors + fluent-ffmpeg) qui :
 *   - récupère une playlist distante (API HTTP) et la synchronise en
 *     continu (ajout / suppression / réordonnancement à chaud)
 *   - encode un flux HLS permanent (/flux/index.m3u8) sans jamais
 *     couper la diffusion entre deux vidéos
 *   - diffuse un interlude en boucle quand la playlist est vide
 *   - expose un webhook POST /api/control pour piloter la diffusion
 *     (start, stop, next, back, play, reload, overlay)
 *   - incruste un overlay (image PNG transparente) directement via le
 *     filtre ffmpeg "overlay", paramétrable à distance
 *   - remonte régulièrement son état (fichier en cours, progression,
 *     prochaine vidéo, statut...) à l'API distante
 *
 *  Le fichier est volontairement découpé en sections claires :
 *   1. CONFIGURATION
 *   2. SERVEUR EXPRESS
 *   3. GESTION API DISTANTE (playlist + remontée de statut)
 *   4. GESTION PLAYLIST
 *   5. GESTION FFMPEG / HLS (encodeur permanent + lecteur de vidéos)
 *   6. GESTION OVERLAY
 *   7. GESTION DES COMMANDES WEBHOOK
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
	// Dossiers locaux
	VIDEO_DIR: path.join(__dirname, "videos"),
	OUTPUT_DIR: path.join(__dirname, "flux"),

	// API distante fournissant la programmation (playlist) de la chaîne
	// NB: adapter cette URL/le format de réponse à votre API réelle.
	// Format attendu en GET : { "videos": ["file1.mp4", "file2.mp4", ...] }
	// (chemins relatifs à VIDEO_DIR ou noms de fichiers)
	PLAYLIST_API_URL: "http://127.0.0.1/getter?plugin=EigaNoNeko&page=tv&ajax=playlist",

	// Endpoint où l'on POST l'état de diffusion en temps réel
	STATUS_API_URL: "http://127.0.0.1/getter?plugin=EigaNoNeko&page=tv&ajax=status",

	// Fréquence de synchronisation de la playlist distante (ms)
	PLAYLIST_POLL_INTERVAL: 10_000,

	// Fréquence de remontée du statut vers l'API distante (ms)
	STATUS_PUSH_INTERVAL: 2_000,

	// Port d'écoute du serveur HTTP local
	PORT: 3000,

	// Sortie vidéo cible (résolution encodeur + lecteurs)
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
// ÉTAT GLOBAL DE LA RÉGIE
// =====================================================================

const state = {
	status: "idle", // playing | stopped | idle | interlude | error
	running: true, // false = diffusion en pause (commande "stop")
	queue: [], // liste des chemins absolus des vidéos programmées
	index: 0, // position courante dans la queue
	currentFile: null,
	currentDuration: 0, // durée (s) de la vidéo en cours
	startedAt: null, // timestamp de démarrage de la vidéo en cours
	forcedFile: null, // fichier à lire immédiatement (commande "play")
	lastError: null,
};

// Configuration de l'overlay, pilotable via webhook
const overlayState = {
	enabled: true,
	file: path.join(CONFIG.VIDEO_DIR, "overlay.png"),
	x: 0,
	y: 0,
	opacity: 1, // 0 (invisible) -> 1 (opaque)
};

let currentCommand = null; // commande fluent-ffmpeg en cours (lecture vidéo)
let encoderInputStream = null; // PassThrough alimentant l'encodeur HLS permanent
let encoderCommand = null; // commande fluent-ffmpeg de l'encodeur permanent

// =====================================================================
// 2. SERVEUR EXPRESS
// =====================================================================

const app = express();
app.use(cors());
app.use(express.json());

// Le flux HLS généré par l'encodeur est simplement servi en statique
app.use("/flux", express.static(CONFIG.OUTPUT_DIR));

// Petite route de santé / état, pratique pour du debug rapide
app.get("/api/status", (req, res) => {
	res.json(buildStatusPayload());
});

// =====================================================================
// 3. GESTION API DISTANTE (playlist + remontée de statut)
// =====================================================================

/**
 * Interroge l'API distante pour récupérer la programmation et met à
 * jour la queue locale (ajouts/suppressions/réordonnancement) sans
 * jamais interrompre la diffusion en cours.
 */
async function fetchRemotePlaylist() {
	try {
		const res = await fetch(CONFIG.PLAYLIST_API_URL);
		if (!res.ok) throw new Error(`HTTP ${res.status}`);
		const data = await res.json();

		const names = Array.isArray(data) ? data : data.videos || [];
		const resolved = names
			.filter((name) => /\.(mp4|mkv|webm)$/i.test(name))
			.filter((name) => path.basename(name) !== "interlude.mp4")
			.map((name) => (path.isAbsolute(name) ? name : path.join(CONFIG.VIDEO_DIR, name)))
			.filter((file) => fs.existsSync(file));

		applyNewPlaylist(resolved);
	} catch (err) {
		console.error("[API] Impossible de récupérer la playlist distante :", err.message);
		// En cas d'échec, on retombe sur les fichiers locaux disponibles
		// pour ne pas couper la diffusion.
		if (state.queue.length === 0) refreshLocalPlaylist();
	}
}

/**
 * Envoie l'état courant de diffusion à l'API distante.
 */
async function pushStatus() {
	try {
		await fetch(CONFIG.STATUS_API_URL, {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify(buildStatusPayload()),
		});
	} catch (err) {
		// La remontée de statut ne doit jamais interrompre la diffusion
		console.error("[API] Échec de l'envoi du statut :", err.message);
	}
}

/**
 * Construit le payload d'état envoyé à l'API / exposé sur /api/status.
 */
function buildStatusPayload() {
	const elapsed = state.startedAt ? (Date.now() - state.startedAt) / 1000 : 0;
	const next = getNextFile();

	return {
		status: state.status,
		running: state.running,
		file: state.currentFile ? path.basename(state.currentFile) : null,
		elapsed: Math.round(elapsed),
		duration: Math.round(state.currentDuration),
		position: state.currentDuration ? Math.min(1, elapsed / state.currentDuration) : 0,
		next: next ? path.basename(next) : null,
		queueLength: state.queue.length,
		overlay: { ...overlayState, file: path.basename(overlayState.file) },
		lastError: state.lastError,
	};
}

function getNextFile() {
	if (state.queue.length === 0) return null;
	const nextIndex = (state.index + 1) % state.queue.length;
	return state.queue[nextIndex];
}

// =====================================================================
// 4. GESTION PLAYLIST
// =====================================================================

/**
 * Remplace la queue courante par une nouvelle liste, en conservant la
 * position de lecture sur le fichier en cours si celui-ci existe
 * toujours dans la nouvelle programmation (pas de coupure).
 */
function applyNewPlaylist(newQueue) {
	const currentFile = state.currentFile;
	state.queue = newQueue;

	if (currentFile) {
		const idx = state.queue.indexOf(currentFile);
		state.index = idx >= 0 ? idx : state.index;
	}

	if (state.index >= state.queue.length) state.index = 0;
}

/**
 * Repli local : scanne simplement VIDEO_DIR (comportement d'origine),
 * utilisé si l'API distante est injoignable.
 */
function refreshLocalPlaylist() {
	const files = fs
		.readdirSync(CONFIG.VIDEO_DIR)
		.filter((file) => /\.(mp4|mkv|webm)$/i.test(file))
		.filter((file) => file !== "interlude.mp4")
		.map((file) => path.join(CONFIG.VIDEO_DIR, file));
	applyNewPlaylist(files);
}

// =====================================================================
// 5. GESTION FFMPEG / HLS
// =====================================================================

/**
 * Démarre (ou redémarre) l'encodeur HLS permanent. Il lit en continu
 * depuis un flux PassThrough (mpegts) et écrit le flux HLS de sortie.
 * C'est ce process qui ne doit JAMAIS s'arrêter tant que le serveur
 * tourne : c'est lui qui garantit la continuité de la diffusion.
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
		.on("start", (cmd) => console.log("[Encodeur] démarré"))
		.on("stderr", () => {}) // silencieux (trop verbeux sinon)
		.on("error", (err) => {
			console.error("[Encodeur] erreur :", err.message);
			state.status = "error";
			state.lastError = err.message;
			// On relance l'encodeur après un court délai pour ne pas
			// laisser le flux HLS mort trop longtemps.
			setTimeout(startEncoder, 1000);
		})
		.on("end", () => {
			console.log("[Encodeur] arrêté, redémarrage...");
			setTimeout(startEncoder, 500);
		});

	encoderCommand.run();
}

/**
 * Construit et lance la commande ffmpeg qui lit un fichier vidéo local,
 * applique éventuellement l'overlay, et pipe le résultat (mpegts) vers
 * l'encodeur permanent. La promesse se résout quand la lecture est
 * terminée (fin naturelle OU interruption via une commande webhook).
 */
function playFile(file) {
	return new Promise((resolve) => {
		state.currentFile = file;
		state.startedAt = Date.now();
		state.status = path.basename(file) === "interlude.mp4" ? "interlude" : "playing";
		state.currentDuration = 0;
		state.lastError = null;

		console.log("[Lecture]", path.basename(file));

		// Récupère la durée (pour le calcul de progression) sans bloquer
		ffmpeg.ffprobe(file, (err, data) => {
			if (!err && data && data.format) state.currentDuration = data.format.duration || 0;
		});

		const command = ffmpeg(file).inputOptions(["-re"]);
		const useOverlay = overlayState.enabled && fs.existsSync(overlayState.file);

		if (useOverlay) {
			command.input(overlayState.file);
			command.complexFilter([
				{
					filter: "scale",
					options: `${CONFIG.WIDTH}:${CONFIG.HEIGHT}:force_original_aspect_ratio=decrease`,
					inputs: "0:v",
					outputs: "scaled",
				},
				{
					filter: "pad",
					options: `${CONFIG.WIDTH}:${CONFIG.HEIGHT}:(ow-iw)/2:(oh-ih)/2`,
					inputs: "scaled",
					outputs: "base",
				},
				{
					filter: "scale",
					options: `${CONFIG.WIDTH}:${CONFIG.HEIGHT}`,
					inputs: "1:v",
					outputs: "ov_scaled",
				},
				{
					filter: "format",
					options: "rgba",
					inputs: "ov_scaled",
					outputs: "ov_rgba",
				},
				{
					filter: "colorchannelmixer",
					options: `aa=${overlayState.opacity}`,
					inputs: "ov_rgba",
					outputs: "ov_final",
				},
				{
					filter: "overlay",
					options: `${overlayState.x}:${overlayState.y}`,
					inputs: ["base", "ov_final"],
				},
			]);
		} else {
			command.videoFilters([
				`scale=${CONFIG.WIDTH}:${CONFIG.HEIGHT}:force_original_aspect_ratio=decrease`,
				`pad=${CONFIG.WIDTH}:${CONFIG.HEIGHT}:(ow-iw)/2:(oh-ih)/2`,
			]);
		}

		command
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
			// Se déclenche aussi bien pour une vraie erreur que pour un
			// arrêt volontaire (kill) via une commande webhook.
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

/**
 * Boucle d'interlude : joue interlude.mp4 en continu tant qu'aucune
 * vidéo n'est disponible dans la playlist.
 */
async function playInterlude() {
	const interlude = path.join(CONFIG.VIDEO_DIR, "interlude.mp4");
	if (!fs.existsSync(interlude)) {
		// Rien à jouer : on attend un peu avant de retenter.
		state.status = "idle";
		await sleep(2000);
		return;
	}

	state.status = "interlude";
	while (state.running && state.queue.length === 0 && !state.forcedFile) {
		await playFile(interlude);
	}
}

/**
 * Boucle principale de diffusion : enchaîne les vidéos de la queue
 * sans interruption, gère l'interlude et les lectures forcées.
 */
async function scheduler() {
	while (true) {
		if (!state.running) {
			state.status = "stopped";
			await sleep(500);
			continue;
		}

		if (state.forcedFile) {
			const file = state.forcedFile;
			state.forcedFile = null;
			await playFile(file);
			continue;
		}

		if (state.queue.length === 0) {
			await playInterlude();
			continue;
		}

		if (state.index >= state.queue.length) state.index = 0;
		await playFile(state.queue[state.index]);
		if (state.running) state.index++;
	}
}

function sleep(ms) {
	return new Promise((r) => setTimeout(r, ms));
}

// =====================================================================
// 6. GESTION OVERLAY
// =====================================================================

/**
 * Met à jour la configuration de l'overlay. Les changements sont pris
 * en compte à la prochaine vidéo lue (la vidéo en cours n'est pas
 * réencodée à la volée).
 */
function updateOverlay(params = {}) {
	if (typeof params.enabled === "boolean") overlayState.enabled = params.enabled;
	if (typeof params.file === "string") {
		overlayState.file = path.isAbsolute(params.file)
			? params.file
			: path.join(CONFIG.VIDEO_DIR, params.file);
	}
	if (typeof params.x === "number") overlayState.x = params.x;
	if (typeof params.y === "number") overlayState.y = params.y;
	if (typeof params.opacity === "number") {
		overlayState.opacity = Math.max(0, Math.min(1, params.opacity));
	}
	return overlayState;
}

// =====================================================================
// 7. GESTION DES COMMANDES WEBHOOK
// =====================================================================

/**
 * Interrompt proprement la lecture en cours (utilisé par next/back/play)
 * afin que le scheduler enchaîne immédiatement sur la suite.
 */
function killCurrentPlayback() {
	if (currentCommand) {
		currentCommand.kill("SIGKILL");
	}
}

app.post("/api/control", (req, res) => {
	const { action, file, ...params } = req.body || {};

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

		case "back":
			if (state.queue.length > 0) {
				state.index = (state.index - 2 + state.queue.length * 2) % state.queue.length;
			}
			killCurrentPlayback();
			res.json({ ok: true, message: "Retour à la vidéo précédente" });
			break;

		case "play": {
			if (!file) {
				res.status(400).json({ ok: false, error: "Paramètre 'file' requis" });
				break;
			}
			const target = path.isAbsolute(file) ? file : path.join(CONFIG.VIDEO_DIR, file);
			if (!fs.existsSync(target)) {
				res.status(404).json({ ok: false, error: "Fichier introuvable" });
				break;
			}
			state.running = true;
			state.forcedFile = target;
			killCurrentPlayback();
			res.json({ ok: true, message: `Lecture forcée de ${path.basename(target)}` });
			break;
		}

		case "reload":
			fetchRemotePlaylist().then(() => {
				res.json({ ok: true, message: "Playlist rechargée", queueLength: state.queue.length });
			});
			break;

		case "overlay":
			res.json({ ok: true, overlay: updateOverlay(params) });
			break;

		default:
			res.status(400).json({
				ok: false,
				error: "Action inconnue. Actions valides : start, stop, next, back, play, reload, overlay",
			});
	}
});

// =====================================================================
// 8. DÉMARRAGE
// =====================================================================

startEncoder();

// Première synchronisation de la playlist, puis démarrage du scheduler
fetchRemotePlaylist().finally(() => {
	setTimeout(scheduler, 1000);
});

// Synchronisation périodique de la playlist distante
setInterval(fetchRemotePlaylist, CONFIG.PLAYLIST_POLL_INTERVAL);

// Remontée périodique du statut à l'API distante
setInterval(pushStatus, CONFIG.STATUS_PUSH_INTERVAL);

app.listen(CONFIG.PORT, "127.0.0.1", () => {
	console.log("Mini régie TV en ligne");
	console.log(`Flux HLS  : http://127.0.0.1:${CONFIG.PORT}/flux/index.m3u8`);
	console.log(`Webhook   : POST http://127.0.0.1:${CONFIG.PORT}/api/control`);
	console.log(`Statut    : GET  http://127.0.0.1:${CONFIG.PORT}/api/status`);
});