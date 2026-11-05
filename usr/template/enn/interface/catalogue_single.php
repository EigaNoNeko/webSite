<?php
$json__ = [
	"translate" => [
		'saison' => 'Saga',

		'saison1' => 'East Blue',
		'saison2' => 'Alabasta',
		'saison3' => 'Ile céleste',
		'saison4' => 'Water Seven',
		'saison5' => 'Thriller Bark',
		'saison6' => 'Guerre au sommet',
		'saison7' => 'Ile des Hommes-Poissons',
		'saison8' => 'Dressrosa',
		'saison9' => 'Ile tougato',
		'saison10' => 'Pays des Wa',
		'saison11' => 'Egghead',
		'saison12' => 'Elbaf',
	]
];

//__EnnCatalogues->addObj($catalogueInfo['uniqid'], "translate", 'translate', json_encode($json__['translate']));
if(isset($catalogueInfo['object']['translate'])) $catalogueInfo['object']['translate'] = json_decode(htmlspecialchars_decode($catalogueInfo['object']['translate']['translate']) ?? '[]', true);
?>
<?php
function card($obj) {
?>
<li class="item-catalogue">
	<a href="<?= $obj['href'] ?>">
		<div class="min">
			<img src="<?= $obj['image'] ?>" onerror="this.src = Core.notLoadImage('<?= $obj['image_type'] ?>')">
		</div>
		<div class="text">
			<span class="little_title"><?= $obj['little_title'] ?></span>
			<span class="title"><?= $obj['title'] ?></span>
			<span class="sub_title"><?= $obj['sub_title'] ?></span>
		</div>
	</a>
</li>
<?php
}
?>
<?php include "include/head.php"; ?>
<section class="catalogue-header">
	<div class="window-width catalogue-banner">
		<div class="min">
			<span class="shadow"></span>
			<img src="<?= __EnnMain->cdnImageUrl ?>hero/<?= $catalogueInfo['slug'] ?>.jpg" height="480px" onerror="this.src = Core.notLoadImage('banner')">
		</div>
		<div class="content">
			<div class="text">
				<h1 class="title"><?= $catalogueInfo['title'] ?? '' ?></h1>
				<div class="description-a" onclick="this.classList.toggle('open')">
					<p class="description-substr"><?= __OmsyString->substr(__OmsyMain->GFunction->htmlDecode($catalogueInfo['description'] ?? ''), 155) ?> <b>Lire plus</b></p>
					<p class="description"><?= __OmsyMain->GFunction->htmlDecode($catalogueInfo['description'] ?? '') ?> <b>Lire moin</b></p>
				</div>
				<div class="genres">
					<ul class="list-tags enn-kit-ul-horizon">
						<?php foreach (array_slice($catalogueInfo['genres'] ?? [], 0, 4) as $value): ?>
						<li>
							<a href="/search?query_search=<?= trim(__OmsyMain->GFunction->htmlDecode($value)) ?>"><?= __OmsyMain->GFunction->htmlDecode($value) ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="list-info">
					<ul class="enn-kit-ul-horizon">
						<!-- <?php if(isset($catalogueInfo['status']) && !empty($catalogueInfo['status'])): ?>
						<li><span class="kit-tag"><?= $catalogueInfo['status'] ?></span></li>
						<?php endif; ?> -->
						<li><span style="font-weight: 200; opacity: .7;"><?= __EnnWatch->langToLitleString($catalogueInfo['data']['episodes'][1]['lang'] ?? '') ?></span></li>
						<li><span style="font-weight: 500;"><?= $catalogueInfo['statistics']['episode'] ?? 0 ?> Episodes</span></li>
						<li><span style="font-weight: 500;"><?= $catalogueInfo['statistics']['saison'] ?? 0 ?> Saisons</span></li>
					</ul>
					<?php if(count(explode(',', $catalogueInfo['streaming'] ?? '') ?? []) > 0): ?>
					<ul class="enn-kit-ul-horizon" style="margin-top: 16px;">
						<li><span>Disponible sur</span></li>
						<?php foreach(explode(',', $catalogueInfo['streaming'] ?? '') ?? [] as $value): ?>
						<li>
							<span style="font-weight: 900;"><?= strtoupper($value) ?></span>
							<!-- <img src="/interface/img/logo-plateforme/<?= $value ?>.png" height="24px"> -->
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
			<div class="catalogue-user-action">
				<div class="started">
					<ul>
						<li>
							<a href="/watch/<?= $catalogueInfo['slug'] ?>" class="enn-button enn-button-cta">
								<span class="icon">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M340-236.16v-487.68L723.07-480 340-236.16ZM400-480Zm0 134 210.77-134L400-614v268Z"/></svg>
								</span>
								<span class="text">Regarder S1E1</span>
							</a>
						</li>
						<li>
							<a id="follow-channel-button" class="enn-button cta-color <?php if($verify['follow']) echo 'actif'; ?>">
								<span class="icon">
									<?php if($verify['follow']): ?>
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M698.77-600 600-698.77l41.77-42.77 57 57 141-142 42.77 42.77L698.77-600ZM220-150v-597.69Q220-778 241-799q21-21 51.31-21H520v60H292.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46V-242l200-86 200 86v-278h60v370L480-261.54 220-150Zm60-610h240-240Z"/></svg>
									<?php else: ?>
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M220-150v-597.69Q220-778 241-799q21-21 51.31-21H520v60H292.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46V-242l200-86 200 86v-278h60v370L480-261.54 220-150Zm60-610h240-240Zm400 160v-80h-80v-60h80v-80h60v80h80v60h-80v80h-60Z"/></svg>
									<?php endif; ?>
								</span>
								<span class="text">Favoris</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="action-signin">
					<ul>
						<li>
							<a href="/catalogue/<?= $catalogueInfo['slug'] ?>/reports" class="enn-button">
								<span class="icon">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M220-130v-650h323.84l16 80H780v360H536.16l-16-80H280v290h-60Zm280-430Zm86 160h134v-240H510l-16-80H280v240h290l16 80Z"/></svg>
								</span>
								<span class="text">Signaler</span>
							</a>
						</li>
						<li>
							<a id="like-channel-button" class="enn-button <?php if($verify['like']) echo 'actif'; ?>">
								<span class="icon">
									<?php if($verify['like']): ?>
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M709.23-140H313.08v-480l265.38-263.84L612.3-850q6.24 6.23 10.35 16.5 4.12 10.27 4.12 19.35V-804L584.3-620h243.39q28.54 0 50.42 21.89Q900-576.23 900-547.69v64.61q0 6.23-1.31 13.46t-3.92 13.47L780.15-185.69q-8.61 19.23-28.84 32.46T709.23-140ZM253.08-620v480H100v-480h153.08Z"/></svg>
									<?php else: ?>
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M709.23-140H288.46v-480l265.39-263.84L587.69-850q6.23 6.23 10.35 16.5 4.11 10.27 4.11 19.35V-804l-42.46 184h268q28.54 0 50.42 21.89Q900-576.23 900-547.69v64.61q0 6.23-1.62 13.46-1.61 7.23-3.61 13.47L780.15-185.69q-8.61 19.23-28.84 32.46T709.23-140Zm-360.77-60h360.77q4.23 0 8.65-2.31 4.43-2.31 6.74-7.69L840-480v-67.69q0-5.39-3.46-8.85t-8.85-3.46H483.85L534-779.23 348.46-594.46V-200Zm0-394.46V-200v-394.46Zm-60-25.54v60H160v360h128.46v60H100v-480h188.46Z"/></svg>
									<?php endif; ?>
								</span>
								<!-- <span class="text"><?= $counting['like'] ?></span> -->
							</a>
						</li>
						<li>
							<a id="unlike-channel-button" class="enn-button <?php if($verify['unlike']) echo 'actif'; ?>">
								<span class="icon">
									<?php if($verify['unlike']): ?>
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-840h400v520L360-40l-50-50q-7-7-11.5-19t-4.5-23v-14l44-174H120q-32 0-56-24t-24-56v-80q0-7 1.5-15t4.5-15l120-282q9-20 30-34t44-14Zm480 520v-520h160v520H720Z"/></svg>
									<?php else: ?>
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M250.77-803.84h420.77v479.99L406.15-60l-33.84-33.85q-6.23-6.23-10.35-16.5-4.11-10.27-4.11-19.34v-10.16l42.46-184h-268q-28.54 0-50.42-21.88Q60-367.62 60-396.15v-64.62q0-6.23 1.62-13.46 1.61-7.23 3.61-13.46l114.62-270.46q8.61-19.23 28.84-32.46t42.08-13.23Zm360.77 60H250.77q-4.23 0-8.65 2.3-4.43 2.31-6.74 7.7L120-463.84v67.69q0 5.38 3.46 8.84 3.46 3.47 8.85 3.47h343.84L426-164.61l185.54-184.77v-394.46Zm0 394.46v-394.46 394.46Zm60 25.53v-59.99H800v-360H671.54v-60H860v479.99H671.54Z"/></svg>
									<?php endif; ?>
								</span>
								<!-- <span class="text"><?= $counting['unlike'] ?></span> -->
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="window-width enn-main-catalogue enn-grid-catalogue">
	<section class="left">
	<?php if($catalogueInfo['statistics']['saison'] > 1 && (isset($catalogueInfo['data']['episodes']) && count($catalogueInfo['data']['episodes']) > 12)): ?>
