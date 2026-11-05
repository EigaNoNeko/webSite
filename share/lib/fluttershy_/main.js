class Butterfly {
    constructor(obj) {
        this.playerContenaire = obj.playerContenaire

        this.source = obj.source
        this.info = obj.info

        this.loadNativePlayer()
    }

    loadNativePlayer() {
        let htmlPlayerSmart = `<div class="player-element">`
            htmlPlayerSmart += `<video id="player" autoplay src="${this.source.video}"></video>`
            htmlPlayerSmart += `<img id="player-thumbnail" src="${this.source.image}">`
                htmlPlayerSmart += `<div class="player-controls-smart">`
                    htmlPlayerSmart += `<div class="end-controls"></div>`
                    htmlPlayerSmart += `<div class="down-info">`
                        htmlPlayerSmart += `<div class="progress-timebar">`
                            htmlPlayerSmart += `<div class="time-and-btn">`
                                htmlPlayerSmart += `<div class="item-string">`
                                    htmlPlayerSmart += `<span id="currentTime">00:00</span>`
                                htmlPlayerSmart += `</div>`
                                //htmlPlayerSmart += `<button id="toggle-fullscreen"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M111.87-111.87v-331h91v176.11l490.37-490.37H517.13v-91h331v331h-91v-176.11L266.76-202.87h176.11v91h-331Z"/></svg></button>`
                            htmlPlayerSmart += `</div>`
                            htmlPlayerSmart += `<div class="player-controls-bar">`
                                htmlPlayerSmart += `<span id="getProgressBarTime"></span>`
                                htmlPlayerSmart += `<div class="progress-bar">` // tabindex="-1"
                                    htmlPlayerSmart += `<span class="bufftime"></span>`
                                    htmlPlayerSmart += `<span class="currenttime"></span>`
                                htmlPlayerSmart += `</div>`
                            htmlPlayerSmart += `</div>`
                            htmlPlayerSmart += `<div class="time-and-btn">`
                                htmlPlayerSmart += `<div class="item-string">`
                                    htmlPlayerSmart += `<span id="durationTime">00:00</span>`
                                htmlPlayerSmart += `</div>`
                                //htmlPlayerSmart += `<button id="toggle-fullscreen"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M111.87-111.87v-331h91v176.11l490.37-490.37H517.13v-91h331v331h-91v-176.11L266.76-202.87h176.11v91h-331Z"/></svg></button>`
                            htmlPlayerSmart += `</div>`
                        htmlPlayerSmart += `</div>`
                        htmlPlayerSmart += `<div class="control-time">`
                            htmlPlayerSmart += `<div class="left-controls">`
                                htmlPlayerSmart += `<ul>`
                                    htmlPlayerSmart += `</li>`
                                        htmlPlayerSmart += `<button id="toggle-play"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M311.87-185.41v-589.18L775.07-480l-463.2 294.59Z"/></svg></button>`    
                                    htmlPlayerSmart += `</li>`
                                    htmlPlayerSmart += `<li>`
                                        htmlPlayerSmart += `<button>`
                                            htmlPlayerSmart += `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-80q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-440h80q0 117 81.5 198.5T480-160q117 0 198.5-81.5T760-440q0-117-81.5-198.5T480-720h-6l62 62-56 58-160-160 160-160 56 58-62 62h6q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-440q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-80Z"/></svg>`
                                        htmlPlayerSmart += `</button>`
                                    htmlPlayerSmart += `</li>`
                                htmlPlayerSmart += `</ul>`
                            htmlPlayerSmart += `</div>`
                            htmlPlayerSmart += `<div class="right-controls">`
                                htmlPlayerSmart += `<ul>`
                                    htmlPlayerSmart += `<li>`
                                        htmlPlayerSmart += `<button onclick="Player.hideSettings()">`
                                            htmlPlayerSmart += `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Z"/></svg>`
                                        htmlPlayerSmart += `</button>`
                                    htmlPlayerSmart += `</li>`
                                     htmlPlayerSmart += `<li>`
                                        htmlPlayerSmart += `<button id="toggle-fullscreen">`
                                            htmlPlayerSmart += `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M111.87-111.87v-331h91v176.11l490.37-490.37H517.13v-91h331v331h-91v-176.11L266.76-202.87h176.11v91h-331Z"/></svg>`
                                        htmlPlayerSmart += `</button>`
                                    htmlPlayerSmart += `</li>`
                                htmlPlayerSmart += `</ul>`
                            htmlPlayerSmart += `</div>`
                        htmlPlayerSmart += `</div>`
                    htmlPlayerSmart += `</div>`
                htmlPlayerSmart += `</div>`
            htmlPlayerSmart += `</div>`
        htmlPlayerSmart += `</div>`


        this.playerContenaire.insertAdjacentHTML('afterbegin', htmlPlayerSmart)

        this.Fluttershy = {}; new Fluttershy({
            playerContenaire: this.playerContenaire.querySelector('.player-element'),
            nextPlaying: {
                url: this.info.nextUrl ?? null
            },
            icon: {
                pause: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M560-200v-560h160v560H560Zm-320 0v-560h160v560H240Z"/></svg>`,
                play: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M320-200v-560l440 280-440 280Z"/></svg>`,
                enterFullscreen: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M140-140v-300h60v198.23L718.23-760H520v-60h300v300h-60v-198.23L241.77-200H440v60H140Z"/></svg>`,
                leaveFullscreen: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M141.77-100 100-141.77 378.23-420H180v-60h300v300h-60v-198.23L141.77-100ZM480-480v-300h60v198.23L818.23-860 860-818.23 581.77-540H780v60H480Z"/></svg>`
            }
        })
    }
}
const FluttershySharedConst = {
    debug: true,
    stopKeyEventBool: false
}

