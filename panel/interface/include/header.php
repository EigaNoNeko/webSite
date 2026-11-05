<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Omsy Dashbaord</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="/panel/interface/css/default.css">
	<link rel="stylesheet" type="text/css" href="/panel/interface/css/main.css">
	<link rel="stylesheet" type="text/css" href="/panel/interface/css/uikit.css">

	<script type="text/javascript" src="/share/lib/omsyJS/main.js"></script>
	<script type="text/javascript" src="/share/lib/omsyJS/OmsyInterval.js"></script>

	<script type="text/javascript" src="/panel/interface/js/OmsyVars.js"></script>

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
</head>
<body>
<section class="messages-systeme">
	<ul id="message-system"></ul>
</section>
<header class="bottom-header">
	<div class="left">
		<ul>
			<li>
				<a onclick="document.querySelector('body').classList.toggle('left-header-open')">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M140-254.62v-59.99h680v59.99H140ZM140-450v-60h680v60H140Zm0-195.39v-59.99h680v59.99H140Z"/></svg>
				</a>
			</li>
			<!--<li>
				<a href="/" class="logo">
					<img src="/share/omsy-product/white_logo_lite.png" height="24px">
				</a>
			</li>-->
			<li>
				<a href="/stat">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m798-322-62-62q44-41 69-97t25-119q0-63-25-118t-69-96l62-64q56 53 89 125t33 153q0 81-33 153t-89 125ZM670-450l-64-64q18-17 29-38.5t11-47.5q0-26-11-47.5T606-686l64-64q32 29 50 67.5t18 82.5q0 44-18 82.5T670-450Zm-310 10q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-120v-112q0-33 17-62t47-44q51-26 115-44t141-18q77 0 141 18t115 44q30 15 47 44t17 62v112H40Zm80-80h480v-32q0-11-5.5-20T580-266q-36-18-92.5-36T360-320q-71 0-127.5 18T140-266q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-600q0-33-23.5-56.5T360-680q-33 0-56.5 23.5T280-600q0 33 23.5 56.5T360-520Zm0-80Zm0 400Z"/></svg>
					</span>
					<span class="text" id="get-session">Online 0</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="right">
		<ul>
			<li>
				<a href="#" class="progress-id-izebvezbn">
					<span class="icon">
						<span class="progress"><span class="progress-content"></span></span>
					</span>
					<span class="text progress-message">Démarrage...</span>
				</a>
			</li>
			<li>
				<a href="/stat" class="fetching">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-160v-80h110l-16-14q-52-46-73-105t-21-119q0-111 66.5-197.5T400-790v84q-72 26-116 88.5T240-478q0 45 17 87.5t53 78.5l10 10v-98h80v240H160Zm400-10v-84q72-26 116-88.5T720-482q0-45-17-87.5T650-648l-10-10v98h-80v-240h240v80H690l16 14q49 49 71.5 106.5T800-482q0 111-66.5 197.5T560-170Z"/></svg>
					</span>
				</a>
			</li>
		</ul>
	</div>
</header>
<header class="left-header">
	<div class="top">
		<div class="userinfo header-padding-lr">
			<div class="avatar">
				<img src="<?= __OmsyAccounts->user_avatar ?? '/panel/interface/image/avatar/haha.png' ?>" width="64px" height="64px">
			</div>
			<div class="info">
				<span class="username w700"><?= __OmsyAccounts->username ?></span>
				<span class="sub_username">
					<?php if(__OmsyAccounts->user_mail): ?>
					<?= __OmsyAccounts->user_mail ?>
					<?php else: ?>
					<?= CMS->GFunction->convertDate(__OmsyAccounts->last_login) ?>
					<?php endif; ?>
				</span>
			</div>
		</div>
		<div class="naviguation-menu">
			<div class="head-menu">
				<span class="name w700">Vos plugins</span>
			</div>
			<ul>
				<?php foreach(__OmsyPanel->getMenu2() as $value): ?>
				<li
					class="<?php if(isset($_GET['plugin']) && $_GET['plugin'] == $value['id']) echo 'open'; ?>"
				>
					<a class="categorie-button w700"
					  data-name="<?= $value['name'] ?>"
					  data-item-id="<?= $value['id'] ?>"
					  onclick="this.parentNode.classList.toggle('open')"
					>
						<div class="left">
							<span class="icon"><?= $value['icon'] ?></span>
							<span class="text"><?= $value['name'] ?></span>
						</div>
						<div class="icon-open">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-373.85 303.85-550h352.3L480-373.85Z"/></svg>
						</div>
					</a>
					<ol class="sub_menu">
						<?php
						foreach($value['sub'] ?? [] as $v):
						?>
						<li>
							<a href="getter?plugin=<?= $value['id'] ?>&page=<?= $v['url'] ?>">
								<span class="text"><?= $v['name'] ?></span>
							</a>
						</li>
						<?php endforeach; ?>
					</ol>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="bottom">
		<div class="header-padding-lr" id="publishChangeButton-contenaire" style="display: none;">
			<button id="publishChangeButton">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
				</span>
				<span class="text">omsy.button.unknow</span>
			</button>
		</div>
	</div>
</header>
<main>
<script>

</script>