<section class="enn-catalogue-block enn-kit enn-kit-mgb catalogue-list-watchs catalogue-list-saisons">
	<div class="head">
		<h2>Saisons</h2>
		<div class="button"></div>
	</div>
	<div class="content">
		<ul class="content" id="video-list">
			<?php
			for ($i=1; $i <= $catalogueInfo['statistics']['saison']; $i++):
				card([
					'href' => "/watch/{$catalogueInfo['slug']}?s=$i&ep=1",
					'image' => __EnnMain->cdnImageUrl."thumbnail/{$catalogueInfo['slug']}.jpg",
					'image_type' => 'thumbnail',
					'little_title' => $catalogueInfo['title'],
					'title' => $catalogueInfo['object']['translate']['saison'.$i] ?? 'Saison '.$i,
					'sub_title' => ($catalogueInfo['object']['translate']['saison'] ?? 'Saison') . ' ' . $i,
				]);
			endfor;
			?>
		</ul>
	</div>
</section>
		<?php else: ?>
		<?php include 'catalogue_single_watchs.php'; ?>
		<?php endif; ?>
	<?php if(isset($catalogueInfo['data']['episodes-hs']) && count($catalogueInfo['data']['episodes-hs']) > 0): ?>
<section class="enn-catalogue-block enn-kit enn-kit-mgb catalogue-list-watchs catalogue-list-saisons">
	<div class="head">
		<h2>Hors-serie</h2>
		<div class="button"></div>
	</div>
	<div class="content">
		<ul class="content" id="video-list">
			<?php
			foreach($catalogueInfo['data']['episodes-hs'] as $key => $value):
				card([
					'href' => "/watch/{$catalogueInfo['slug']}?s=1&ep=1&type=$key",
					'image' => __EnnMain->cdnImageUrl."thumbnail/{$catalogueInfo['slug']}.jpg",
					'image_type' => 'thumbnail',
					'little_title' => $catalogueInfo['title'],
					'title' => $catalogueInfo['object']['translate'][$key] ?? $key,
					'sub_title' => $key,
				]);
			endforeach;
			?>
		</ul>
	</div>
