const ButterflySharedConst = {
	debug: true,
	stopKeyEventBool: false
}

class Butterfly {
	constructor(object) {
		this.object = object

		this.playerContenaire = this.object.playerContenaire

		if(this.object.icon.play) this.icon.play = this.object.icon.play
		if(this.object.icon.pause) this.icon.pause = this.object.icon.pause

		this.player = this.playerContenaire.querySelector('#player')
		if(!this.player) {
			if(ButterflySharedConst.debug) console.log('[VIDEO_ELEMENT] Not exist')
			return
		}

		if(this.player.nodeName !== 'VIDEO') {
			if(ButterflySharedConst.debug) console.log('[VIDEO_ELEMENT] ERROR')
			return
		}
		if(ButterflySharedConst.debug) console.log('[VIDEO_ELEMENT] OK')



		// BIND THIS -> EVENT FUNCTION
		this.videoContenaireKeydown = this.videoContenaireKeydown.bind(this)
		this.videoElementTimeUpdate = this.videoElementTimeUpdate.bind(this)
		this.videoElementDurationUpdate = this.videoElementDurationUpdate.bind(this)
		this.videoElementBufferUpdate = this.videoElementBufferUpdate.bind(this)
		this.videoControlsBar = this.videoControlsBar.bind(this)

		this.videoElementPlay = this.videoElementPlay.bind(this)
		this.videoElementPause = this.videoElementPause.bind(this)

		this.togglePlay = this.togglePlay.bind(this)
		this.videoContenaireClick = this.videoContenaireClick.bind(this)

		//this.updatePlayerSize = this.updatePlayerSize.bind(this)

		this.videoContenaireToggleFullscreen = this.videoContenaireToggleFullscreen.bind(this)

		//

		this.loadElement()
		this.addEvent()
		//this.removeEvent()

		//this.updatePlayerSize()
	}

	togglePlay() {
		if(this.player.paused) {
			this.player.play()
		} else {
			this.player.pause()
		}
	}

	reloadEvent() {
		this.removeEvent()
		this.addEvent()
	}

	loadElement() {
		if(this.playerContenaire.querySelector('#currentTime')) this.stringTimeCurrent = this.playerContenaire.querySelector('#currentTime')
		if(this.playerContenaire.querySelector('#durationTime'))  this.stringTimeDuration = this.playerContenaire.querySelector('#durationTime')

		if(this.playerContenaire.querySelector('#toggle-play')) this.controlsButtoPlay = this.playerContenaire.querySelector('#toggle-play')
		if(this.playerContenaire.querySelector('#toggle-fullscreen')) this.controlsButtoFullscreen = this.playerContenaire.querySelector('#toggle-fullscreen')
		if(this.playerContenaire.querySelector('.progress-bar')) {
			this.progressBar = this.playerContenaire.querySelector('.progress-bar')
			this.currentTimeEl = this.progressBar.querySelector('.currenttime')
			this.buffTimeEl = this.progressBar.querySelector('.bufftime')
		}

		if(this.controlsButtoPlay) {
			if(this.player.paused)
				this.controlsButtoPlay.innerHTML = this.icon.play
			else
				this.controlsButtoPlay.innerHTML = this.icon.pause
		}
	}

	addEvent() {
		this.playerContenaire.addEventListener('keydown', this.videoContenaireKeydown)
		
		//window.addEventListener('resize', this.updatePlayerSize)

		if(this.progressBar) this.player.addEventListener('timeupdate', this.videoElementTimeUpdate)
		if(this.progressBar) this.player.addEventListener('durationchange', this.videoElementDurationUpdate)

		this.player.addEventListener('play', this.videoElementPlay)
		this.player.addEventListener('pause', this.videoElementPause)
		if(this.progressBar) this.player.addEventListener('progress', this.videoElementBufferUpdate)

		this.playerContenaire.addEventListener('click', this.videoContenaireClick)

		if(this.controlsButtoPlay) this.controlsButtoPlay.addEventListener('click', this.togglePlay)

		if(this.controlsButtoFullscreen) this.controlsButtoFullscreen.addEventListener('click', this.videoContenaireToggleFullscreen)

		this.progressBar.addEventListener('click', this.videoControlsBar);
	}

	removeEvent() {
		document.removeEventListener('keydown', this.videoContenaireKeydown)

		if(this.progressBar) this.player.removeEventListener('timeupdate', this.videoElementTimeUpdate)
		if(this.progressBar) this.player.removeEventListener('durationchange', this.videoElementDurationUpdate)

		this.player.removeEventListener('play', this.videoElementPlay)
		this.player.removeEventListener('pause', this.videoElementPause)
		if(this.progressBar) this.player.removeEventListener('progress', this.videoElementBufferUpdate)

		this.playerContenaire.removeEventListener('click', this.videoContenaireClick)

		if(this.controlsButtoPlay) this.controlsButtoPlay.removeEventListener('click', this.togglePlay)

		this.progressBar.removeEventListener('click', this.videoControlsBar);
	}

