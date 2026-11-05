class enn_player {
	constructor(obj) {
		this.playerContenaire = obj.playerContenaire

		this.object = obj
		this.source = obj.source

		let cook = Omsy.getCookie('watch_channel');
		cook = cook ? JSON.parse(cook) : {};
		cook[obj.info.channel] = {
			video: obj.info.uniqid,
			saison: obj.info.saison,
			episode: obj.info.episode,
			date: Date.now()
		};
		cook = Object.fromEntries(
			Object.entries(cook).sort((a, b) => {
				const dateA = a[1]?.date ?? 0;
				const dateB = b[1]?.date ?? 0;
				return dateB - dateA;
			})
		);
		Omsy.setCookie('watch_channel', JSON.stringify(cook));

		// if(this.source.video.includes('.mp4')) this.source.video = '/api/EigaNoNeko/v3/get_player_video?src='+this.source.video
		if(!this.source.video.includes('.mp4')) this.loadIframePlayer()
		else this.loadNativePlayer()
		this.updatPlayerSize()
	}

	load(button, frame = '', fous = "choice-subtitle") {
		this.playerContenaire.innerHTML = '<div class="loading-player"><img src="/interface/img/default_thumbnail.webp" width="100%"><span class="loading"></span></div>'
		button.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.querySelectorAll('li').forEach((e) => {
			e.querySelector('a').classList.remove('focus')
		})

		button.classList.add('focus')

		this.source.mime = [button.dataset.mime]
		this.source.video = button.dataset.src
		this.source.basename = button.dataset.basename

		if(!this.source.video.includes('.mp4')) this.loadIframePlayer()
		else this.loadNativePlayer()
		this.updatPlayerSize()
	}

	updatPlayerSize() {
		const parentWidth = this.playerContenaire.offsetWidth;
		const ratio = 1080 / 1920; // Hauteur / Largeur
		this.playerContenaire.style.height = (parentWidth * ratio) + 'px';
	}

	slugify(str) {
	  return str
	    .normalize("NFD")                        // décompose les accents (é → e + ́)
	    .replace(/[\u0300-\u036f]/g, "")         // supprime les accents
	    .toLowerCase()                           // minuscules
	    .trim()                                  // supprime les espaces en début/fin
	    .replace(/[^a-z0-9\s-]/g, "")           // supprime les caractères spéciaux
	    .replace(/[\s]+/g, "_")                  // espaces → tirets
	    .replace(/-+/g, "_")                    // tirets multiples → un seul
	    .toUpperCase();
	}

	async loadIframePlayer() {
		const proxyUrl = "/api/EigaNoNeko/v3/get_proxy.php?catalogue=" + this.object.info.channel + "&url=" + encodeURIComponent(this.source.video.replace(/vidmoly\.(to|net)/g, 'vidmoly.biz'));
		const res = await fetch(proxyUrl);
		const data = await res.json();
		console.log(data)
		if (!data.data.ok) {
			console.log(data)
			console.log("Erreur HTTP :", data.data.status);
			this.playerContenaire.innerHTML = `<div style="background: #111; width: 100%; overflow: hidden; user-select: none; pointer-events: none;">

  <div style="aspect-ratio: 16/9; background: #1a1a1a; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px;">

    <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(226,75,74,0.15); border: 1.5px solid #E24B4A; display: flex; align-items: center; justify-content: center;">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E24B4A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" fill="transparent"/>
        <line x1="12" y1="9" x2="12" y2="13"/>
        <line x1="12" y1="17" x2="12.01" y2="17"/>
      </svg>
    </div>

    <div style="text-align: center; padding: 0 2rem;">
      <p style="color: #f5f5f5; font-size: 15px; font-weight: 500; margin: 0 0 8px;">Impossible de lire cette vidéo</p>
      <p style="color: #888; font-size: 13px; margin: 0; line-height: 1.6;">
        Votre lecteur actuel ne fonctionne pas.<br>
        Essayez un autre lecteur pour continuer à regarder.
      </p>
    </div>

    <div style="background: rgba(226,75,74,0.12); border: 1px solid rgba(226,75,74,0.4); border-radius: 6px; padding: 4px 10px;">
      <span style="color: #E24B4A; font-size: 11px; font-family: monospace;">${this.slugify(data.error ?? 'ERR MEDIA NOT SUPPORTED')}</span>
    </div>

  </div>
</div>`
			return;
		}

		this.playerContenaire.innerHTML = `<iframe
			id="player"
			src="${this.source.video.replace(/vidmoly\.(to|net)/g, 'vidmoly.biz')}"
			width="100%" height="100%"
			style="display: block; border: medium;"
			allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
			allowfullscreen="true"
			sandbox="allow-scripts allow-same-origin"
		></iframe>`

		this.updatPlayerSize()
	}

	loadNativePlayer() {
		const fluttershy = new Fluttershy(this.playerContenaire, {
			src: this.source.video,
			poster: this.source.image,
		})
	}
}