</section>
		<?php endif; ?>
		<?php if(isset($catalogueInfo['kids']) && count($catalogueInfo['kids']) > 0): ?>
		<section class="enn-catalogue-block enn-kit enn-kit-mgb catalogue-list-watchs">
			<div class="head">
				<h2>A voir aussi</h2>
			</div>
			<div class="content">
				<ul class="content">
					<?php foreach ($catalogueInfo['kids'] as $key => $value): ?>
					<li class="item-catalogue">
						<div class="min">
							<a href="/catalogue/<?= $value['slug'] ?>/">
								<img src="<?= $value['thumbnail']['thumbnail'] ?? $catalogueInfo['image']['thumbnail'] ?>">
							</a>
						</div>
						<div class="text">
							<span class="title"><a href="/catalogue/<?= $value['slug'] ?>/"><?= $value['information']['title'] ?></a></span>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php endif; ?>
		<section class="grid-2x100">
		<?php
		$characters = __OmsySql->execute('SELECT uniqid, name, role, voice_actors FROM enncore_characters WHERE catalogue_id = ? LIMIT 8', [$catalogueInfo['uniqid']])->fetchAll();
		if(isset($characters[0])):
		?>
		<section class="enn-catalogue-block enn-kit enn-kit-mgb catalogue-list-characters">
			<div class="head">
				<h2>Character / People</h2>
				<div class="button">
					<ul class="reset-style display-flex"></ul>
				</div>
			</div>
			<div class="content">
				<ul class="content">
					<?php
					foreach ($characters as $value):
						$value['voice_actors'] = json_decode(htmlspecialchars_decode($value['voice_actors']), true);
						if(!isset($value['voice_actors'])) continue;
						if(count($value['voice_actors']) <= 0) continue;

						$language = array_key_exists('Japanese', $value['voice_actors'])
                            ? 'Japanese'
                            : array_key_first($value['voice_actors']);
                        
                        $voiceActorId = $value['voice_actors'][$language][0];
						if(isset($value['voice_actors']) && count($value['voice_actors']) > 0) $value['voice_actor'] = __OmsySql->execute('SELECT uniqid, name, role FROM enncore_characters WHERE uniqid = ?', [$voiceActorId])->fetchAll()[0] ?? [];
						else continue;
					?>
					<?php
					__EnnCardItem->people([
						'people' => [
							"href" => '/catalogue/',
							"image" => __EnnMain->cdnImageUrl.'/voice-actors/' . __OmsyString->slugify($value['voice_actor']['name']) . '_' . $value['voice_actor']['uniqid'] . '.jpg',
							"title" => $value['voice_actor']['name'] ?? '',
							"sub_title" => array_key_first($value['voice_actors']),
						],
						'character' => [
							"href" => '/catalogue/',
							"image" => __EnnMain->cdnImageUrl . 'characters/' . $catalogueInfo['slug'] . '_' . __OmsyString->slugify($value['name']) . '_' . $value['uniqid'] . '.jpg',
							"title" => $value['name'],
							"sub_title" => $value['role'],
						]
					]);
					?>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php endif; ?>
		<?php if(isset($catalogueInfo['staff'][0])): ?>
		<section class="enn-catalogue-block enn-kit enn-kit-mgb catalogue-list-characters">
			<div class="head">
				<h2>Staff</h2>
				<div class="button">
					<ul class="reset-style display-flex"></ul>
				</div>
			</div>
			<div class="content">
				<ul class="content">
					<?php
					foreach (array_slice($catalogueInfo['staff'] ?? [], 0, 8) as $value):
						$da = __OmsySql->execute('SELECT uniqid, name FROM enncore_characters WHERE uniqid = ?', [$value['id']])->fetchAll()[0] ?? [];
						if(!isset($da['name'])) continue;
						$value = array_merge($value, $da);
					?>
					<?php
					__EnnCardItem->people([
						'people' => [
							"href" => '/catalogue/',
							"image" => '/share/_omsy/download/staff/' . __OmsyString->slugify($value['name']) . '_' . $value['uniqid'] . '.jpg',
							"title" => $value['name'],
							"sub_title" => $value['role'],
						]
					]);
					?>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php endif; ?>
		</section>
	</section>
	<section class="right"></section>
