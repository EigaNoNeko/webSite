const FluttershySharedConst = {
    stopKeyEventBool: false
}

class Fluttershy {
	constructor(objectData) {
		this.playerContenaire = objectData.playerContenaire;
		this.nextPlaying = objectData.nextPlaying;

		this.icon.play = objectData.icon.play;
		this.icon.pause = objectData.icon.pause;

		this.player = this.playerContenaire.querySelector('#player');
		if(this.player.nodeName !== 'VIDEO') return

		this.progressBar = this.playerContenaire.querySelector('.progress-bar');
		this.currentTimeEl = this.progressBar.querySelector('.currenttime');
		this.buffTimeEl = this.progressBar.querySelector('.bufftime');

		//this.stringTimeContenaire = this.playerContenaire.querySelector('.item-string');
		this.stringTimeCurrent = this.playerContenaire.querySelector('#currentTime');
		this.stringTimeDuration = this.playerContenaire.querySelector('#durationTime');

		this.controlsButtoPlay = this.playerContenaire.querySelector('#toggle-play');
		this.controlsButtoFullscreen = this.playerContenaire.querySelector('#toggle-fullscreen');

		this.controlsProgressBarTimeIndication = this.playerContenaire.querySelector('#getProgressBarTime')

		// 🔧 Corrige le binding de contexte
		this.updateProgress = this.updateProgress.bind(this);
		this.updateBuffer = this.updateBuffer.bind(this);
		this.playPause = this.playPause.bind(this);

		this.load();
	}

	nextPlay() {
		if(this.nextPlayingStat) return
		if(!this.nextPlaying.url) return

		this.nextPlayingTime = setTimeout(() => {
			window.location = this.nextPlaying.url;
		}, 5000)
		this.playerContenaire.querySelector('.end-controls').insertAdjacentHTML('afterbegin', '<button id="nextPlayingButton">Episode suivant</button>')
		this.playerContenaire.querySelector('.end-controls').insertAdjacentHTML('afterbegin', '<button id="cancelPlayingButton">Regarder jusqu\'à la fin</button>')
		this.nextPlayingStat = true
		this.playerContenaire.querySelector('.end-controls button#cancelPlayingButton').addEventListener('click', () => {
			this.cancelNextPlay()
		})
		this.playerContenaire.querySelector('.end-controls button#nextPlayingButton').addEventListener('click', () => {
			window.location = this.nextPlaying.url;
		})
	}

	cancelNextPlay() {
		if(!this.playerContenaire.querySelector('.end-controls button')) return;
		
		this.playerContenaire.querySelectorAll('.end-controls button').forEach((e) => {
			e.remove()
		})

		clearTimeout(this.nextPlayingTime)
		this.nextPlayingTime = null

		console.log('cancel')
	}

	removeNextPlay() {
		if(!this.playerContenaire.querySelector('.end-controls button')) return;
		
		this.playerContenaire.querySelectorAll('.end-controls button').forEach((e) => {
			e.remove()
		})
		this.nextPlayingStat = false
	}
	
	updateProgress() {
		const percent = (this.player.currentTime / this.player.duration) * 100;
		this.currentTimeEl.style.width = `${percent}%`;

		//console.log((this.player.duration - this.player.currentTime))
		//if((this.player.duration - this.player.currentTime) < 5) this.nextPlay()
		//if((this.player.duration - this.player.currentTime) > 5) this.removeNextPlay()

		if(this.player.duration > 240) {
			if((this.player.duration - this.player.currentTime) < 60) this.nextPlay()
			if((this.player.duration - this.player.currentTime) > 60) this.removeNextPlay()
		} else {
			if((this.player.duration - this.player.currentTime) < 5) this.nextPlay()
			if((this.player.duration - this.player.currentTime) > 5) this.removeNextPlay()
		}

		this.stringTimeCurrent.textContent = this.formatTime(this.player.currentTime)
		//this.stringTimeDuration.textContent = this.formatTime(this.player.duration)
		this.stringTimeDuration.textContent = this.formatTime((this.player.duration - this.player.currentTime))
	}
	updateBuffer() {
		if (this.player.buffered.length) {
			const bufferedEnd = this.player.buffered.end(this.player.buffered.length - 1);
			const percent = (bufferedEnd / this.player.duration) * 100;
			this.buffTimeEl.style.width = `${percent}%`;
		}
	}

