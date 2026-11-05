<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/1.5.13/hls.min.js"></script>
	<script src="/share/lib/omsyJS/main.js"></script>
<style>
* {
	margin: 0;
	padding: 0;

	box-sizing: border-box;
}
body {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;

	display: flex;
	align-items: center;
	justify-content: center;

	background: #000;
}

video {
	width: 100%;
	height: 100%;

	cursor: pointer;
	border: solid 1px rgba(40, 40, 40);
}
</style>
</head>
<body>
	<video id="player" autoplay muted controls></video>
<script>
const video = document.getElementById("player");

const streamUrl = "http://127.0.0.1:3000/flux/index.m3u8";

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
} else if (video.canPlayType("application/vnd.apple.mpegurl")) video.src = streamUrl;
</script>
</body>
</html>