</section>
<script type="text/javascript">
const maxSaison = <?= $catalogueInfo['statistics']['saison'] ?>

Core.configLikeButton({
	like: document.getElementById('like-channel-button'),
	unlike: document.getElementById('unlike-channel-button'),
	follow: document.getElementById('follow-channel-button'),
	channel: '<?= $catalogueInfo['uniqid'] ?>'
})

const fetchSaison = (intVideoCannal, t) => {
	document.getElementById('video-list').innerHTML = `<?php for ($i = 0; $i < 25; $i++): ?><li class="item-catalogue"><a href="#" class="not-style serie-card"><div class="min"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAGCAYAAAD68A/GAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0TM3sHo7sAAACfSURBVAjXNY47DkBAFADn7YclJNuquf9xFG6gUSwii81T6WaSKUbGcVRVRUTw3vM8D3Vdk3NGVQEQEVwIgWEYsNZyHAdd11FVFeu6EmPEGMOyLBjvPU3TEEIAwDmHMYb7vlFVcs60bYu7rgtVZZ5ncs7EGCmlYK0lhICIsG0b7n1fUkoAqCr7vlNKIaVE3/cYYzjPE5mmSf/oH//9Z4APW+VNBjPDx/sAAAAASUVORK5CYII="></div><div class="content"><span class="title"></span><span class="sub_title"></span></div></a></li><?php endfor; ?>`

	request('/api/EigaNoNeko/v3/get_episodes', {
		channel: "<?= $catalogueInfo['uniqid'] ?>",
		playlist: intVideoCannal
	}, true)
	.then(async (res) => {
		await sleep(1000);
		let html = ''
		Object.keys(res).forEach((key) => {
			const item = res[key]
			html += `<li class="item-catalogue"><a href="/watch/<?= $catalogueInfo['slug'] ?>?s=${intVideoCannal}&ep=${item.episode}">
					<div class="min">
						<img src="<?= __EnnMain->cdnImageUrl ?>thumbnail/<?= $catalogueInfo['slug'] ?>.jpg" onerror="this.src = Core.notLoadImage('thumbnail')">
					</div>
					<div class="text">
						
							<span class="little_title"><?= $catalogueInfo['title'] ?></span>
							<span class="title">Episode ${item.episode}</span>
							<span class="sub_title">S${intVideoCannal}E${item.episode} - ${item.lang ?? ''}</span>
						
					</div></a>
				</li>`
		})
		document.getElementById('video-list').innerHTML = html
	})
}
</script>
<?php include "include/footer.php"; ?>