	formatTime(seconds) {
		seconds = Math.floor(seconds);
		const h = Math.floor(seconds / 3600);
		const m = Math.floor((seconds % 3600) / 60);
		const s = seconds % 60;

		const pad = (n) => n.toString().padStart(2, '0');

		if (h > 0) {
			return `${pad(h)}:${pad(m)}:${pad(s)}`; // Format hh:mm:ss
		} else {
			return `${pad(m)}:${pad(s)}`; // Format mm:ss
		}
	}

	load() {
		console.log(this.player)
		if(this.player.nodeName == 'VIDEO') {
			this.loadEvent()
		}
	}

	loadEvent() {
		document.addEventListener('keydown', (event) => {
			if(FluttershySharedConst.stopKeyEventBool) return

			const active = document.activeElement;
			if (active && active.tagName === "BUTTON") return;

			console.log(FluttershySharedConst.stopKeyEventBool)

			if(event.keyCode == 39) this.player.currentTime+=5
			if(event.keyCode == 37) this.player.currentTime-=5
			if(event.keyCode == 32 || event.keyCode == 75) {
				event.preventDefault()
				this.playPause()
			}

			if(event.key == 'Enter') {
				event.preventDefault()
				this.playPause()
			}
		})

		this.playerContenaire.addEventListener('click', (e) => {
			console.log(e.target.nodeName)
			if(
				e.target.nodeName == 'BUTTON'
				//||
				//e.target.nodeName == 'DIV'
			) return
			this.playPause()
		})

		this.player.addEventListener('timeupdate', this.updateProgress);
		this.player.addEventListener('progress', this.updateBuffer);

		// Gérer le clic sur la barre de progression
		this.progressBar.addEventListener('click', (e) => {
			const rect = this.progressBar.getBoundingClientRect();
			const clickX = e.clientX - rect.left;
			const percent = clickX / rect.width;
			this.player.currentTime = percent * this.player.duration;
		});

		this.progressBar.addEventListener('mouseleave', (e) => { this.controlsProgressBarTimeIndication.style.display = 'none' })

		this.progressBar.addEventListener('mousemove', (e) => {
			const rect = this.progressBar.getBoundingClientRect();
			const clickX = e.clientX - rect.left;
			const percent = clickX / rect.width;
			//this.player.currentTime = percent * this.player.duration;
			const durantionMove = percent * this.player.duration
			console.log(this.formatTime(durantionMove), clickX)

			this.controlsProgressBarTimeIndication.textContent = this.formatTime(durantionMove)
			this.controlsProgressBarTimeIndication.style.left = clickX+'px'
			this.controlsProgressBarTimeIndication.style.display = 'block'
		});
		
		/* this.controlsButtoFullscreen.addEventListener('click', ( ) => {
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
		}) */

		this.player.addEventListener('pause', () => {
			this.controlsButtoPlay.innerHTML = this.icon.play

			if(!this.playerContenaire.querySelector('.end-controls button')) return;
			clearTimeout(this.nextPlayingTime)
			this.nextPlayingTime = null

			console.log('cancel')
		})

		this.player.addEventListener('play', () => {
			this.controlsButtoPlay.innerHTML = this.icon.pause

			this.playerContenaire.classList.remove('loading')
			this.playerContenaire.querySelector('img#player-thumbnail').style.display = 'none'
		})

		this.player.addEventListener('canplay', () => {
			this.playerContenaire.classList.remove('loading')
			this.playerContenaire.querySelector('img#player-thumbnail').style.display = 'none'
		})
	}

	playPause() {
		if(this.player.paused) {
			this.player.play()
			
		 } else {
		 	this.player.pause()
		 }
	}

	icon = {}
	stopKeyEventBool = false
}