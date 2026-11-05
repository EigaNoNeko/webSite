<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<title>Floagg Admin</title>
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">

	<script type="text/javascript" src="/share/lib/omsyJS/main.js?v=<?= time() // __OmsyMain->__Template->version ?>"></script>
	<script src="/share/lib/floagg_panel/src/omsyConsole.js?v=<?= time() // __OmsyMain->__Template->version ?>"></script>
	<script src="/share/lib/floagg_panel/src/main.js?v=<?= time() // __OmsyMain->__Template->version ?>"></script>

	<?php foreach ($this->additionnalScript as $value): ?>
	<script src="<?= $value ?>?v=<?= time() // __OmsyMain->__Template->version ?>"></script>
	<?php endforeach; ?>
	
	<link rel="manifest" href="/manifest.json">

	<link rel="stylesheet" type="text/css" href="/share/lib/floagg_panel/src/min.css?v=<?= time() // __OmsyMain->__Template->version ?>">
	<link rel="stylesheet" type="text/css" href="/share/lib/floagg_panel/src/main_old.css?v=<?= time() // __OmsyMain->__Template->version ?>">
	<link rel="stylesheet" type="text/css" href="/share/lib/floagg_panel/src/main.css?v=<?= time() // __OmsyMain->__Template->version ?>">
	<link rel="stylesheet" type="text/css" href="/share/lib/floagg_panel/src/kit.css?v=<?= time() // __OmsyMain->__Template->version ?>">
	<link rel="stylesheet" type="text/css" href="/share/lib/floagg_panel/src/media.css?v=<?= time() // __OmsyMain->__Template->version ?>">
	<?php foreach ($this->additionnalCss as $value): ?>
	<link rel="stylesheet" type="text/css" href="<?= $value ?>?v=<?= time() // __OmsyMain->__Template->version ?>">
	<?php endforeach; ?>

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
</head>
<body>
<section class="fullscreen-contenaire" style="display: none;"></section>
<header class="top-bar flex-items-center space-between">
	<div class="left">
		<ul>
			<li>
				<a onclick="document.querySelector('body').classList.toggle('open-menu')">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-269.23v-40h640v40H160ZM160-460v-40h640v40H160Zm0-190.77v-40h640v40H160Z"/></svg>
				</a>
			</li>
			<li>
				<a href="/">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-200h156v-234h168v234h156v-360L480-742 240-560v360Zm-28 28v-402l268-203 268 203v402H536v-234H424v234H212Zm268-299Z"/></svg>
				</a>
			</li>
			<li>
				<a onclick="history.back();">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m276.85-460 231.69 231.69L480-200 200-480l280-280 28.54 28.31L276.85-500H760v40H276.85Z"/></svg>
				</a>
			</li>
			<li>
				<a onclick="window.location.reload();">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M483.08-200q-117.25 0-198.63-81.34-81.37-81.34-81.37-198.54 0-117.2 81.37-198.66Q365.83-760 483.08-760q71.3 0 133.54 33.88 62.23 33.89 100.3 94.58V-760h40v209.23H547.69v-40h148q-31.23-59.85-87.88-94.54Q551.15-720 483.08-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h42.46Q725.08-310.15 651-255.08 576.92-200 483.08-200Z"/></svg>
				</a>
			</li>
		</ul>
	</div>
	<div class="right">
		<ul>
			<?php foreach ($this->getTopRightMenu() as $value): ?>
			<li>
				<a href="<?= $value['url'] ?>">
					<span class="icon"><?= $value['icon'] ?></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</header>
