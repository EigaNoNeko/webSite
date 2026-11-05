<?= omsp_header(); ?>
<style>
* {
    margin: 0;
    padding: 0;

    box-sizing: border-box;
}
body {

    background: #000;
    color: #fff;
}

</style>
</head>
<body>
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

.player-info .video-info {
    width: 100%;
}

.player-info video {
    flex: none;

    width: 480px;
}

.player-info label {
    font-size: 14px;
    font-weight: 500;

    display: block;
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
    </div>
    <video id="player" muted></video>
</section>
<section>
    <div>
        <ul id="listvideo"></ul>
    </div>
    <div>
        <input type="text" id="source-src">
        <button id="source-send">send</button>
    </div>
    <div>
        <button id="source-next" onclick="sendControls('next')">next</button>
    </div>
    <div>
        <input type="number" id="overlayOpacity" value="1" min="0" max="1" step="0.1" onchange="updateOverlay(this)">
    </div>
</section>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/1.5.13/hls.min.js"></script>
<script>
const streamUrl = "http://127.0.0.1:3000/flux/index.m3u8";
function getFileNameWithoutExtension(path) {
    const file = path.split("/").pop().split("\\").pop();
    return file.substring(0, file.lastIndexOf(".")) || file;
}
// -----------------------------------------------------------------
// Lecteur HLS (hls.js, ou lecture native pour Safari)
// -----------------------------------------------------------------
const video = document.getElementById("player");

video.addEventListener('durationchange', () => {
    video.currentTime = video.duration-1.5
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

const queue = []

const addQueue = (string) => {
    Omsy.request('http://127.0.0.1:3000/api/queue', {'source': string}, true)
    .then((e) => {
        queue.push(string)
    })
}

const sendControls = (action) => {
    Omsy.request('http://127.0.0.1:3000/api/control', {action: action}, true)
    .then((e) => {
        console.log(e)
    })
}

document.getElementById('source-send').addEventListener('click', () => {
    addQueue(document.getElementById('source-src').value)
})

const updateOverlay = (e) => {
    Omsy.request('http://127.0.0.1:3000/api/overlay', {opacity: e.value}, true)
    .then((e) => {
        console.log(e)
    })
}

const getQueue = () => {
    fetch('http://127.0.0.1:3000/api/queue')
    .then((e) => e.json())
    .then((e) => {
        console.log(e)
        document.getElementById('current-video').textContent = getFileNameWithoutExtension(e[0] ?? '/Users/julie/Documents/Eiga No Neko TV/videos/TVアニメ「正反対な君と僕」オープニング映像.mp4')
        document.getElementById('next-video').textContent = getFileNameWithoutExtension(e[1] ?? '/Users/julie/Documents/Eiga No Neko TV/videos/TVアニメ「正反対な君と僕」オープニング映像.mp4')

        delete e[0];
        delete e[1];

        let html = ''
        e.forEach((c) => {
            html += `<li>${c}</li>`
        })
        document.getElementById('listvideo').innerHTML = html

        fetch('http://127.0.0.1:3000/api/status')
        .then((e) => e.json())
        .then((e) => {
            console.log(e)

            document.getElementById('current-video').textContent = getFileNameWithoutExtension(e.file ?? '/Users/julie/Documents/Eiga No Neko TV/videos/TVアニメ「正反対な君と僕」オープニング映像.mp4')

            setTimeout(getQueue, 2000)
        })
    })
}
getQueue()
</script>
<?= omsp_footer(); ?>