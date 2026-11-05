<?php include "include/head.php"; ?>
<section class="enn-main-home">
	<section class="enn-kit banner-homepage window-width">
		<div class="content">
			<div class="banner-buttons-controls"><?php for($i = 0; $i < count($heroes); $i++): ?><span></span><?php endfor; ?></div>
			<div class="all-banner">
				<?php foreach($heroes as $value): ?>
				<div class="banner-item" data-title="<?= $value['title'] ?? '' ?>">
					<!-- <video playsinline muted src="<?= __EnnMain->cdnImageUrl ?>video/<?= $value['slug'] ?>.mp4" style="display: none;" onerror="this.remove()"></video> -->
					<img fetchPriority="high" src="<?= __EnnMain->cdnImageUrl ?>hero/<?= $value['slug'] ?>.jpg" alt="Image <?= $value['slug'] ?>.jpg">
					<div class="banner-item-content">
						<h1><?= $value['title'] ?? '' ?></h1>
						<p><?= __OmsyString->substr(__OmsyMain->GFunction->htmlDecode($value['description'] ?? ''), 200) ?></p>
						<ul>
							<li>
								<a href="/watch/<?= $value['slug'] ?>?s=1&ep=7" class="enn-button enn-button-cta">
									<span class="icon">
										<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M320-200v-560l440 280-440 280Z"/></svg>
									</span>
									<span class="text">Lecture S1EP1</span>
								</a>
							</li>
							<li>
								<a href="/catalogue/<?= $value['slug'] ?>" class="enn-button">
									<span class="icon">
										<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-280h80v-240h-80v240Zm68.5-331.5Q520-623 520-640t-11.5-28.5Q497-680 480-680t-28.5 11.5Q440-657 440-640t11.5 28.5Q463-600 480-600t28.5-11.5ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
									</span>
									<span class="text">Plus d'infos</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="enn-main-home list-subject-watchs window-width" id="dynalist-section">
		<?php if($c == 0): ?>
		<?php
		$channel_view = json_decode(htmlspecialchars_decode($_COOKIE['watch_channel'] ?? ''), true) ?? [];
		if(count($channel_view) > 0 || isset($_SESSION['uniqid'])):
		?>
		<section class="item-subject-watch serie-watching item-subject-continue-watching loading">
			<div class="head">
				<div class="name"><h2><?= langToText('continueWatching') ?></h2></div>
				<div class="option"></div>
			</div>
			<div class="content">
				<ul class="serie-watching item-subject-continue-watching grid5 grey-card" id="continueWatching">
					<?php for ($i = 0; $i < 5; $i++): ?><li><a href="#" class="not-style serie-card"><div class="min"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAGCAYAAAD68A/GAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0TM3sHo7sAAACfSURBVAjXNY47DkBAFADn7YclJNuquf9xFG6gUSwii81T6WaSKUbGcVRVRUTw3vM8D3Vdk3NGVQEQEVwIgWEYsNZyHAdd11FVFeu6EmPEGMOyLBjvPU3TEEIAwDmHMYb7vlFVcs60bYu7rgtVZZ5ncs7EGCmlYK0lhICIsG0b7n1fUkoAqCr7vlNKIaVE3/cYYzjPE5mmSf/oH//9Z4APW+VNBjPDx/sAAAAASUVORK5CYII="></div><div class="content"><span class="title"></span><span class="sub_title"></span></div></a></li><?php endfor; ?>
				</ul>
			</div>
		</section>
		<script>