	formatTime(seconds) {
		seconds = Math.floor(seconds)
		const h = Math.floor(seconds / 3600)
		const m = Math.floor((seconds % 3600) / 60)
		const s = seconds % 60

		const pad = (n) => n.toString().padStart(2, '0')

		if (h > 0) return `${pad(h)}:${pad(m)}:${pad(s)}`
		else return `${pad(m)}:${pad(s)}`
	}

	updatePlayerSize() {
		const player = this.playerContenaire.querySelector('#player');

		let playerHeight = player.offsetHeight;
		let playerWidth = player.offsetWidth;

		let contenaireHeight = this.playerContenaire.parentNode.offsetHeight;
		let contenaireWidth = this.playerContenaire.parentNode.offsetWidth;

		if((playerHeight > contenaireHeight)) {
			player.style.width = 'auto';
			player.style.height = '100%';
		}

		if((playerWidth > contenaireWidth)) {
			player.style.width = '100%';
			player.style.height = 'auto';
		}

		//if(ButterflySharedConst.debug) console.log(playerHeight,'>', contenaireHeight, playerWidth,'>', contenaireWidth)
	}

	updateCurrentTime(int, b = true) {
		if(b) this.player.currentTime+=int
		else this.player.currentTime = int
	}

	updateVolume(int) {
		let buffVol = this.player.volume
		buffVol+= int

		if((buffVol > 1.0)) return
		if((buffVol < 0.0)) return

		this.player.volume=buffVol

		if(ButterflySharedConst.debug) console.log('[VIDEO_ELEMNT_VOLUME] update to '+this.player.volume)
	}

	/* EVENT LIST */
	videoElementPlay() {
		this.playerContenaire.classList.add('playing')
		this.playerContenaire.classList.remove('loading')
		this.playerContenaire.classList.remove('paused')
		if(this.controlsButtoPlay) this.controlsButtoPlay.innerHTML = this.icon.pause
	}
	videoElementPause() {
		this.playerContenaire.classList.remove('playing')
		this.playerContenaire.classList.add('paused')
		if(this.controlsButtoPlay) this.controlsButtoPlay.innerHTML = this.icon.play
	}
	videoElementTimeUpdate() {
		if(this.stringTimeCurrent) this.stringTimeCurrent.textContent = this.formatTime(this.player.currentTime)
		if(this.stringTimeDuration) this.stringTimeDuration.textContent = this.formatTime(this.player.duration - this.player.currentTime)

		const percent = (this.player.currentTime / this.player.duration) * 100;
		this.currentTimeEl.style.width = `${percent}%`;
	}

	videoElementDurationUpdate() {
		if(ButterflySharedConst.debug) console.log('[VIDEO_ELEMENT] TimeDuration Update to '+this.player.duration)
	}

	videoElementBufferUpdate() {
		if (this.player.buffered.length) {
			const bufferedEnd = this.player.buffered.end(this.player.buffered.length - 1);
			const percent = (bufferedEnd / this.player.duration) * 100;
			this.buffTimeEl.style.width = `${percent}%`;
		}
	}

	videoContenaireClick(event) {
		if(event.target.nodeName === 'BUTTON') return
		this.togglePlay()
	}

	videoContenaireKeydown(event) {
		if(ButterflySharedConst.stopKeyEventBool) return

		const active = document.activeElement
		if (active && active.tagName === "BUTTON") return;

		if(this.keydownCallAction[event.keyCode]) this.keydownCallAction[event.keyCode]()

		if(ButterflySharedConst.debug) console.log('[EVENT_KEYDOWN]['+event.keyCode+'] for call', this.keydownCallAction[event.keyCode])
	}

	videoContenaireToggleFullscreen() {
		if (
			!document.fullscreenElement && !document.webkitFullscreenElement
		) {  // current working methods
			if (this.playerContenaire.requestFullscreen) {
				this.playerContenaire.requestFullscreen();
			} else if (this.playerContenaire.mozRequestFullScreen) {
				this.playerContenaire.mozRequestFullScreen();
			} else if (this.playerContenaire.webkitRequestFullscreen) {
				this.playerContenaire.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
			}
			document.querySelector('body').classList.add('open-fullscreen')
		} else {
			if (document.cancelFullScreen) {
				document.cancelFullScreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			}
			document.querySelector('body').classList.remove('open-fullscreen')
		}
	}

	videoControlsBar(e) {
		const rect = this.progressBar.getBoundingClientRect();
		const clickX = e.clientX - rect.left;
		const percent = clickX / rect.width;
		this.player.currentTime = percent * this.player.duration;
	}

	icon = {}

	keydownCallAction = {
		39: this.updateCurrentTime.bind(this, +5), // ArrowRight
		37: this.updateCurrentTime.bind(this, -5), // ArrowLeft

		38: this.updateVolume.bind(this, +0.05), // ArrowTop
		40: this.updateVolume.bind(this, -0.05), // ArrowBottom

		32: this.togglePlay.bind(this), // Space
		75: this.togglePlay.bind(this), // K

		70: this.videoContenaireToggleFullscreen.bind(this), // F
	}
}