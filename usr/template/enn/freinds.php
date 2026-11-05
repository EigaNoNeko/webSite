<?php
__OmsyInterface->setTitle('Nos Amis - Eiga No Neko');
$buff = [
	"Exclusive à EigaNoNeko" => [],
	"stemvsthewrld" => [],
	"laminutedecode" => [],
	"Nos amis" => [],
	"Tu devrait follow" => [],
];

$buff['Nos amis'][] = [
	"img" => "https://leponeyblanc.fr/media/images/partners/francoponies_mlpfrance.png",
	"href" => "https://mlp-france.com/",
	"name" => "MLP France",
];
$buff['Nos amis'][] = [
	"img" => "https://leponeyblanc.fr/media/images/partners/francoponies_nfp.jpg",
	"href" => "https://needforponies.fr/",
	"name" => "Need For Ponies",
];
$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/yero.jpeg",
	"href" => "https://www.tiktok.com/@shinnosuke.wav",
	"name" => "Shinnosuke.wav",
];
$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/mlh.jpg",
	"href" => "https://www.youtube.com/@mlhorion",
	"name" => "Mlh.Orion",
];
$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/mtbios.jpg",
	"href" => "https://www.youtube.com/@MtiosMC",
	"name" => "MtBios",
];
$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/enn.webp",
	"href" => "https://www.twitch.com/eiganoneko",
	"name" => "EigaNoNeko",
];


$buff['Exclusive à EigaNoNeko'][] = [
	"audio_id" => "erovnzrqefrvqre",
	"img" => "/interface/img/freinds/ONMYOWN.jpeg",
	"audio" => "/interface/music/stem_master.mp3",
	"name" => "ONMYOWN",
	"sub_name" => "@stemvsthewrld",
];


$buff['stemvsthewrld'][] = [
	"img" => "/interface/img/freinds/stem.jpeg",
	"href" => "https://open.spotify.com/intl-fr/artist/1c5cVNksYsgflADitRbSM4",
	"name" => "Spotify",
];
$buff['stemvsthewrld'][] = [
	"img" => "/interface/img/freinds/stem_tiktok.jpeg",
	"href" => "https://www.tiktok.com/@stemvsthewrld",
	"name" => "Tiktok",
];
$buff['stemvsthewrld'][] = [
	"img" => "/interface/img/freinds/stem_ytb.jpg",
	"href" => "https://www.youtube.com/channel/UCdUvSXUigZhLAtti2M6RPLg",
	"name" => "Youtube",
];


$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/axel.jpeg",
	"href" => "https://www.tiktok.com/@axlcts",
	"name" => "Axl",
];

$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/kill.png",
	"href" => "https://www.twitch.com/killtesblocs",
	"name" => "killtesblocs",
];

$buff['Tu devrait follow'][] = [
	"img" => "/interface/img/freinds/zsleep_walker.jpg",
	"href" => "https://www.tiktok.com/@zsleep_walker",
	"name" => "Walker",
];

$buff['laminutedecode'][] = [
	"img" => "/interface/img/freinds/laminutedecode.jpeg",
	"href" => "https://www.tiktok.com/@laminutedecode?lang=fr",
	"name" => "Tiktok",
];

$buff['laminutedecode'][] = [
	"img" => "/interface/img/freinds/laminutedecode.jpeg",
	"href" => "https://www.youtube.com/@laminutedecode",
	"name" => "Youtube",
];

$buff['laminutedecode'][] = [
	"img" => "/interface/img/freinds/laminutedecode.jpeg",
	"href" => "laminutedecode.com",
	"name" => "Site web",
];

$buff['laminutedecode'][] = [
	"img" => "/interface/img/freinds/laminutedecode.jpeg",
	"href" => "https://apps.apple.com/us/app/la-minute-de-code/id6744115037",
	"name" => "App IOS",
];

$buff['laminutedecode'][] = [
	"img" => "/interface/img/freinds/laminutedecode.jpeg",
	"href" => "https://play.google.com/store/apps/details?id=com.laminutedecode.app",
	"name" => "App Android",
];
?>
<?php include "interface/include/head.php"; ?>
<style>
ul.list-freinds {
	list-style: none;

	display: flex;
	align-items: center;
	flex-wrap: wrap;

	padding-right: var(--padding-lr-header);
	margin-top: 8px;
	margin-bottom: 32px;
}


ul.list-freinds li a {
	display: block;

	padding: 8px;
	border: solid 1px transparent;

	text-align: center;

	text-decoration: none;

	color: inherit;
}
ul.list-freinds li a:hover {
	border-color: #fff;
}

ul.list-freinds-plateformes li .min {
	width: 100px;

	display: inline-block;
	margin: 0 auto;
}

