<?php include "include/head.php"; ?>
<?php
$lang = [
	"default" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" style="width: ; height: auto;"><defs><linearGradient id="greyGradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#222222"/><stop offset="100%" stop-color="#444444"/></linearGradient></defs><rect width="900" height="600" fill="url(#greyGradient)"/><circle cx="450" cy="300" r="100" stroke="#FFFFFF" stroke-width="15" fill="none"/><line x1="350" y1="300" x2="550" y2="300" stroke="#FFFFFF" stroke-width="15"/></svg>',
	"vf" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600" preserveAspectRatio="xMidYMid meet" style="width: ; height: auto;"><path fill="#CE1126" d="M0 0h900v600H0"/><path fill="#fff" d="M0 0h600v600H0"/><path fill="#002654" d="M0 0h300v600H0"/></svg>',
	"vo" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" style="width: ; height: auto;"><rect width="3" height="2" fill="#ffffff"/><circle cx="1.5" cy="1" r="0.6" fill="#BC002D"/></svg>',
	"vostfr" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" style="width: ; height: auto;"><rect width="3" height="2" fill="#ffffff"/><circle cx="1.5" cy="1" r="0.6" fill="#BC002D"/></svg>',
];
?>
<section class="player-video window-width">
	<div class="contenaire-player-data">
		<div class="contenaire-player">
			<div class="loading-player"><img src="/interface/img/default_thumbnail.webp" width="100%"><span class="loading"></span></div>
		</div>
		<section class="contenaire-player-info">
			<div class="player-controls">
				<ul>
					<li>
						<?php if(isset($back)): ?>
						<a href="/watch/<?= $channelInfo['slug'] ?>?s=<?= $back['saison'] ?>&ep=<?= $back['episode'] ?>&type=<?= $typeVideo ?>">
							<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M400-93.85 13.85-480 400-866.15l56.77 56.77L127.38-480l329.39 329.38L400-93.85Z"/></svg></span>
							<span class="text">S<?= intval($back['saison']) ?> - Episode <?= intval($back['episode']) ?></span>
						</a>
						<?php endif; ?>
					</li>
					<li>
						<a href="/catalogue/<?= $channelInfo['slug'] ?>">
							<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-189.23q-24.75 0-42.37-17.63Q420-224.48 420-249.23q0-24.75 17.63-42.38 17.62-17.62 42.37-17.62 24.75 0 42.37 17.62Q540-273.98 540-249.23q0 24.75-17.63 42.37-17.62 17.63-42.37 17.63ZM480-420q-24.75 0-42.37-17.63Q420-455.25 420-480q0-24.75 17.63-42.37Q455.25-540 480-540q24.75 0 42.37 17.63Q540-504.75 540-480q0 24.75-17.63 42.37Q504.75-420 480-420Zm0-230.77q-24.75 0-42.37-17.62Q420-686.02 420-710.77q0-24.75 17.63-42.37 17.62-17.63 42.37-17.63 24.75 0 42.37 17.63Q540-735.52 540-710.77q0 24.75-17.63 42.38-17.62 17.62-42.37 17.62Z"/></svg></span>
							<span class="text not-mobile">Toute les information</span>
						</a>
					</li>
					<li>
						<?php if(isset($next)): ?>
						<a href="/watch/<?= $channelInfo['slug'] ?>?s=<?= $next['saison'] ?>&ep=<?= $next['episode'] ?>&type=<?= $typeVideo ?>">
							<span class="text">S<?= intval($next['saison']) ?> - Episode <?= intval($next['episode']) ?></span>
							<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m320.62-93.85-56.77-56.77L593.23-480 263.85-809.38l56.77-56.77L706.77-480 320.62-93.85Z"/></svg></span>
						</a>
						<?php endif; ?>
					</li>
				</ul>
			</div>
			<div class="infoplayer-others">
				<div class="channel-info">
					<div class="img">
						<img src="<?= __EnnMain->cdnImageUrl ?>poster/<?= $channelInfo['slug'] ?? '' ?>.jpg" width="128px" onerror="this.src = Core.notLoadImage('poster')">
					</div>
					<div class="text">
						<span class="title"><?= $channelInfo['title'] ?? '' ?></span>
						<p class="substr"><?= __OmsyString->substr($channelInfo['description'] ?? '', 155) ?></p>
						<span class="l-content">
							<?php foreach ($channelInfo['genres'] ?? [] as $value): ?>
							<a href="/search?query_search=<?= $value ?>"><?= __OmsyString->htmlDecode($value) ?>,</a>
							<?php endforeach; ?>
						</span>
					</div>
				</div>
				<div class="content-description">
					<h3>Episode <?= intval($videoChannel_____['episode']) ?> - Saison <?= intval($videoChannel_____['saison'] ) ?> - <span id="lang-update-string"><?= $channelInfo['title'] ?? '' ?></span></h3>
					<p class="allstr" style="display: none;"><?= nl2br($channelInfo['description'] ?? '') ?></p>
					<p class="substr"><?= __OmsyString->substr($channelInfo['description'] ?? '', 155) ?></p>
				</div>
			</div>
		</section>
	</div>
	<div class="player-info">
		<?php
		$current = 'null';
		if(isset($videoLang['vf']) && count($videoLang['vf']) > 0) $current = 'vf';
		else if(isset($videoLang['vf1']) && count($videoLang['vf1']) > 0) $current = 'vf1';
		else if(isset($videoLang['vf2']) && count($videoLang['vf2']) > 0) $current = 'vf2';
		else if(isset($videoLang['vostfr']) && count($videoLang['vostfr']) > 0) $current = 'vostfr';
		?>
		<div class="liste-player liste-player-langue" style="margin-top: 0;">
			<ul>
				<?php
				foreach ($videoLang as $k => $valuelang):
					if(count($valuelang) <= 0) continue;
					if($current == 'null') $current = $k;
				?>
				<li
				data-name="<?= __EnnWatch->langToString($k) ?>"
				<?php if($current == $k) echo 'class="focus"' ?>>
					<a onclick="Player.getLangue(this.parentNode, '<?= strtolower($k) ?>')">
						<?= $lang[strtolower($k)] ?? $lang['default'] ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="liste-player">
			<div class="item-lang-player open">
				<div class="head" onclick="toggleClass(this.parentNode, 'open')">
					<span class="title-list">
						<span class="name"><?= __EnnWatch->langToString($current) ?></span>
						<span class="kit-tag" style="margin-left: 8px;">Recommandé</span>
					</span>
					<div class="indice">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-360 280-560h400L480-360Z"/></svg>
					</div>
				</div>
				<div class="content">
					<ul id="focus-upate-langue-player">
						<?php foreach ($videoLang[$current] as $value): ?>
						<li>
							<a class="<?php if($videoInfo['src'] == $value['src']) echo 'focus'; ?>"
								data-basename="<?= getBaseDomainName($value['src']) ?>"
								data-src="<?= $value['src'] ?>"
								data-str="<?= $value['name'] ?>"
								onclick="corePlayer.load(this, '<?= $value['src'] ?>', 'focus-upate')">
								<span class="icon focused">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-160v-80h110l-16-14q-49-49-71.5-106.5T160-478q0-111 66.5-197.5T400-790v84q-72 26-116 88.5T240-478q0 45 17 87.5t53 78.5l10 10v-98h80v240H160Zm400-10v-84q72-26 116-88.5T720-482q0-45-17-87.5T650-648l-10-10v98h-80v-240h240v80H690l16 14q49 49 71.5 106.5T800-482q0 111-66.5 197.5T560-170Z"/></svg>
								</span>
								<span class="icon web">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M339.72-148.34q-65.73-28.34-114.36-76.92-48.63-48.58-76.99-114.26Q120-405.19 120-479.87q0-74.67 28.34-140.41 28.34-65.73 76.92-114.36 48.58-48.63 114.26-76.99Q405.19-840 479.87-840q74.67 0 140.41 28.34 65.73 28.34 114.36 76.92 48.63 48.58 76.99 114.26Q840-554.81 840-480.13q0 74.67-28.34 140.41-28.34 65.73-76.92 114.36-48.58 48.63-114.26 76.99Q554.81-120 480.13-120q-74.67 0-140.41-28.34ZM440-162v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q41-45 62.5-100.5T800-480.29q0-98.47-54.12-179.86Q691.77-741.54 600-777.54V-760q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z"/></svg>
								</span>
								<span class="text">
									<?= $value['name'] ?>
								</span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		foreach ($videoLang as $k => $valuelang):
			if(count($valuelang) <= 0) continue;
		?>
		<div class="liste-player">
			<div class="item-lang-player">
				<div class="head" onclick="toggleClass(this.parentNode, 'open')">
					<span class="title-list">
						<span class="name"><?= __EnnWatch->langToString($k) ?></span>
					</span>
					<div class="indice">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-360 280-560h400L480-360Z"/></svg>
					</div>
				</div>
				<div class="content">
					<ul id="focus-upate">
						<?php foreach ($valuelang as $value): ?>
						<li>
							<a class="<?php if($videoInfo['src'] == $value['src']) echo 'focus'; ?>"
								data-basename="<?= getBaseDomainName($value['src']) ?>"
								data-src="<?= $value['src'] ?>"
								data-str="<?= $value['name'] ?>"
								onclick="corePlayer.load(this, '<?= $value['src'] ?>', 'focus-upate')">
								<span class="icon focused">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M160-160v-80h110l-16-14q-49-49-71.5-106.5T160-478q0-111 66.5-197.5T400-790v84q-72 26-116 88.5T240-478q0 45 17 87.5t53 78.5l10 10v-98h80v240H160Zm400-10v-84q72-26 116-88.5T720-482q0-45-17-87.5T650-648l-10-10v98h-80v-240h240v80H690l16 14q49 49 71.5 106.5T800-482q0 111-66.5 197.5T560-170Z"/></svg>
								</span>
								<span class="icon web">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M339.72-148.34q-65.73-28.34-114.36-76.92-48.63-48.58-76.99-114.26Q120-405.19 120-479.87q0-74.67 28.34-140.41 28.34-65.73 76.92-114.36 48.58-48.63 114.26-76.99Q405.19-840 479.87-840q74.67 0 140.41 28.34 65.73 28.34 114.36 76.92 48.63 48.58 76.99 114.26Q840-554.81 840-480.13q0 74.67-28.34 140.41-28.34 65.73-76.92 114.36-48.58 48.63-114.26 76.99Q554.81-120 480.13-120q-74.67 0-140.41-28.34ZM440-162v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q41-45 62.5-100.5T800-480.29q0-98.47-54.12-179.86Q691.77-741.54 600-777.54V-760q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z"/></svg>
								</span>
								<span class="text">
									<?= $value['name'] ?>
								</span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>