window.onload = () => {
request('/api/ennNode/get_watching', {}, true)
.then((value) => {
	let html = ''
	let countI = 0
	value.forEach((i) => {
		if(countI >= 5) return
		html += Core.createCard({
			url: `/watch/${i.id}?s=${i.saison}&ep=${i.episode}`,
			image: `<?= __EnnMain->cdnImageUrl ?>thumbnail/${i.id}.jpg`,
			imageType: `thumbnail`,
			title: `${i.title}`,
			sub_title: `${i.sub_title ?? ''}`,
			progress: `${(i.current_episodes / i.max_episodes) * 100}`
		})
		countI++
	})
	document.getElementById('continueWatching').innerHTML = html
	document.getElementById('continueWatching').parentNode.parentNode.classList.remove('loading')
})
}
		</script>
		<?php
		endif;

		foreach ($buff as $key => $value):
			$type = 'catalogue';
			if(isset($buff__[$key])) $type = 'blog';
		?>
		<section class="item-subject-watch <?= $type === 'blog' ? 'item-subject-blog' : '' ?>">
			<div class="head">
				<div class="name"><h2><?= langToText($key) ?></h2></div>
				<div class="option"></div>
			</div>
			<div class="content">
				<ul>
					<?php
					foreach ($value as $v):
						$image = ($type === 'blog')
						? ($v['thumbnail'] ?? 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAGCAYAAAD68A/GAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0TM3sHo7sAAACfSURBVAjXNY47DkBAFADn7YclJNuquf9xFG6gUSwii81T6WaSKUbGcVRVRUTw3vM8D3Vdk3NGVQEQEVwIgWEYsNZyHAdd11FVFeu6EmPEGMOyLBjvPU3TEEIAwDmHMYb7vlFVcs60bYu7rgtVZZ5ncs7EGCmlYK0lhICIsG0b7n1fUkoAqCr7vlNKIaVE3/cYYzjPE5mmSf/oH//9Z4APW+VNBjPDx/sAAAAASUVORK5CYII=')
						: (__EnnMain->cdnImageUrl.'poster/'.$v['id'].'.jpg' ?? 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAPCAIAAABSnclZAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0UFJFMgBcAAADWSURBVBjTfZBNioUwEITTJkwSNRK8gSu9/33EGyhEzY+09lsIxnkwU6uu+qC6aRiGgb1ERADwWEFE7LfeSfEFqqp6J+KZjDHW2oLztm3Xdd22DREz7rqOiKSUiFiW5XVdzrmMhRCMMURMKWmtnXO5nIjO8/TeCyGEEPM8f58WY2yahnPuvTfG3GUZa60R8d6ilOKcZ2ytBYBpmsZxVEoty5JSynjfd0T8kfIiCiEcx3Hn0Pf9c11d1zFGKWUI4f6deD4MAN57xlgI4bZEVLC/BQD/YcbYB/13b4Bs85S4AAAAAElFTkSuQmCC');
					?>
					<li>
						<a href="<?= ($type === 'blog') ? '/blog/'.$v['group'].'/'.$v['slug'] : '/catalogue/'.$v['id'] ?>" class="not-style serie-card">
							<div class="min load">
								<img src="<?= $image ?>" onerror="Core.imageErr(this, '<?= $image = ($type === 'blog') ? 'thumbnail' : 'poster' ?>')" onload="Core.imageLoad(this, '<?= $image = ($type === 'blog') ? 'thumbnail' : 'poster' ?>')">
							</div>

							<div class="content">
								<span class="title"><b><?= $v['title'] ?></b></span>
								<div>
									<?php if(isset($v['genres'])): ?>
									<span style="margin-top: 8px;">Genre</span>
									<span class="sub_title" style="margin-top: 0px;"><?php
                                    $genres = is_array($v['genres'] ?? null) ? $v['genres'] : [];
                                    echo implode(',', array_slice($genres, 0, 4));
                                    ?></span>
									<?php endif; ?>
									<?php if(isset($v['langues'])): ?>
									<span style="margin-top: 8px;">Langues</span>
									<span class="sub_title" style="margin-top: 0px;"><?= $v['langues'] ?></span>
									<?php endif; ?>
									<?php if(isset($v['description'])): ?>
									<div class="hover">
										<span style="margin-top: 8px;">Synopsis</span>
										<span class="sub_title" style="margin-top: 0px;"><?= $v['description'] ?? '' ?></span>
									</div>
									<?php endif; ?>
								</div>
								<?php if(isset($v['publish_date'])): ?>
								<div style="margin-top: 8px;">
									<span class="sub_title" style="margin-top: 0px;"><?= $v['publish_date'] ?></span>
								</div>
								<?php endif; ?>
							</div>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php endforeach; ?>
		<?php else: ?>
		<?php
		$sections = [];
		for ($i = 0; $i < 6; $i++) $sections[] = 'watch';
		for ($i = 0; $i < 2; $i++) $sections[] = 'blog';
		shuffle($sections);
		$sections[0] = 'watch';
		?>
		<?php foreach ($sections as $type): ?>
		<section class="item-subject-watch <?= $type === 'blog' ? 'item-subject-blog' : '' ?> loading">
			<div class="head">
				<div class="name"></div>
				<div class="option"></div>
			</div>
			<div class="content">
				<ul>
					<?php
					$count = ($type === 'blog') ? 4 : 7;
					$img = ($type === 'blog')
						? 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAGCAYAAAD68A/GAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0TM3sHo7sAAACfSURBVAjXNY47DkBAFADn7YclJNuquf9xFG6gUSwii81T6WaSKUbGcVRVRUTw3vM8D3Vdk3NGVQEQEVwIgWEYsNZyHAdd11FVFeu6EmPEGMOyLBjvPU3TEEIAwDmHMYb7vlFVcs60bYu7rgtVZZ5ncs7EGCmlYK0lhICIsG0b7n1fUkoAqCr7vlNKIaVE3/cYYzjPE5mmSf/oH//9Z4APW+VNBjPDx/sAAAAASUVORK5CYII='
						: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAPCAIAAABSnclZAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0UFJFMgBcAAADWSURBVBjTfZBNioUwEITTJkwSNRK8gSu9/33EGyhEzY+09lsIxnkwU6uu+qC6aRiGgb1ERADwWEFE7LfeSfEFqqp6J+KZjDHW2oLztm3Xdd22DREz7rqOiKSUiFiW5XVdzrmMhRCMMURMKWmtnXO5nIjO8/TeCyGEEPM8f58WY2yahnPuvTfG3GUZa60R8d6ilOKcZ2ytBYBpmsZxVEoty5JSynjfd0T8kfIiCiEcx3Hn0Pf9c11d1zFGKWUI4f6deD4MAN57xlgI4bZEVLC/BQD/YcbYB/13b4Bs85S4AAAAAElFTkSuQmCC';
					?>
					<?php for ($i = 0; $i < $count; $i++): ?>
					<li>
						<a href="#" class="not-style serie-card"><div class="min"><img src="<?= $img ?>"></div><div class="content"><span class="title"></span><span class="sub_title"></span></div></a>
					</li>
					<?php endfor; ?>
				</ul>
			</div>
		</section>
		<?php endforeach; ?>
		<?php endif; ?>
	</section>
</section>
<script>
const sections = <?= json_encode($sections ?? []); ?>;
</script>
<script type="text/javascript" src="/interface/js/indexPage.js"></script>
<script type="text/javascript">
let ins = 0
const insMax = document.querySelectorAll('.enn-main-home .banner-homepage .all-banner .banner-item')
const insMaxB = document.querySelectorAll('.enn-main-home .banner-homepage .banner-buttons-controls span')
updateBannerScroll()

function updateBannerScroll() {
	let inss = ins
	let ii = 0
	insMax.forEach((e) => {
		e.classList.remove('actif')
		insMaxB[ii].classList.remove('actif')
		ii++
	})
	insMax[ins].classList.add('actif')
	insMaxB[ins].classList.add('actif')
	document.querySelector('.all-banner').style.transform = `translateX(calc(-${ins * 100}%))`
	if(insMax[inss].querySelector('video')) {
		setTimeout(() => {
			console.log(inss)
			insMax[inss].querySelector('video').addEventListener('ended', videoTermined)
			insMax[inss].querySelector('video').style.display = 'block'
			insMax[inss].querySelector('video').play()
			function videoTermined(event) {
				insMax[inss].querySelector('video').removeEventListener('ended', videoTermined)

				insMax[inss].querySelector('video').pause()
				insMax[inss].querySelector('video').style.display = 'none'
				//updateBannerScroll()
				setTimeout(() => { updateBannerScroll() }, 8000)
			}
		}, 5000)
	} else setTimeout(() => { updateBannerScroll() }, 8000)
	ins++
	if(ins > insMax.length-1) ins = 0
}
<?php if($c > 0): ?>
catalogueHomepage()
<?php endif; ?>
</script>
<?php include "include/footer.php"; ?>