ul.list-freinds-plateformes li .min img {
	border-radius: 100%;
}

ul.list-freinds li .min {
	position: relative;
	margin-bottom: 16px;
}

ul.list-freinds li .sub_name {
	opacity: .4;
	font-size: 12px;

	display: block;
}

ul.list-freinds li .min span.live {
	position: absolute;
	right: 0;
	bottom: 0;

	padding: 8px;
	border-radius: 8px;

	line-height: 1em;
	font-size: 10px;
	font-weight: 800;

	background: red;
	display: none;
}

ul.list-freinds li .min span.live.actif {
	display: block;
}

ul.list-freinds li .name {
	font-weight: 500;
}

ul.list-freinds li audio {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;

	width: 100%;
	height: 100%;

	pointer-events: none;
}

ul.list-freinds li .icon {
	z-index: 1;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;

	width: 100%;
	height: 100%;

	display: flex;
	align-items: center;
	justify-content: center;
}

ul.list-freinds li .icon svg {
	width: 32px;
	height: 32px;
}

ul.list-freinds li .icon button {
	transform: scale(1.0);
	border: none;

	padding: 8px;
	background: rgba(200, 200, 200, .4);
	border-radius: 100%;

	cursor: pointer;
	transition: .2s;
}

ul.list-freinds li .min.playing .icon button {
	transform: scale(1.3);
	transition: .2s;
}

ul.list-freinds li img.album {
	border-radius: 64px;
	transition: .2s;
}

ul.list-freinds li .min.playing img {
	transform: scale(0.8);
	border-radius: 32px;
	transition: .2s;
}
</style>
<script>
function isLive(is, user) {
	fetch(`https://api.twitch.tv/helix/streams?user_login=${user}`, {
		headers: {
			"Client-ID": 'e1vyymy0ycqmvt05qf0n3q1ve1qs2t',
			"Authorization": `Bearer qeg1plp6kgz8ukcekaux1zoh0henmn`
		}
	})
	.then((e) => e.json())
	.then((e) => {
		if(e.data[0]) is.classList.add('actif')
		else is.classList.remove('actif')

		setTimeout(() => { isLive(is, user) }, 5000)
	})
}

const audioPlatingToggle = (self, audioId) => {
	const audioElement = document.getElementById(audioId)

	console.log(audioElement)

	if(audioElement.paused) audioElement.play()
	else audioElement.pause()

	if(audioElement.classList.contains('evened')) return;
	audioElement.classList.add('evened')

	audioElement.addEventListener('play', () => {
		self.parentNode.parentNode.classList.add('playing')
		self.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M560-200v-560h160v560H560Zm-320 0v-560h160v560H240Z"/></svg>'
	})

	audioElement.addEventListener('pause', () => {
		self.parentNode.parentNode.classList.remove('playing')
		self.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M320-200v-560l440 280-440 280Z"/></svg>'
	})
}
</script>
<?php foreach ($buff as $key => $value): ?>
<h2 class="app-header-margin-top" style=""><?= $key ?></h2>
<ul class="list-freinds">
	<?php foreach ($value as $id => $val): ?>
	<li>
		<a <?php if(isset($val['href'])): ?> href="<?= $val['href'] ?>" target="_blank" <?php endif; ?>>
			<div class="min">
				<?php if (isset($val['audio'])): ?>
				<span class="icon">
					<button onclick="audioPlatingToggle(this, '<?= $val['audio_id'] ?>-audio')">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M320-200v-560l440 280-440 280Z"/></svg>
					</button>
				</span>
				<audio id="<?= $val['audio_id'] ?>-audio" src="<?= $val['audio'] ?>"></audio>
				<?php endif; ?>
				<img src="<?= $val['img'] ?>" height="100px" class="<?php if(strpos(($val['href'] ?? ''), 'spotify') ||strpos(($val['href'] ?? ''), 'twitch') || strpos(($val['href'] ?? ''), 'youtube') || strpos(($val['href'] ?? ''), 'tiktok') || strpos(($val['href'] ?? ''), 'google') || strpos(($val['href'] ?? ''), 'apple') || strpos(($val['href'] ?? ''), '.com')) echo 'album"'; ?>">
				<?php if(isset($val['href'])): ?>
				<span class="live" id="<?= $id ?>">LIVE</span>
				<script>
				if("<?= $val['href'] ?>".includes('twitch')) isLive(document.getElementById(`<?= $id ?>`), '<?= $val['name'] ?>')
				</script>
				<?php endif; ?>
			</div>
			<div class="info">
				<span class="name"><?= $val['name'] ?></span>
				<span class="sub_name"><?= $val['sub_name'] ?? '' ?></span>
			</div>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php endforeach; ?>
<?php include "interface/include/footer.php"; ?>