<?php
$sourceVideo = $_GET['src'] ?? 'https://7345e173-8d81-4624-a901-889bd422b450.mdnplay.dev/shared-assets/videos/flower.webm';

if(filter_var($sourceVideo, FILTER_VALIDATE_URL) === false) {
	http_response_code(403);
	return __OmsyMain->api('GET', ['msg' => 'URL incorrect']);
}
?>
<script type="text/javascript" src="/share/lib/fluttershy/main.js"></script>
<section class="enn-video-contenaire"></section>
<script>
const Player = new Fluttershy(document.querySelector('.enn-video-contenaire'), {
	src: "https://mlpfr.ponies.fr/marco/StarVF01-1_480p.mp4",
	poster: "https://t3.ftcdn.net/jpg/06/16/34/92/360_F_616349295_hw3oZYyNeRrz2s1h2n6x5fBLwHUA4Gpw.jpg",
	currentQuality: "480p",
	currentAudio: "VF",
	
	backVideo: "#backvideo",
	nextVideo: "#nextvideo",
});
</script>