<link rel="stylesheet" type="text/css" href="/tmp_butterfly_css/main.css">
<div id="player-contenaire" style="width: 780px;display: inline-block;"></div>
<script type="text/javascript" src="/fluttershy_/main.js"></script>
<script>
const Player = new Butterfly({
	playerContenaire: document.querySelector('#player-contenaire'),
	info: {},
	source: {
		//video: '/share/trailer.mp4',
		video: "https://needforponies.fr/media/videos/gravity-falls_01x02_VF_720p.mp4",
		mime: ['mp4'],

		image: 'https://deadline.com/wp-content/uploads/2025/04/911-abc.jpg?w=681&h=383&crop=1',
	}
});
</script>