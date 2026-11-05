class Fluttershy {
    constructor(obj) {
        this.playerContenaire = obj.playerContenaire
        this.cssLink = obj.cssUrl ?? "/share/lib/Fluttershy/tmp_butterfly_css/main.css"

        document.querySelector('head').innerHTML += `<link rel="stylesheet" type="text/css" href="${this.cssLink}">`

        this.source = obj.source
        this.info = obj.info

        this.loadNativePlayer()
    }

    loadNativePlayer() {
        let htmlPlayerSmart = `<div class="player-element">`
            htmlPlayerSmart += `<video id="player" src="${this.source.video}" width="100%" height="100%"></video>`
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

        new Butterfly({
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
        if(Omsy) {
        	Omsy.event.event.resize['GoodMorningEnnPlayer'] = () => {
				this.updatePlayerSizeNative()
			}

			Omsy.event.event.load['GoodMorningEnnPlayer'] = () => {
				this.updatePlayerSizeNative()
			}
        }
    }

    updatePlayerSizeNative() {
		const parentWidth = this.playerContenaire.parentNode.offsetWidth;
		const ratio = 1080 / 1920;
		console.log(parentWidth, (parentWidth * ratio) + 'px')
		this.playerContenaire.parentNode.style.height = (parentWidth * ratio) + 'px';
		return;
	}
}