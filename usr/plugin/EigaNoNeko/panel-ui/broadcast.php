<?= omsp_header(); ?>
<style>
video {
	max-width: 720px;
	cursor: pointer;
	border: solid 1px rgba(40, 40, 40);
}

.player-info {
	padding: 16px;
	display: flex;
}

.player-info video {
	flex: none;
	width: 480px;
}

.player-info .video-info {
	width: 100%;
}

.queue-form {
	padding: 16px;
}

.queue-form fieldset {
	margin: 12px 0;
}

.queue-form fieldset label {
	margin-right: 12px;
}
</style>
<section class="kit-contenaire">
	<section class="player-info">
		<div class="video-info">
			<div>
				<label>En diffusion</label>
				<span id="current-video"></span>
			</div>
			<div>
				<label>A suivre</label>
				<span id="next-video"></span>
			</div>
			<div>
				<label>Liste d'attente</label>
				<ul id="listvideo"></ul>
			</div>
		</div>
		<video id="player" muted></video>
	</section>
</section>
<section class="queue-form">
	<div>
		<label>Source (URL ou chemin)</label>
		<input type="text" id="source-src" style="width:400px">
	</div>
	<fieldset>
		<legend>Overlay</legend>
		<label><input type="checkbox" id="overlay-enabled" checked> Activé</label>
		<label>Fichier <input type="text" id="overlay-file" placeholder="overlay.png"></label>
		<label>Opacité <input type="number" id="overlay-opacity" value="0.5" min="0" max="1" step="0.1"></label>
		<label>X <input type="number" id="overlay-x" value="0" style="width:70px"></label>
		<label>Y <input type="number" id="overlay-y" value="0" style="width:70px"></label>
	</fieldset>
	<div>
		<button id="source-send">Ajouter à la file</button>
		<button id="source-next" onclick="sendControls('next')">next</button>
	</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/1.5.13/hls.min.js"></script>
<script>
const API = "http://127.0.0.1:3000";
const streamUrl = API + "/flux/index.m3u8";

const video = document.getElementById("player");
video.addEventListener('durationchange', () => {
	video.currentTime = video.duration - 1.5
})
if (window.Hls && Hls.isSupported()) {
	const hls = new Hls();
	hls.loadSource(streamUrl);
	hls.attachMedia(video);

	hls.on(Hls.Events.ERROR, (event, data) => {
		if (data.fatal) {
			switch (data.type) {
				case Hls.ErrorTypes.NETWORK_ERROR:
					console.log("Reconnexion...");
					hls.startLoad();
					break;
				case Hls.ErrorTypes.MEDIA_ERROR:
					hls.recoverMediaError();
					break;
				default:
					hls.destroy();
					setTimeout(() => location.reload(), 3000);
			}
		}
	});
} else if (video.canPlayType("application/vnd.apple.mpegurl")) {
	video.src = streamUrl;
}

const addQueue = (data) => {
	return Omsy.request(API + '/api/queue', data, true)
}

const sendControls = (action) => {
	Omsy.request(API + '/api/control', {action: action}, true)
	.then((e) => console.log(e))
}

document.getElementById('source-send').addEventListener('click', () => {
	const source = document.getElementById('source-src').value.trim()
	if (!source) return

	addQueue({
		source: source,
		overlay_enabled: document.getElementById('overlay-enabled').checked ? 'true' : 'false',
		overlay_file: document.getElementById('overlay-file').value.trim(),
		overlay_opacity: document.getElementById('overlay-opacity').value,
		overlay_x: document.getElementById('overlay-x').value,
		overlay_y: document.getElementById('overlay-y').value,
	}).then(() => {
		document.getElementById('source-src').value = ''
	})
})

// Index de la prochaine vidéo dans la file : 1 normalement (0 = en cours), 0 pendant l'interlude
let nextIndex = 1

const label = (i) => i ? i.source.split('/').pop() : '—'

const describeOverlay = (o) =>
	o.enabled
		? `overlay : ${o.file}${o.exists ? '' : ' ⚠ introuvable'} · opacité ${o.opacity} · (${o.x}, ${o.y})`
		: 'sans overlay'

const getQueue = () => {
	Promise.all([
		fetch(API + '/api/queue').then((r) => r.json()),
		fetch(API + '/api/status').then((r) => r.json()),
	])
	.then(([items, status]) => {
		// items[0] = en cours, items[1] = suivant (sauf pendant l'interlude, où la file est vide)
		document.getElementById('current-video').textContent =
			status.interlude ? 'Interlude' : label(items[0])
		nextIndex = status.interlude ? 0 : 1
		const next = items[nextIndex]
		document.getElementById('next-video').textContent =
			next ? `${label(next)} (${describeOverlay(next.overlay)})` : '—'

		document.getElementById('listvideo').innerHTML = items.slice(2).map((i) =>
			`<li>${label(i)} <small>(${describeOverlay(i.overlay)})</small></li>`
		).join('')
	})
	.catch((err) => console.error(err))
	.finally(() => setTimeout(getQueue, 2000))
}
getQueue()
</script>
<?= omsp_footer(); ?>