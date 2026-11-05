const Core = new class enn_core {
	images = {
		banner: '/interface/img/banner.png',

		thumbnail: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAGCAYAAAD68A/GAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0TM3sHo7sAAACfSURBVAjXNY47DkBAFADn7YclJNuquf9xFG6gUSwii81T6WaSKUbGcVRVRUTw3vM8D3Vdk3NGVQEQEVwIgWEYsNZyHAdd11FVFeu6EmPEGMOyLBjvPU3TEEIAwDmHMYb7vlFVcs60bYu7rgtVZZ5ncs7EGCmlYK0lhICIsG0b7n1fUkoAqCr7vlNKIaVE3/cYYzjPE5mmSf/oH//9Z4APW+VNBjPDx/sAAAAASUVORK5CYII=',
		poster: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAPCAIAAABSnclZAAAAAXNSR0IB2cksfwAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB+kKHA0UFJFMgBcAAADWSURBVBjTfZBNioUwEITTJkwSNRK8gSu9/33EGyhEzY+09lsIxnkwU6uu+qC6aRiGgb1ERADwWEFE7LfeSfEFqqp6J+KZjDHW2oLztm3Xdd22DREz7rqOiKSUiFiW5XVdzrmMhRCMMURMKWmtnXO5nIjO8/TeCyGEEPM8f58WY2yahnPuvTfG3GUZa60R8d6ilOKcZ2ytBYBpmsZxVEoty5JSynjfd0T8kfIiCiEcx3Hn0Pf9c11d1zFGKWUI4f6deD4MAN57xlgI4bZEVLC/BQD/YcbYB/13b4Bs85S4AAAAAElFTkSuQmCC'
	}
	notLoadImage(string) {
		return this.images[string];
	}
	configLikeButton(obj) {
		this.likeButton = obj.like
		this.unlikeButton = obj.unlike
		this.followButton = obj.follow

		this.likeButton.addEventListener('click', () => {
			request('/api/ennNode/object-like', {
				type: 'like',
				channel: obj.channel
			}, true)
			.then((e) => {
				this.interfaceLike(e)
			})
			.catch((err) => {
				//this.sendError(err)
			})
		})

		this.unlikeButton.addEventListener('click', () => {
			request('/api/ennNode/object-like', {
				type: 'unlike',
				channel: obj.channel
			}, true)
			.then((e) => {
				// console.log(e)
				this.interfaceLike(e)
			})
			.catch((err) => {
				//this.sendError(err)
			})
		})

		this.followButton.addEventListener('click', () => {
			request('/api/ennNode/object-follow', {
				channel: obj.channel
			}, true)
			.then((e) => {
				if(e.follow == 'add') {
					this.followButton.classList.add('actif')
					this.followButton.querySelector('span.icon').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M698.77-600 600-698.77l41.77-42.77 57 57 141-142 42.77 42.77L698.77-600ZM220-150v-597.69Q220-778 241-799q21-21 51.31-21H520v60H292.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46V-242l200-86 200 86v-278h60v370L480-261.54 220-150Zm60-610h240-240Z"/></svg>`
				}
				if(e.follow == 'remove') {
					this.followButton.classList.remove('actif')
					this.followButton.querySelector('span.icon').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M220-150v-597.69Q220-778 241-799q21-21 51.31-21H520v60H292.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46V-242l200-86 200 86v-278h60v370L480-261.54 220-150Zm60-610h240-240Zm400 160v-80h-80v-60h80v-80h60v80h80v60h-80v80h-60Z"/></svg>`
				}
			})
			.catch((err) => {
				//this.sendError(err)
			})
		})
	}
	interfaceLike(e) {
		if(e.unlike == 'add') {
			this.unlikeButton.classList.add('actif')
			// this.unlikeButton.querySelector('span.text').textContent = parseInt(this.unlikeButton.querySelector('span.text').textContent)+1
			this.unlikeButton.querySelector('span.icon').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-840h400v520L360-40l-50-50q-7-7-11.5-19t-4.5-23v-14l44-174H120q-32 0-56-24t-24-56v-80q0-7 1.5-15t4.5-15l120-282q9-20 30-34t44-14Zm480 520v-520h160v520H720Z"/></svg>`
		}
		if(e.unlike == 'remove') {
			this.unlikeButton.classList.remove('actif')
			// this.unlikeButton.querySelector('span.text').textContent = parseInt(this.unlikeButton.querySelector('span.text').textContent)-1
			this.unlikeButton.querySelector('span.icon').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M250.77-803.84h420.77v479.99L406.15-60l-33.84-33.85q-6.23-6.23-10.35-16.5-4.11-10.27-4.11-19.34v-10.16l42.46-184h-268q-28.54 0-50.42-21.88Q60-367.62 60-396.15v-64.62q0-6.23 1.62-13.46 1.61-7.23 3.61-13.46l114.62-270.46q8.61-19.23 28.84-32.46t42.08-13.23Zm360.77 60H250.77q-4.23 0-8.65 2.3-4.43 2.31-6.74 7.7L120-463.84v67.69q0 5.38 3.46 8.84 3.46 3.47 8.85 3.47h343.84L426-164.61l185.54-184.77v-394.46Zm0 394.46v-394.46 394.46Zm60 25.53v-59.99H800v-360H671.54v-60H860v479.99H671.54Z"/></svg>`
		}

		if(e.like == 'add') {
			this.likeButton.classList.add('actif')
			// this.likeButton.querySelector('span.text').textContent = parseInt(this.likeButton.querySelector('span.text').textContent)+1
			this.likeButton.querySelector('span.icon').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M709.23-140H313.08v-480l265.38-263.84L612.3-850q6.24 6.23 10.35 16.5 4.12 10.27 4.12 19.35V-804L584.3-620h243.39q28.54 0 50.42 21.89Q900-576.23 900-547.69v64.61q0 6.23-1.31 13.46t-3.92 13.47L780.15-185.69q-8.61 19.23-28.84 32.46T709.23-140ZM253.08-620v480H100v-480h153.08Z"/></svg>`
		}
		if(e.like == 'remove') {
			this.likeButton.classList.remove('actif')
			// this.likeButton.querySelector('span.text').textContent = parseInt(this.likeButton.querySelector('span.text').textContent)-1
			this.likeButton.querySelector('span.icon').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M709.23-140H288.46v-480l265.39-263.84L587.69-850q6.23 6.23 10.35 16.5 4.11 10.27 4.11 19.35V-804l-42.46 184h268q28.54 0 50.42 21.89Q900-576.23 900-547.69v64.61q0 6.23-1.62 13.46-1.61 7.23-3.61 13.47L780.15-185.69q-8.61 19.23-28.84 32.46T709.23-140Zm-360.77-60h360.77q4.23 0 8.65-2.31 4.43-2.31 6.74-7.69L840-480v-67.69q0-5.39-3.46-8.85t-8.85-3.46H483.85L534-779.23 348.46-594.46V-200Zm0-394.46V-200v-394.46Zm-60-25.54v60H160v360h128.46v60H100v-480h188.46Z"/></svg>`
		}
	}

	sendSearch(txt) {
		document.location = "/search?query_search="+txt
	}
	
	loadSearchEvent(input, button) {
		let debounceTimer;

		const renderResults = (results) => {

			const list = input.parentNode.querySelector('.form-search-subjection ol');
			const fragment = document.createDocumentFragment();
			list.parentNode.style.display = 'none'

			results.slice(0, 6).forEach((item) => {
				const li = document.createElement('li');
				li.innerHTML = `
					<a href="/catalogue/${item.slug}">
						<span class="img">
							<img src="${cdnImageUrl}poster/${item.slug}.jpg" width="42" height="42" 
								 onerror="this.src = Core.notLoadImage('poster')">
						</span>
						<div class="info">
							<span class="title">${item.title}</span>
						</div>
					</a>`;
				fragment.appendChild(li);
			});

			list.innerHTML = '';

			list.parentNode.style.display = 'block'
			//if(results.length > 0) list.parentNode.style.display = 'block'
			if(results.length <= 0) {
				const li = document.createElement('li');
				li.innerHTML = `
					<a href="request">
						<span class="img">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M509.5-332.5Q522-345 522-362t-12.5-29.5Q497-404 480-404t-29.5 12.5Q438-379 438-362t12.5 29.5Q463-320 480-320t29.5-12.5ZM450-444h60q0-19 1.5-30t4.5-18q4-8 11.5-16.5T552-534q21-21 31.5-42t10.5-42q0-47-31-74.5T480-720q-41 0-72 23t-42 61l54 22q7-23 23-35.5t37-12.5q24 0 39 13t15 33q0 17-7.5 29.5T500-558q-17 14-27 25.5T458-510q-5 10-6.5 24.5T450-444Zm30 258q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/></svg>
						</span>
						<div class="info">
							<span class="text">Demander la serie</span>
						</div>
					</a>`;
				fragment.appendChild(li);
			}

			list.appendChild(fragment);
		};

		const fetchSuggestions = (query) => {
			if (!query.trim()) return;

			Omsy.request("/api/EigaNoNeko/v1/get_search", { query }, true)
				.then((res) => renderResults(res.data))
				.catch((err) => console.error('Search error:', err));
		};

		input.addEventListener('keydown', (event) => {
			if (event.key === "Enter") {
				clearTimeout(debounceTimer);
				return this.sendSearch(input.value);
			}

			if (input.value.trim()) {
				input.parentNode.querySelector('.form-search-subjection ol').innerHTML = '';
			}

			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(() => fetchSuggestions(input.value), 300);
		});

		input.addEventListener('blur', (event) => {
			if (window.matchMedia('(pointer: coarse)').matches) return;
			const subjection = input.parentNode.querySelector('.form-search-subjection');
			if (!subjection.contains(event.relatedTarget)) {
				subjection.style.display = 'none';
			}
		});
		input.addEventListener('focus', () => {
			input.parentNode.querySelector('.form-search-subjection').style.display = 'block'
		});

		button.addEventListener('click', () => this.sendSearch(input.value));
	}

	imageLoad(t, string) {
		if(!t.parentNode.classList.contains('err')) t.parentNode.classList.remove('load')
	}
	imageErr(t, string) {
		t.parentNode.classList.add('err')
		t.src = this.images[string]
	}

	substr(string, int = 255, end = "...") {
		if (string.length >= int) string = string.substring(0, int) + end;
		return string;
	}

	createCard(obj) {
		let html = ''
		html += `<li>
			<a href="${obj.url}" class="not-style serie-card" data-update="popup">
				<div class="min load">
					<img src="${obj.image}" alt="Image ${obj.title}" onerror="Core.imageErr(this, '${obj.imageType}')" onload="Core.imageLoad(this, '${obj.imageType}')">`
		if(obj.progress) html += `<span class="progress"><span style="width: ${obj.progress}%;"></span></span>`
		html += `</div>
				<div class="content">
					<span class="title">${obj.title}</span>
					<span class="sub_title">${obj.sub_title ?? ''}</span>
					<div>`
						if(obj.langues) html += `<span style="margin-top: 8px;">Langues</span><span class="sub_title" style="margin-top: 0px;">${obj.langues ?? ''}</span>`
						if(obj.description) html += `<div class="hover"><span style="margin-top: 8px;">Synopsis</span><span class="sub_title" style="margin-top: 0px;">${this.substr(obj.description ?? '', 120)}</span></div>`
					html += `</div>
				</div>
			</a>
		</li>`

		return html
	}
}