<script type="text/javascript" src="/share/lib/fluttershy/main.js"></script>
<script type="text/javascript" src="/interface/js/core.player.js"></script>
<script type="text/javascript">
const Player = new class enn_player {
	constructor(obj) {
		this.bufferPlayer = <?= json_encode($videoLang); ?>;
	}
	isJson(value) {
	    // Déjà un objet/tableau JSON
	    if (typeof value === 'object' && value !== null) {
	        return true;
	    }

	    // Si ce n'est pas une string
	    if (typeof value !== 'string') {
	        return false;
	    }

	    try {
	        JSON.parse(value);
	        return true;
	    } catch {
	        return false;
	    }
	}
	getLangue(t, name) {
		document.querySelector('.item-lang-player span.title-list span.name').textContent = t.dataset.name
		document.querySelectorAll('.liste-player-langue li').forEach((e) => {
			e.classList.remove('focus')
		})
		t.classList.add('focus')
		document.querySelector('#focus-upate-langue-player').innerHTML = ''
		for (const [lang, cle] of Object.entries(this.bufferPlayer[name])) {
			document.querySelector('#focus-upate-langue-player').appendChild(this.fund(cle))
		}
	}
	getBaseDomainName(url) {
		// Regex pour extraire le domaine
		const pattern = /(?:https?:\/\/)?(?:www\.)?([^/]+)/i;

		const matches = url.match(pattern);

		if (matches) {
			// Séparer le domaine
			const domainParts = matches[1].split('.');

			let dom = domainParts[0] + '.' + domainParts[1];

			switch (domainParts.length) {
				case 3:
					dom = domainParts[1] + '.' + domainParts[2];
					break;
			}

			return dom;
		}

		return "Player #";
	}
	fund(value) {
		let name = "Source " + this.getBaseDomainName(value.src)
		const li = document.createElement('li');
		const a = document.createElement('a');
		//a.classList.add('')
		// Classe conditionnelle
		// if (videoInfo.ID === value.ID) {
		// 	a.classList.add('focus');
		// }
		// Data attributes
		a.dataset.basename = this.getBaseDomainName(value.src);
		a.dataset.src = value.src;
		a.dataset.str = name;
		// a.dataset.mime = value.mime[0];

		// Event
		a.onclick = function () {
			corePlayer.load(this, value.src, 'focus-upate');
		};

		// Icon span
		const iconSpan = document.createElement('span');
		iconSpan.classList.add('icon', 'focused');

		iconSpan.innerHTML = `
		<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
			<path d="M160-160v-80h110l-16-14q-49-49-71.5-106.5T160-478q0-111 66.5-197.5T400-790v84q-72 26-116 88.5T240-478q0 45 17 87.5t53 78.5l10 10v-98h80v240H160Zm400-10v-84q72-26 116-88.5T720-482q0-45-17-87.5T650-648l-10-10v98h-80v-240h240v80H690l16 14q49 49 71.5 106.5T800-482q0 111-66.5 197.5T560-170Z"/>
		</svg>
		`;

		const iconSpan2 = document.createElement('span');
		iconSpan2.classList.add('icon', 'web');

		iconSpan2.innerHTML = `
		<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M339.72-148.34q-65.73-28.34-114.36-76.92-48.63-48.58-76.99-114.26Q120-405.19 120-479.87q0-74.67 28.34-140.41 28.34-65.73 76.92-114.36 48.58-48.63 114.26-76.99Q405.19-840 479.87-840q74.67 0 140.41 28.34 65.73 28.34 114.36 76.92 48.63 48.58 76.99 114.26Q840-554.81 840-480.13q0 74.67-28.34 140.41-28.34 65.73-76.92 114.36-48.58 48.63-114.26 76.99Q554.81-120 480.13-120q-74.67 0-140.41-28.34ZM440-162v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q41-45 62.5-100.5T800-480.29q0-98.47-54.12-179.86Q691.77-741.54 600-777.54V-760q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z"/></svg>
		`;

		// Text span
		const textSpan = document.createElement('span');
		textSpan.classList.add('text');
		textSpan.textContent = name ?? value.lang_;

		// Assemblage
		a.appendChild(iconSpan);
		a.appendChild(iconSpan2);
		a.appendChild(textSpan);
		li.appendChild(a);

		return li
	}
}

