const langUpdate = {
	french: {
		continueWatching: "Continuer à regarder",
		newEpisodesAdded: "Nouveaux episodes",
		mostWatched: "Les plus regarder",
		favoriteWatching: "Vos favoris",
		theClassic: "Les classics"
	},
	english: {
		continueWatching: "Continue watching",
		newEpisodesAdded: "New episodes added",
		mostWatched: "Most watched",
		favoriteWatching: "Your favorite",
		theClassic: "The classic"
	}
}
const langToText = (id) => {
	let lang = ''
	if(!lang) lang = 'french'
	if(!langUpdate[lang]) lang = 'french'
	if(langUpdate[lang][id]) return langUpdate[lang][id];
	return id;
}

function shuffle(array) {
 	var m = array.length, t, i;
	while (m) {
		i = Math.floor(Math.random() * m--);
		t = array[m];
	 	array[m] = array[i];
		array[i] = t;
	}

	return array;
}
const catalogueHomepage = () => {
	Promise.all([
		request('/api/ennNode/index', { channel_view: cookieWatching }, true),
		request('/api/omsyBlog/v1/index', {}, true)
	])
	.then(([responseHomepage, responseBlog]) => {
		const data = {
			...responseHomepage,
			...responseBlog
		}
		const entries = Object.entries(data)

        const fixedEntry = entries.find(([key]) => key === 'continueWatching')
        const fixedEntry_ = entries.find(([key]) => key === 'newEpisodesAdded')
        
        const others = entries.filter(([key]) => 
        	key !== 'continueWatching' && key !== 'newEpisodesAdded'
        )
        
        others.sort(() => Math.random() - 0.5)
        
        const shuffledObject = Object.fromEntries([
        	...(fixedEntry ? [fixedEntry] : []),
        	...(fixedEntry_ ? [fixedEntry_] : []),
        	...others
        ])

		let html = ''
		for (const [key, value] of Object.entries(shuffledObject)) {
			if(value.length <= 0) continue;

			let class_ = ''
			let class__ = ''
			let thumbnailType = 'poster'
			let countI = 0
			let progress = null

			if(
				key == 'continueWatching'
			) {
				thumbnailType = "thumbnail"
				class_ = 'serie-watching item-subject-continue-watching'
				class__ = 'serie-watching item-subject-continue-watching grid5 grey-card'
			}
			if(
				(responseBlog[key] && responseBlog[key].length > 0)
			) {
				thumbnailType = "thumbnail"
				class_ = 'serie-watching item-subject-blog'
			}
			html += `<section class="item-subject-watch ${class_}">
				<div class="head">
					<div class="name">
						<h2>${langToText(key)}</h2>
					</div>
					<div class="option">
					</div>
				</div>
				<div class="content">
					<ul class="${class__}">`
					value.forEach((i) => {
						let url = `#`
						let thumbnail = Core.images['poster']
						if(key == 'continueWatching') {
							url = `/watch/${i.id}/${i.video}`
							if(i.images.thumbnail[0] == '/') i.images.thumbnail = 'https://eiganoneko.com/'+i.images.thumbnail  
							thumbnail = i.images.thumbnail ?? Core.images['thumbnail']
							progress = `${(i.current_episodes / i.max_episodes) * 100}`
						}
						else if((responseBlog[key] && responseBlog[key].length > 0)) {
							url = `blog/${i.group}/${i.slug}`
							if(i.thumbnail[0] == '/') i.thumbnail = 'https://eiganoneko.com/'+i.thumbnail  
							thumbnail = i.thumbnail ?? Core.images['thumbnail']
						} else {
							url = `/catalogue/${i.id}`
							if(i.images.poster[0] == '/') i.images.poster = ``  
							thumbnail = i.images.poster ?? Core.images['poster']
						}

						if(countI >= 7) return
						if(key == 'continueWatching' && countI >= 5) return

						html += Core.createCard({
							url: `${url}`,
							image: `${thumbnail}`,
							imageType: `${thumbnailType}`,
							title: `${i.title}`,
							sub_title: `${i.sub_title ?? ''}`,
							progress: progress
						})
						countI++
					})
					html += `</ul>
				</div>
			</section>`
		}

		document.getElementById('dynalist-section').innerHTML = html
	})
	// .catch((error) => {
	// 	console.error(error)
	// })

}
function rand(max) {
	return Math.floor(Math.random() * max);
}
// catalogueHomepage()