<header class="left-bar">
	<div class="top-menu">
		<div class="userinfo">
			<div class="avatar">
				<img src="<?= $this->getAvatar() ?>" width="64px" height="64px" style="object-fit: cover;">
			</div>
			<div class="info">
				<span class="username w700"><?= __OmsyAccounts->username ?></span>
				<span class="sub_username">
					<?php if(__OmsyAccounts->user_mail): ?>
					<?= __OmsyAccounts->user_mail ?>
					<?php else: ?>
					<?= __OmsyMain->GFunction->convertDate(__OmsyAccounts->last_login) ?>
					<?php endif; ?>
				</span>
			</div>
		</div>
		<div class="menu-plugins">
			<?php foreach ($this->getMap() as $nameButton => $listes): ?>
			<?php if(isset($nameButton) && $nameButton !== 'null') : ?>
			<li class="sepp-head"><?= $nameButton ?></li>
			<?php endif; ?>
			<?php foreach($listes as $button): ?>
			<li
				class="<?php if(isset($button['focus']) && $button['focus']) echo 'open focus'; ?>">
				<a class="categorie-button w700"
					onclick="this.parentNode.classList.toggle('focus')"
					<?php if((isset($button['sub']) && count($button['sub']) <= 0) || !isset($button['sub'])) : ?>
					href="<?= $button['url'] ?>"
					<?php endif; ?>
				>
					<div class="left">
						<span class="icon"><?= $button['icon'] ?></span>
						<span class="text"><?= $button['name'] ?></span>
					</div>
					<?php if(isset($button['sub']) && count($button['sub']) > 0) : ?>
					<div class="icon-open">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-373.85 303.85-550h352.3L480-373.85Z"/></svg>
					</div>
					<?php endif; ?>
				</a>
				<?php if(isset($button['sub']) && count($button['sub']) > 0) : ?>
				<ol class="sub_menu">
					<?php foreach($button['sub'] as $ksbtn => $sbtn): ?>
					<?php if(isset($ksbtn) && $ksbtn !== 'null') : ?>
					<li class="sepp-head"><?= $ksbtn ?></li>
					<?php endif; ?>
					<?php foreach($sbtn as $sub_button): ?>
					<li class="<?php if(isset($sub_button['focus']) && $sub_button['focus']) echo 'focus'; ?>">
						<a href="<?= $sub_button['url'] ?>">
							<span class="text"><?= $sub_button['name'] ?></span>
						</a>
					</li>
					<?php endforeach; ?>
					<?php endforeach; ?>
				</ol>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="bottom-menu">
		<div id="publishChangeButton-contenaire" style="display: ;">
			<button id="publishChangeButton">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M224.62-160q-27.62 0-46.12-18.5Q160-197 160-224.62V-360h40v135.38q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69H360v40H224.62Zm510.76 0H600v-40h135.38q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-360h40v135.38q0 27.62-18.5 46.12Q763-160 735.38-160ZM160-735.38q0-27.62 18.5-46.12Q197-800 224.62-800H360v40H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93V-600h-40v-135.38Zm640 0V-600h-40v-135.38q0-9.24-7.69-16.93-7.69-7.69-16.93-7.69H600v-40h135.38q27.62 0 46.12 18.5Q800-763 800-735.38ZM481.54-271.54q13.31 0 22.42-9.11 9.12-9.12 9.12-22.43 0-13.3-9.12-22.42-9.11-9.12-22.42-9.12-13.31 0-22.42 9.12-9.12 9.12-9.12 22.42 0 13.31 9.12 22.43 9.11 9.11 22.42 9.11Zm-20.62-132.23h38.39q1.54-25.54 9.92-42 8.39-16.46 32.31-40.38 30.38-30.39 41.88-51.12 11.5-20.73 11.5-46.11 0-46.31-31.3-75.7-31.31-29.38-80.54-29.38-39.23 0-69.46 19.84-30.24 19.85-47 57.08l36.76 15.46q10.85-25.23 31.5-40.19 20.66-14.96 46.66-14.96 32.84 0 53.81 18.96 20.96 18.96 20.96 49.58 0 18.46-9.12 34.81-9.11 16.34-31.34 36.11-29.93 28.23-42.43 52.15-12.5 23.93-12.5 55.85Z"/></svg>
				</span>
				<span class="text">Sauvegarder</span>
			</button>
		</div>
	</div>
</header>
<style>
@media (min-width: 500px) {
	header.bottom-bar {
		display: none;
	}
}
@media (max-width: 500px) {
:root {
	--tb-bar-height: 0px;
}
main {
	padding-top: 16px;
	padding-bottom: 32px;
	margin-bottom: 94px;
}

header.top-bar {
	display: none;
}

header.bottom-bar {
	position: fixed;
	left: 0;
	right: 0;
	bottom: 0;

	height: auto;
	background-color: #fff;
	display: block;

	padding: 16px;
	padding-bottom: 48px;
	box-shadow: 0px 4px 20px rgba(0, 0, 0, .05);

	border-top-left-radius: 16px;
	border-top-right-radius: 16px;
}

header.bottom-bar .menu {
	padding: 4px;
	border-radius: 32px;
}

header.bottom-bar ul {
	width: 100%;
	margin-top: 0;
}
header.bottom-bar ul.horizontal {
	display: flex;
	align-items: center;
	justify-content: space-between;
}

header.bottom-bar ul.horizontal a {
	flex-direction: column;

	width: 59px;
}

header.bottom-bar a {

	border: solid 1px transparent;

	border-radius: 32px;

	display: flex;
	align-items: center;

	transition: .2s;
}
header.bottom-bar .app-list a {
	padding: 8px;

}
header.bottom-bar .app-list a:hover {
	background-color: var(--color-dark2);
	transition: .2s;
}

header.bottom-bar svg,
header.bottom-bar span {
	display: block;
}

header.bottom-bar svg {
	height: 28px;
	width: 28px;

	margin-bottom: 8px;
}

header.bottom-bar ul.horizontal span { font-size: 10px; }

body header .menu.app-list { display: none; }
body.open-mobilemenuapp header .menu.app-list { display: block; }


.popup-step {
	padding-bottom: 48px;

	border-bottom-left-radius: 0px;
	border-bottom-right-radius: 0px;

	width: 100%;
}



.fullscreen-contenaire {
	align-items: flex-end;
}

.popup-step .status-fetch {
	display: flex;
	flex-direction: column;
	align-items: center;

	gap: 16px;

	margin-bottom: 32px;
}
}

.popup-step {
	padding: 32px;

	border-top-left-radius: 16px;
	border-top-right-radius: 16px;
	border-bottom-left-radius: 16px;
	border-bottom-right-radius: 16px;

	background: #fff;

	display: flex;
	flex-direction: column;
	gap: 32px;

	line-height: 1.3em;

	width: 450px;
}