class Fluttershy {
    constructor(object) {
        this.object = object

        this.playerContenaire = this.object.playerContenaire

        if(this.object.icon.play) this.icon.play = this.object.icon.play
        if(this.object.icon.pause) this.icon.pause = this.object.icon.pause

        this.player = this.playerContenaire.querySelector('#player')
        if(!this.player) {
            if(FluttershySharedConst.debug) console.log('[VIDEO_ELEMENT] Not exist')
            return
        }

        if(this.player.nodeName !== 'VIDEO') {
            if(FluttershySharedConst.debug) console.log('[VIDEO_ELEMENT] ERROR')
            return
        }
        if(FluttershySharedConst.debug) console.log('[VIDEO_ELEMENT] OK')



        // BIND THIS -> EVENT FUNCTION
        this.videoContenaireKeydown = this.videoContenaireKeydown.bind(this)
        this.videoElementTimeUpdate = this.videoElementTimeUpdate.bind(this)
        this.videoElementDurationUpdate = this.videoElementDurationUpdate.bind(this)
        this.videoElementBufferUpdate = this.videoElementBufferUpdate.bind(this)

        this.videoElementPlay = this.videoElementPlay.bind(this)
        this.videoElementPause = this.videoElementPause.bind(this)

        this.togglePlay = this.togglePlay.bind(this)
        this.videoContenaireClick = this.videoContenaireClick.bind(this)

        this.updatePlayerSize = this.updatePlayerSize.bind(this)

        this.videoContenaireToggleFullscreen = this.videoContenaireToggleFullscreen.bind(this)

        //

        this.loadElement()
        this.addEvent()
        //this.removeEvent()

        this.updatePlayerSize()
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

        this.progressBar = this.playerContenaire.querySelector('.progress-bar')
        this.currentTimeEl = this.progressBar.querySelector('.currenttime')
        this.buffTimeEl = this.progressBar.querySelector('.bufftime')

        if(this.controlsButtoPlay) {
            if(this.player.paused)
                this.controlsButtoPlay.innerHTML = this.icon.play
            else
                this.controlsButtoPlay.innerHTML = this.icon.pause
        }
    }

    addEvent() {
        document.addEventListener('keydown', this.videoContenaireKeydown)
        window.addEventListener('resize', this.updatePlayerSize)

        this.player.addEventListener('timeupdate', this.videoElementTimeUpdate)
        this.player.addEventListener('durationchange', this.videoElementDurationUpdate)

        this.player.addEventListener('play', this.videoElementPlay)
        this.player.addEventListener('pause', this.videoElementPause)
        this.player.addEventListener('progress', this.videoElementBufferUpdate)

        this.playerContenaire.addEventListener('click', this.videoContenaireClick)

        if(this.controlsButtoPlay) this.controlsButtoPlay.addEventListener('click', this.togglePlay)

        if(this.controlsButtoFullscreen) this.controlsButtoFullscreen.addEventListener('click', this.videoContenaireToggleFullscreen)
    }

    removeEvent() {
        document.removeEventListener('keydown', this.videoContenaireKeydown)

        this.player.removeEventListener('timeupdate', this.videoElementTimeUpdate)
        this.player.removeEventListener('durationchange', this.videoElementDurationUpdate)

        this.player.removeEventListener('play', this.videoElementPlay)
        this.player.removeEventListener('pause', this.videoElementPause)
        this.player.removeEventListener('progress', this.videoElementBufferUpdate)

        this.playerContenaire.removeEventListener('click', this.videoContenaireClick)

        if(this.controlsButtoPlay) this.controlsButtoPlay.removeEventListener('click', this.togglePlay)
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

        //if(FluttershySharedConst.debug) console.log(playerHeight,'>', contenaireHeight, playerWidth,'>', contenaireWidth)
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

        if(FluttershySharedConst.debug) console.log('[VIDEO_ELEMNT_VOLUME] update to '+this.player.volume)
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
        if(FluttershySharedConst.debug) console.log('[VIDEO_ELEMENT] TimeDuration Update to '+this.player.duration)
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
        if(FluttershySharedConst.stopKeyEventBool) return

        const active = document.activeElement
        if (active && active.tagName === "BUTTON") return;

        if(this.keydownCallAction[event.keyCode]) this.keydownCallAction[event.keyCode]()

        if(FluttershySharedConst.debug) console.log('[EVENT_KEYDOWN]['+event.keyCode+'] for call', this.keydownCallAction[event.keyCode])
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