<?php
$webmap = [
	"/" => [
		"text" => "Accueil",
		"icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-200h133.85v-237.69h212.3V-200H720v-360L480-740.77 240-560v360Zm-60 60v-450l300-225.77L780-590v450H526.15v-237.69h-92.3V-140H180Zm300-330.38Z"/></svg>'
	],
	"/catalogue" => [
		"text" => "Catalogue",
		"icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M287.69-535.39 480-850.77l192.31 315.38H287.69ZM700-95.39q-68.85 0-116.73-47.88-47.88-47.88-47.88-116.73t47.88-116.73q47.88-47.88 116.73-47.88t116.73 47.88q47.88 47.88 47.88 116.73t-47.88 116.73Q768.85-95.39 700-95.39Zm-564.61-20v-289.22h289.22v289.22H135.39Zm564.6-39.99q43.93 0 74.28-30.34t30.35-74.27q0-43.93-30.34-74.28t-74.27-30.35q-43.93 0-74.28 30.34t-30.35 74.27q0 43.93 30.34 74.28t74.27 30.35Zm-504.61-20h169.24v-169.24H195.38v169.24Zm198.16-420h172.92L480-734.46l-86.46 139.08Zm86.46 0ZM364.62-344.62ZM700-260Z"/></svg>'
	],
	"/depot" => [
		"text" => "Demander une serie",
		"icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-400h480v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM880-80 720-240H160q-33 0-56.5-23.5T80-320v-480q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v720ZM160-320h594l46 45v-525H160v480Zm0 0v-480 480Z"/></svg>'
	],
	"https://discord.gg/djgPnZDBBf\" target=\"_blank\"" => [
		"text" => 'Discord',
	    "icon" => '<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M464 66.52A50 50 0 00414.12 17L97.64 16A49.65 49.65 0 0048 65.52V392c0 27.3 22.28 48 49.64 48H368l-13-44 109 100zM324.65 329.81s-8.72-10.39-16-19.32C340.39 301.55 352.5 282 352.5 282a139 139 0 01-27.85 14.25 173.31 173.31 0 01-35.11 10.39 170.05 170.05 0 01-62.72-.24 184.45 184.45 0 01-35.59-10.4 141.46 141.46 0 01-17.68-8.21c-.73-.48-1.45-.72-2.18-1.21-.49-.24-.73-.48-1-.48-4.36-2.42-6.78-4.11-6.78-4.11s11.62 19.09 42.38 28.26c-7.27 9.18-16.23 19.81-16.23 19.81-53.51-1.69-73.85-36.47-73.85-36.47 0-77.06 34.87-139.62 34.87-139.62 34.87-25.85 67.8-25.12 67.8-25.12l2.42 2.9c-43.59 12.32-63.44 31.4-63.44 31.4s5.32-2.9 14.28-6.77c25.91-11.35 46.5-14.25 55-15.21a24 24 0 014.12-.49 205.62 205.62 0 0148.91-.48 201.62 201.62 0 0172.89 22.95s-19.13-18.15-60.3-30.45l3.39-3.86s33.17-.73 67.81 25.16c0 0 34.87 62.56 34.87 139.62 0-.28-20.35 34.5-73.86 36.19z"/><path d="M212.05 218c-13.8 0-24.7 11.84-24.7 26.57s11.14 26.57 24.7 26.57c13.8 0 24.7-11.83 24.7-26.57.25-14.76-10.9-26.57-24.7-26.57zM300.43 218c-13.8 0-24.7 11.84-24.7 26.57s11.14 26.57 24.7 26.57c13.81 0 24.7-11.83 24.7-26.57S314 218 300.43 218z"/></svg>'
	],
	"/freinds" => [
	    "text" => "Nos amis",
	    "icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"/></svg>'
	]
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= __OmsyInterface->getTitle(); ?></title>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-T1JJ278X14"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-T1JJ278X14');
</script>
<!-- Google tag (gtag.js) -->

	<meta name="robots" content="index, follow">
	<meta name="language" content="fr">

	<?php if(!isset($_GET['notEmbed'])): ?>
	<meta name="description" content="<?= htmlspecialchars($embeding['description'] ?? 'Eiga No Neko, catalogue de streaming d\'animées et séries. Et aussi de scans', ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="keywords" content="<?= htmlspecialchars($embeding['keywords'] ?? 'anime, animés, séries, scans, catalogue anime, Eiga No Neko', ENT_QUOTES, 'UTF-8'); ?>">
	
	<?php if(isset($embeding['image'])): ?>
	<meta property="og:image" content="<?= htmlspecialchars($embeding['image'], ENT_QUOTES, 'UTF-8'); ?>">
	<?php endif; ?>

	<meta name="twitter:card" content="summary_large_image">
	<?php endif; ?>

	<link rel="manifest" href="/manifest.json">
	<link rel="icon" type="image/png" href="/interface/img/enn.png">

	<!-- Préconnexion Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<!-- Police : garde seulement les graisses utiles -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">

	<!-- CSS principal -->
	<link rel="stylesheet" href="/interface/css/enn.kit.css">
	<link rel="stylesheet" href="/interface/css/enn.main.css">
	<link rel="stylesheet" href="/interface/css/enn.media.css">

	<?php if(isset($_SESSION['username'])): ?>
	<?php if(isset($_SESSION['role']) && $_SESSION['role'] === '10'): ?>
	<style>
header.top-header {
	top: 36px;
}
.window-contenaire {
    margin-top: calc(var(--height-header) + 36px);
}
:root {
	--color-blue: 0, 120, 212;
	--color-orange: #FF9500;
	--color-lightblue: #41AFDC;
	--color-red: #D30F45;

	--color-dark0: #171717;
	--color-dark1: #1f1f1f;
	--color-dark2: #2a2a2a;

	--color-light0: #ffff;
	--color-light1: rgba(150, 150, 150);
	--color-light2: rgba(100, 100, 100);

	--size-base: 4px;

	--tb-bar-height: calc(var(--size-base) * 9);
	--left-bar-width: 300px;
}
header {
	z-index: 999999;
	position: fixed;
	top: 0;
	left: 0;
	right: 0;

	display: flex;

	background: var(--color-dark0);
	fill: var(--color-light0);
	color: var(--color-light0);
}
header.top-bar { border-bottom: solid 1px var(--color-dark2); }

header a {
	text-decoration: none;
	color: inherit;
	fill: inherit;

	display: flex;
	align-items: center;

	cursor: pointer;
}
header.top-bar ul:first-child li:first-child a { padding-left: calc(var(--size-base) * 2); }
header.top-bar ul:nth-child(2) li:last-child a { padding-right: calc(var(--size-base) * 2); }

header a span.text { margin-left: calc(var(--size-base)); }
header button span.text { margin-left: calc(var(--size-base)); }

header a.hidden span.text { display: none; }
header button.hidden span.text { display: none; }

header svg {
	width: calc(var(--size-base) * 6);
	height: calc(var(--size-base) * 6);
}

header.top-bar,
header.bottom-bar {
	right: 0;

	height: var(--tb-bar-height);
}
header.top-bar .right {
	display: flex;
}

header.top-bar a,
header.bottom-bar a {
	opacity: 0.6;
}
header.top-bar a:hover,
header.bottom-bar a:hover {
	opacity: 1.0;
}
header.space-between {
	justify-content: space-between;
}
header.flex-items-center ul {
	list-style: none;

	display: flex;
	align-items: center;
}

header.flex-items-center {
	align-items: center;

	padding: 0 calc(var(--size-base) * 0);
}

header.flex-items-center a {
	padding: calc(var(--size-base)) calc(var(--size-base));
}
	</style>
<?php endif; ?>
<?php endif; ?>

	<script>
		const cdnImageUrl = '<?= __EnnMain->cdnImageUrl ?>'
		const cookieWatching = <?= json_encode($_COOKIE['watch_channel'] ?? '{}'); ?>;
	</script>

	<!-- JS non bloquant -->
	<script src="/share/lib/omsyJS/main.js"></script>
	<script src="/interface/js/core.main.js"></script>

	<?php if($_SERVER['SERVER_NAME'] === '127.0.0.1'): ?>
	<script>document.write('<script src="//' + location.hostname + ':35729/livereload.js?snipver=1"><\/script>');</script>
	<?php endif; ?>
</head>
<body class="<?php if(isset($bodyClass)) { echo $bodyClass; } ?>">
	<?php if(isset($_SESSION['username'])): ?>
	<?php if(isset($_SESSION['role']) && $_SESSION['role'] === '10'): ?>
	<header class="top-bar flex-items-center space-between">
		<div class="left">
			<ul>
				<li>
					<a href="/panel/">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M447.69-527.69V-760H840v232.31H447.69ZM120-200v-232.31h352.31V-200H120Zm0-327.69V-760h232.31v232.31H120Zm367.69-40H800V-720H487.69v152.31ZM160-240h272.31v-152.31H160V-240Zm0-327.69h152.31V-720H160v152.31Zm327.69 0Zm-55.38 175.38Zm-120-175.38Zm356.92 440-4.31-35.39q-18.92-4.23-35.57-12.8-16.66-8.58-29.97-23.5L566-186l-21.54-32.62L572-241.69q-6.62-20.69-6.62-41.39 0-20.69 6.62-41.38l-27.54-23.08L566-380.15l33.38 13.38q12.54-14.92 29.58-23.5t35.96-12.81l4.31-35.38h40l4.31 35.38q18.92 4.23 35.96 12.81t29.58 23.5l33.38-13.38L834-347.54l-27.54 23.08q6.62 20.69 6.62 41.38 0 20.7-6.62 41.39L834-218.62 812.46-186l-33.38-13.38q-13.31 14.92-29.96 23.5-16.66 8.57-35.58 12.8l-4.31 35.39h-40Zm81.12-94.27q25.03-25.04 25.03-61.12 0-36.07-25.03-61.11-25.04-25.04-61.12-25.04t-61.11 25.04q-25.04 25.04-25.04 61.11 0 36.08 25.04 61.12 25.03 25.04 61.11 25.04t61.12-25.04Z"/></svg>
						</span>
						<span class="text">Omsy Panel</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="right">
			<ul>
				<li>
					<a href="/signout">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M224.62-160q-27.62 0-46.12-18.5Q160-197 160-224.62v-510.76q0-27.62 18.5-46.12Q197-800 224.62-800h256.15v40H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93v510.76q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69h256.15v40H224.62Zm433.84-178.46-28.08-28.77L723.15-460H367.69v-40h355.46l-92.77-92.77 28.08-28.77L800-480 658.46-338.46Z"/></svg>
						</span>
					</a>
				</li>
			</ul>
		</div>
	</header>
	<?php endif; ?>
	<?php endif; ?>
	<header class="top-header">
		<div class="not-desktop">
			<ul>
				<li>
					<span class="button" onclick="document.querySelector('body').classList.toggle('left-header-open')">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M140-254.62v-59.99h680v59.99H140ZM140-450v-60h680v60H140Zm0-195.39v-59.99h680v59.99H140Z"/></svg>
					</span>
				</li>
			</ul>
		</div>
		<div class="left">
			<ul class="enn-kit-ul-horizon">
				<li class="open-menu-btn">
					<a class="button" onclick="document.querySelector('body').classList.toggle('left-header-open')">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M140-254.62v-59.99h680v59.99H140ZM140-450v-60h680v60H140Zm0-195.39v-59.99h680v59.99H140Z"/></svg>
					</a>
				</li>
				<li>
					<a href="/" class="button">
						<img src="/interface/img/logo_white.png" height="20px" alt="Eiga No Neko Logo - Home page">
					</a>
				</li>
				<li class="not-mobile button">
					<a href="/catalogue">
						<span class="text">Catalogues</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="right">
			<ul class="enn-kit-ul-horizon">
				<li class="not-mobile">
					<a href="https://discord.gg/djgPnZDBBf" target="_blank" class="button">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M464 66.52A50 50 0 00414.12 17L97.64 16A49.65 49.65 0 0048 65.52V392c0 27.3 22.28 48 49.64 48H368l-13-44 109 100zM324.65 329.81s-8.72-10.39-16-19.32C340.39 301.55 352.5 282 352.5 282a139 139 0 01-27.85 14.25 173.31 173.31 0 01-35.11 10.39 170.05 170.05 0 01-62.72-.24 184.45 184.45 0 01-35.59-10.4 141.46 141.46 0 01-17.68-8.21c-.73-.48-1.45-.72-2.18-1.21-.49-.24-.73-.48-1-.48-4.36-2.42-6.78-4.11-6.78-4.11s11.62 19.09 42.38 28.26c-7.27 9.18-16.23 19.81-16.23 19.81-53.51-1.69-73.85-36.47-73.85-36.47 0-77.06 34.87-139.62 34.87-139.62 34.87-25.85 67.8-25.12 67.8-25.12l2.42 2.9c-43.59 12.32-63.44 31.4-63.44 31.4s5.32-2.9 14.28-6.77c25.91-11.35 46.5-14.25 55-15.21a24 24 0 014.12-.49 205.62 205.62 0 0148.91-.48 201.62 201.62 0 0172.89 22.95s-19.13-18.15-60.3-30.45l3.39-3.86s33.17-.73 67.81 25.16c0 0 34.87 62.56 34.87 139.62 0-.28-20.35 34.5-73.86 36.19z"/><path d="M212.05 218c-13.8 0-24.7 11.84-24.7 26.57s11.14 26.57 24.7 26.57c13.8 0 24.7-11.83 24.7-26.57.25-14.76-10.9-26.57-24.7-26.57zM300.43 218c-13.8 0-24.7 11.84-24.7 26.57s11.14 26.57 24.7 26.57c13.81 0 24.7-11.83 24.7-26.57S314 218 300.43 218z"/></svg>
						</span>
					</a>
				</li>
				<li>
					<div class="form-search">
						<button id="search">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M783.52-110.91 529.85-364.59q-29.76 23.05-68.64 36.57-38.88 13.52-83.12 13.52-111.16 0-188.33-77.17-77.17-77.18-77.17-188.33t77.17-188.33q77.17-77.17 188.33-77.17 111.15 0 188.32 77.17 77.18 77.18 77.18 188.33 0 44.48-13.52 83.12-13.53 38.64-36.57 68.16l253.91 254.15-63.89 63.66ZM378.09-405.5q72.84 0 123.67-50.83 50.83-50.82 50.83-123.67t-50.83-123.67q-50.83-50.83-123.67-50.83-72.85 0-123.68 50.83-50.82 50.82-50.82 123.67t50.82 123.67q50.83 50.83 123.68 50.83Z"/></svg>
						</button>
						<input id="search-txt" type="text" name="query_search" placeholder="Rechercher" value="">
						<section class="form-search-subjection">
							<ol></ol>
						</section>
					</div>
				</li>
				<?php if(isset($_SESSION['username'])): ?>
				<li>
					<a href="#" class="user-account button">
						<img src="/interface/img/avatar/haha.png" width="56px" height="56px">
					</a>
				</li>
				<?php else: ?>
				<li>
					<a href="/signin" class="user-account button">
						<img src="/interface/img/avatar/bruh.jpg" width="56px" height="56px">
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</header>
	<header class="left-header">
		<div class="menu-webmap">
			<ul>
				<?php foreach ($webmap as $key => $value): ?>
				<li>
					<a href="<?= $key ?>" class="not-fullscreen">
						<span class="icon"><?= $value['icon'] ?></span>
						<span class="text"><?= $value['text'] ?></span>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="option-account">
			<ul>
				<?php if(isset($_SESSION['username'])): ?>
				<?php if(isset($_SESSION['role']) && $_SESSION['role'] === '8'): ?>
				<li>
					<a href="/panel/" class="not-fullscreen">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-460q-50.54 0-85.27-34.73T360-580q0-50.54 34.73-85.27T480-700q50.54 0 85.27 34.73T600-580q0 50.54-34.73 85.27T480-460Zm0-40q33.69 0 56.85-23.15Q560-546.31 560-580t-23.15-56.85Q513.69-660 480-660t-56.85 23.15Q400-613.69 400-580t23.15 56.85Q446.31-500 480-500Zm0 378.46q-120.54-35.77-200.27-146.04Q200-377.85 200-516v-216.31l280-104.61 280 104.61V-516q0 138.15-79.73 248.42Q600.54-157.31 480-121.54Zm0-357.69Zm0-315L240-705v189q0 60.15 17.69 115.77 17.69 55.61 49.85 102.92 38.92-20.23 81.46-31.46Q431.54-340 480-340t91 11.23q42.54 11.23 81.46 31.46 32.16-47.31 49.85-102.92Q720-455.85 720-516v-189l-240-89.23ZM480-300q-41.38 0-78.85 8.77-37.46 8.77-70 25.08 30.54 36.15 68 62.38Q436.62-177.54 480-164q43.38-13.54 80.46-39.77 37.08-26.23 67.62-62.38-32.54-16.31-69.62-25.08Q521.38-300 480-300Z"/></svg>
						</span>
						<span class="text">Dashboard</span>
					</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="">
						<div class="user-account">
							<div class="avatar">
								<img src="/interface/img/avatar/haha.png" width="40px">
							</div>
							<div class="text">
								<span class="username"><?= $_SESSION['username'] ?></span>
							</div>
						</div>
					</a>
				</li>
				<?php else: ?>
				<li>
					<a href="/signin">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M60-219v-170h60v110h720v-110h60v170H60Zm328.15-141.38v-37.08h-3.38q-13.38 22.31-35.19 34.19-21.81 11.89-50.19 11.89-45.93 0-72.58-24.35-26.66-24.35-26.66-66.04 0-42 31.16-68.31 31.15-26.3 80.61-26.3 21.85 0 41.54 3.88 19.69 3.89 34.08 11.89v-17.85q0-32.77-18.69-50.69-18.7-17.93-53-17.93-21 0-39.31 8.04-18.31 8.04-33.62 23.89l-33.77-27q20.16-23.54 46.47-35.23 26.3-11.7 60.46-11.7 58.92 0 89.04 29.12 30.11 29.11 30.11 85.5v174.08h-47.08Zm-67.53-137.08q-33.93 0-52.85 14.61-18.92 14.62-18.92 40.7 0 22.69 15.57 36.34 15.58 13.66 40.73 13.66 35.08 0 58.74-25.58 23.65-25.58 23.65-63.35-13.62-8-32-12.19-18.39-4.19-34.92-4.19ZM507.54-360v-401h48.92v123.39l-3 40h2.62q7.61-11.93 29.19-28.97 21.58-17.03 60.04-17.03 58.23 0 92.53 42.53 34.31 42.54 34.31 104.85 0 61.54-33.81 103.38Q704.54-351 644.92-351q-36.38 0-58.46-15.69T556.08-397h-2.62v37h-45.92ZM639-598q-39.23 0-62.39 30.27-23.15 30.27-23.15 70.88 0 41.24 23.15 71 23.16 29.77 62.39 29.77 39.23 0 61.35-29.19 22.11-29.19 22.11-71.58 0-42.38-22.11-71.77Q678.23-598 639-598Z"/></svg>
						</span>
						<span class="text">Me connecter</span>
					</a>
				</li>
				<li>
					<a href="/signup" class="cta">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M60-219v-170h60v110h720v-110h60v170H60Zm328.15-141.38v-37.08h-3.38q-13.38 22.31-35.19 34.19-21.81 11.89-50.19 11.89-45.93 0-72.58-24.35-26.66-24.35-26.66-66.04 0-42 31.16-68.31 31.15-26.3 80.61-26.3 21.85 0 41.54 3.88 19.69 3.89 34.08 11.89v-17.85q0-32.77-18.69-50.69-18.7-17.93-53-17.93-21 0-39.31 8.04-18.31 8.04-33.62 23.89l-33.77-27q20.16-23.54 46.47-35.23 26.3-11.7 60.46-11.7 58.92 0 89.04 29.12 30.11 29.11 30.11 85.5v174.08h-47.08Zm-67.53-137.08q-33.93 0-52.85 14.61-18.92 14.62-18.92 40.7 0 22.69 15.57 36.34 15.58 13.66 40.73 13.66 35.08 0 58.74-25.58 23.65-25.58 23.65-63.35-13.62-8-32-12.19-18.39-4.19-34.92-4.19ZM507.54-360v-401h48.92v123.39l-3 40h2.62q7.61-11.93 29.19-28.97 21.58-17.03 60.04-17.03 58.23 0 92.53 42.53 34.31 42.54 34.31 104.85 0 61.54-33.81 103.38Q704.54-351 644.92-351q-36.38 0-58.46-15.69T556.08-397h-2.62v37h-45.92ZM639-598q-39.23 0-62.39 30.27-23.15 30.27-23.15 70.88 0 41.24 23.15 71 23.16 29.77 62.39 29.77 39.23 0 61.35-29.19 22.11-29.19 22.11-71.58 0-42.38-22.11-71.77Q678.23-598 639-598Z"/></svg>
						</span>
						<span class="text">M'inscrire</span>
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</header>
	<main class="window-contenaire">
	    <section class="enn-main-home not-desktop">
			<section class="form-search-bar">
				<div class="form-search">
					<button id="search2">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M783.52-110.91 529.85-364.59q-29.76 23.05-68.64 36.57-38.88 13.52-83.12 13.52-111.16 0-188.33-77.17-77.17-77.18-77.17-188.33t77.17-188.33q77.17-77.17 188.33-77.17 111.15 0 188.32 77.17 77.18 77.18 77.18 188.33 0 44.48-13.52 83.12-13.53 38.64-36.57 68.16l253.91 254.15-63.89 63.66ZM378.09-405.5q72.84 0 123.67-50.83 50.83-50.82 50.83-123.67t-50.83-123.67q-50.83-50.83-123.67-50.83-72.85 0-123.68 50.83-50.82 50.82-50.82 123.67t50.82 123.67q50.83 50.83 123.68 50.83Z"/></svg>
					</button>
					<input id="search-txt2" type="text" name="query_search" placeholder="Rechercher" value="">
					<section class="form-search-subjection">
						<ol></ol>
					</section>
				</div>
			</section>
			<script type="text/javascript">
				Core.loadSearchEvent(document.getElementById('search-txt2'), document.getElementById('search2'))
			</script>
		</section>