const corePlayer = new enn_player({
	playerContenaire: document.querySelector('.contenaire-player'),
	info: {
		uniqid: "<?= $videoChannel_____['uniqid'] ?>",
		channel: "<?= $channelInfo['uniqid'] ?>",
		saison: <?= $videoChannel_____['saison'] ?>,
		episode: <?= $videoChannel_____['episode'] ?>,
	},
	source: {
		video: "<?= __OmsyString->htmlDecode($videoInfo['src'] ?? '') ?>",
		image: '<?= __EnnMain->cdnImageUrl ?>thumbnail/<?= $channelInfo['slug'] ?? '' ?>.jpg',
		mime: <?= json_encode(['iframe']) ?>,
		quality: "{}"
	},
})

var toggleClass = (element, name) => {
	element.classList.toggle(name)
}

document.querySelector('.infoplayer-others .content-description').addEventListener('click', (e) => {
	if(document.querySelector('.infoplayer-others .content-description .allstr').style.display == 'none') {
		document.querySelector('.infoplayer-others .content-description .allstr').style.display = 'block'
		document.querySelector('.infoplayer-others .content-description .substr').style.display = 'none'
	} else  {
		document.querySelector('.infoplayer-others .content-description .allstr').style.display = 'none'
		document.querySelector('.infoplayer-others .content-description .substr').style.display = 'block'
	}
})

var getInfoPlayer = () => {
	document.querySelector('body').classList.toggle('watching-info')
}
</script>
<?php include "include/footer.php"; ?>