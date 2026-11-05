<?php include "include/head.php"; ?>
<?php
$cat = explode(',', "Action, Adolescence, Aliens / Extra-terrestres, Amitié, Amour, Apocalypse, Art, Arts martiaux, Assassinat, Autre monde, Aventure, Combats, Comédie, Crime, Cyberpunk, Danse, Démons, Détective, Donghua, Dragon, Drame, Ecchi, Ecole, Elfe, Enquête, Famille, Fantastique, Fantasy, Fantômes, Futur, Gastronomie, Ghibli, Guerre, Harcèlement, Harem, Harem inversé, Histoire, Historique, Horreur, Isekai, Jeunesse, Jeux, Jeux vidéo, Josei, Journalisme, Kaï, Mafia, Magical girl, Magie, Maladie, Mariage, Mature, Mechas, Médiéval, Militaire, Monde virtuel, Monstres, Musique, Mystère, Nekketsu, Ninjas, Nostalgie, Paranormal, Philosophie, Pirates, Police, Politique, Post-apocalyptique, Pouvoirs psychiques, Préhistoire, Prison, Psychologique, Quotidien, Religion, Réincarnation / Transmigration, Romance, Samouraïs, School Life, Science-Fantasy, Science-fiction, Scientifique, Seinen, Shôjo, Shôjo-Ai, Shônen, Shônen-Ai, Slice of Life, Société, Sport, Super pouvoirs, Super-héros, Surnaturel, Survie, Survival game, Technologies, Thriller, Tournois, Travail, Vampires, Vengeance, Voyage, Voyage temporel, Webcomic, Yakuza, Yaoi, Yokai, Yuri");
?>
<section class="enn-main-home window-width">
	<!-- <section class="filters-contenaire">
		<div class="filter-section">
			<div class="filter-head">
				<h3>Genres</h3>
			</div>
			<div class="filter-content">
				<ul>
					<?php foreach ($cat as $value): ?>
					<li onclick="filterAdd(this)">
						<input type="checkbox" name="genre[]" value="<?= $value ?>" class="input-genre">
						<label><?= $value ?></label>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section> -->
	<section class="enn-main-home list-subject-watchs" id="dynalist-section">
		<section class="item-subject-watch loading">
			<div class="head">
				<div class="name"></div>
				<div class="option"></div>
			</div>
			<div class="content">
				<ul class="wrap" id="catalogues"><?php for ($i=0; $i < 28; $i++): ?><li><a class="not-style serie-card" data-update="popup"><div class="min"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAPCAIAAABSnclZAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0UFJFMgBcAAADWSURBVBjTfZBNioUwEITTJkwSNRK8gSu9/33EGyhEzY+09lsIxnkwU6uu+qC6aRiGgb1ERADwWEFE7LfeSfEFqqp6J+KZjDHW2oLztm3Xdd22DREz7rqOiKSUiFiW5XVdzrmMhRCMMURMKWmtnXO5nIjO8/TeCyGEEPM8f58WY2yahnPuvTfG3GUZa60R8d6ilOKcZ2ytBYBpmsZxVEoty5JSynjfd0T8kfIiCiEcx3Hn0Pf9c11d1zFGKWUI4f6deD4MAN57xlgI4bZEVLC/BQD/YcbYB/13b4Bs85S4AAAAAElFTkSuQmCC"></div><div class="content"><span class="title"></span><span class="sub_title"></span></div></a></li><?php endfor; ?></ul>
			</div>
		</section>
	</section>
</section>
<div id="sentinel"></div>
<script>
let page = 1
let isLoading = false
let hasNext = true
let filtergenre = [];

const filterAdd = (t) => {
    const input = t.querySelector('input')
    input.checked = !input.checked

	document.getElementById('catalogues').innerHTML = `<?php for ($i=0; $i <= 20; $i++): ?><li><a class="not-style serie-card" data-update="popup"><div class="min"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAPCAIAAABSnclZAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0UFJFMgBcAAADWSURBVBjTfZBNioUwEITTJkwSNRK8gSu9/33EGyhEzY+09lsIxnkwU6uu+qC6aRiGgb1ERADwWEFE7LfeSfEFqqp6J+KZjDHW2oLztm3Xdd22DREz7rqOiKSUiFiW5XVdzrmMhRCMMURMKWmtnXO5nIjO8/TeCyGEEPM8f58WY2yahnPuvTfG3GUZa60R8d6ilOKcZ2ytBYBpmsZxVEoty5JSynjfd0T8kfIiCiEcx3Hn0Pf9c11d1zFGKWUI4f6deD4MAN57xlgI4bZEVLC/BQD/YcbYB/13b4Bs85S4AAAAAElFTkSuQmCC"></div><div class="content"><span class="title"></span><span class="sub_title"></span></div></a></li><?php endfor; ?>`
	document.querySelector('.item-subject-watch').classList.add('loading')

    filtergenre = []
    document.querySelectorAll('.input-genre').forEach((e) => {
    	if(e.checked === true) {
    		filtergenre.push(e.value)
    	}
    })

    page = 1
    hasNext = true
    isLoading = false
    reqCatalogue()
}

const reqCatalogue = () => {
	if (isLoading || !hasNext) return
	isLoading = true

	Omsy.request('/api/EigaNoNeko/v3/get_catalogue', {
		filter_genre: filtergenre,
		page: page,
		per_page: 28
	}, true)
	.then((e) => {
		hasNext = e.pagination.has_next
		page++

		let html = ''
		e.data.forEach((a) => {
			if(!a) return;
			html += Core.createCard({
				url: `/catalogue/${a.slug}`,
				image: `<?= __EnnMain->cdnImageUrl ?>poster/${a.slug}.jpg`,
				imageType: `poster`,
				title: `${a.title}`,
				sub_title: `${a.sub_title ?? ''}`,
				langues: `${a.langues ?? ''}`,
				description: `${a.description ?? ''}`,
			})
		})
		if(document.querySelector('.item-subject-watch.loading')) document.getElementById('catalogues').innerHTML = ''
		document.getElementById('catalogues').innerHTML += html
		document.querySelector('.item-subject-watch').classList.remove('loading')
		isLoading = false
	})
}

// Observer sur un sentinel en bas de page
const sentinel = document.querySelector('#sentinel')
const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) reqCatalogue()
}, { threshold: 0.1 })
observer.observe(sentinel)

// reqCatalogue()
</script>
<?php include "include/footer.php"; ?>