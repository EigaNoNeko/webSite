<section class="enn-catalogue-block enn-kit enn-kit-mgb catalogue-list-watchs loading">
	<div class="head" style="padding-right: 0;">
		<h2 id="saison-name">S1 : <?= $catalogueInfo['title'] ?></h2>
		<div class="button">
			<?php if ($catalogueInfo['statistics']['saison'] > 1): ?>
			<!-- <select onchange="getSaison(this)">
				<option>Saison</option>
				<?php for ($i=1; $i <= $catalogueInfo['statistics']['saison']; $i++): ?>
				<option value="<?= $i ?>">Saison <?= $i ?></option>
				<?php endfor; ?>
			</select> -->
			<div class="not-desktop option-saison">
				<a onclick="getSaison_(this, 'back')" data-saison="1" class="btn-back-saison disabled">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
					</span>
					<span class="text not-desktop">S1</span>
					<span class="text not-mobile">Saison précédente</span>
				</a>
				<a onclick="getSaison_(this, 'next')" data-saison="2" class="btn-next-saison <?php if($catalogueInfo['statistics']['saison'] <= 1) echo 'disabled'; ?> ">
					<span class="text not-desktop">S2</span>
					<span class="text not-mobile">Saison suivante</span>
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z"/></svg>
					</span>
				</a>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="content">
		<ul class="content" id="video-list">
			<?php
			foreach(array_slice($catalogueInfo['data']['episodes'] ?? [], 0, 25) as $value):
				card([
					'href' => "/watch/{$catalogueInfo['slug']}?s={$value['saison']}&ep={$value['episode']}",
					'image' => __EnnMain->cdnImageUrl."thumbnail/{$catalogueInfo['slug']}.jpg",
					'image_type' => 'thumbnail',
					'little_title' => $catalogueInfo['title'],
					'title' => $value['title'],
					'sub_title' => "S{$value['saison']}E{$value['episode']} - ".($value['lang'] ?? ''),
				]);
			endforeach;
			?>
		</ul>
	</div>
	<div class="foot">
		<?php if(count($catalogueInfo['data']['episodes'] ?? []) > 25): ?>
		<a class="see-more-button" onclick="fetchSaison(1); this.remove()">Voir plus</a>
		<?php endif; ?>
		<?php if ($catalogueInfo['statistics']['saison'] > 1): ?>
		<div class="option-saison" style="border-top: solid 1px var(--color-primary-3); padding-top: 8px; margin-top: 16px;">
			<a onclick="getSaison_(this, 'back')" data-saison="1" class="btn-back-saison disabled">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
				</span>
				<span class="text">Saison précédente</span>
			</a>
			<a onclick="getSaison_(this, 'next')" data-saison="2" class="btn-next-saison <?php if($catalogueInfo['statistics']['saison'] <= 1) echo 'disabled'; ?> ">
				<span class="text">Saison suivante</span>
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m321-80-71-71 329-329-329-329 71-71 400 400L321-80Z"/></svg>
				</span>
			</a>
		</div>
		<?php endif; ?>
	</div>
</section>
<script type="text/javascript">
const btnsBack = document.querySelectorAll('.btn-back-saison')
const btnsNext = document.querySelectorAll('.btn-next-saison')
const saisonName = document.getElementById('saison-name')

function getSaison(e) {
    fetchSaison(e.value, e);
}

function updateSaisonUI(currentSaison) {
    saisonName.textContent = 'S' + currentSaison + ' : <?= $catalogueInfo["title"] ?>'

    btnsBack.forEach(btn => {
        btn.dataset.saison = currentSaison - 1

        const notDesktop = btn.querySelector('.text.not-desktop')
        if (notDesktop) notDesktop.textContent = 'S' + (currentSaison - 1)

        if (currentSaison <= 1) btn.classList.add('disabled')
        else btn.classList.remove('disabled')
    })

    btnsNext.forEach(btn => {
        btn.dataset.saison = currentSaison + 1

        const notDesktop = btn.querySelector('.text.not-desktop')
        if (notDesktop) notDesktop.textContent = 'S' + (currentSaison + 1)

        if (currentSaison >= parseInt(maxSaison)) btn.classList.add('disabled')
        else btn.classList.remove('disabled')
    })
}

async function getSaison_(t, ty) {
    const currentSaison = parseInt(t.dataset.saison)
    fetchSaison(currentSaison)
    updateSaisonUI(currentSaison)
}
</script>