.popup-step .foot {
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.popup-step .foot a {
	text-decoration: none;
	color: inherit;
	fill: inherit;
	font-weight: 500;
	letter-spacing: .03em;

	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;
	padding: 10px 16px;

	text-align: center;

	background: #E6EAFF;
	border: 1px solid #C7CFFF;
	border-radius: 8px;

	width: 100%;

	cursor: pointer;
}

.popup-step .foot a.cta {
	color: #fff;
	fill: #fff;

	background: #1938F0;
	border: 1px solid #1938F0;
}

.popup-step .foot a.transparent {
	background: transparent;
	border: 1px solid transparent;
}

.popup-step .foot a.red {
	color: red;
	fill: red;
}
</style>
<header class="bottom-bar for-mobile">
	<div class="menu app-list" style="margin-bottom: 16px;">
		<ul>
			<li>
				<a href="/studio/overview">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M340.62-140q-84.08 0-142.35-58.27Q140-256.54 140-340.62q0-35.3 12.04-68.61t34.5-60.38l141.23-169.85L237.69-820h484.62l-90.08 180.54 141.23 169.85q22.46 27.07 34.5 60.38T820-340.62q0 84.08-58.58 142.35Q702.85-140 619.38-140H340.62ZM480-330.39q-29.92 0-51.11-21.19-21.2-21.19-21.2-51.11 0-29.93 21.2-51.12Q450.08-475 480-475q29.92 0 51.11 21.19 21.2 21.19 21.2 51.12 0 29.92-21.2 51.11-21.19 21.19-51.11 21.19ZM382.69-665h195L625-760H335l47.69 95Zm-42.07 465h278.76q58.93 0 99.77-41.04Q760-282.08 760-340.62q0-24.77-8.5-48.03-8.5-23.27-24.27-42.04L582.15-605h-203.3l-145.7 173.69q-15.77 18.77-24.46 42.35-8.69 23.57-8.69 48.34 0 58.54 41.04 99.58T340.62-200Z"/></svg>
					<span>Budget</span>
				</a>
			</li>
			<li>
				<a href="/studio/overview">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M212.31-100Q182-100 161-121q-21-21-21-51.31v-535.38Q140-738 161-759q21-21 51.31-21h55.38v-84.61h61.54V-780h303.08v-84.61h60V-780h55.38Q778-780 799-759q21 21 21 51.31v535.38Q820-142 799-121q-21 21-51.31 21H212.31Zm0-60h535.38q4.62 0 8.46-3.85 3.85-3.84 3.85-8.46v-375.38H200v375.38q0 4.62 3.85 8.46 3.84 3.85 8.46 3.85ZM200-607.69h560v-100q0-4.62-3.85-8.46-3.84-3.85-8.46-3.85H212.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46v100Zm0 0V-720v112.31Zm280 210.77q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.04 0-14.69 10.34-25.04 10.35-10.34 25.04-10.34t25.04 10.34q10.34 10.35 10.34 25.04 0 14.7-10.34 25.04-10.35 10.35-25.04 10.35Zm-185.04-10.35q-10.34-10.34-10.34-25.04 0-14.69 10.34-25.04 10.35-10.34 25.04-10.34t25.04 10.34q10.34 10.35 10.34 25.04 0 14.7-10.34 25.04-10.35 10.35-25.04 10.35t-25.04-10.35ZM640-396.92q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.04 0-14.69 10.34-25.04 10.35-10.34 25.04-10.34t25.04 10.34q10.34 10.35 10.34 25.04 0 14.7-10.34 25.04-10.35 10.35-25.04 10.35ZM480-240q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.03 0-14.7 10.34-25.04 10.35-10.35 25.04-10.35t25.04 10.35q10.34 10.34 10.34 25.04 0 14.69-10.34 25.03Q494.69-240 480-240Zm-185.04-10.35q-10.34-10.34-10.34-25.03 0-14.7 10.34-25.04 10.35-10.35 25.04-10.35t25.04 10.35q10.34 10.34 10.34 25.04 0 14.69-10.34 25.03Q334.69-240 320-240t-25.04-10.35ZM640-240q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.03 0-14.7 10.34-25.04 10.35-10.35 25.04-10.35t25.04 10.35q10.34 10.34 10.34 25.04 0 14.69-10.34 25.03Q654.69-240 640-240Z"/></svg>
					<span>Calendrier</span>
				</a>
			</li>
		</ul>
	</div>
	<ul class="menu-glass horizontal">
		<?php foreach ($this->getMapMobile() as $button): ?>
		<li>
			<a href="<?= $button['href'] ?>">
				<?= $button['icon'] ?? '' ?>
				<span><?= $button['name'] ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</header>
<script>
const menuScroll = document.querySelector('header.left-bar .menu-plugins')
menuScroll.addEventListener('scroll', () => {
	if(menuScroll.scrollTop > 32) menuScroll.parentNode.classList.add('scrolling')
	if(menuScroll.scrollTop < 32) menuScroll.parentNode.classList.remove('scrolling')
})
</script>
<main>