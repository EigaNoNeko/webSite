class Fluttershy {
    currentQuality = '480p'
    currentAudio = 'VF'
    icons = {
        play: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M320-200v-560l440 280-440 280Z"/></svg>`,
        pause: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-200v-560h160v560H560Zm-320 0v-560h160v560H240Z"/></svg>`,

        fullscreen_open: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M120-120v-320h80v184l504-504H520v-80h320v320h-80v-184L256-200h184v80H120Z"/></svg>`,
        fullscreen_close: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m136-80-56-56 264-264H160v-80h320v320h-80v-184L136-80Zm344-400v-320h80v184l264-264 56 56-264 264h184v80H480Z"/></svg>`,

        volume_off: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M792-56 671-177q-25 16-53 27.5T560-131v-82q14-5 27.5-10t25.5-12L480-368v208L280-360H120v-240h128L56-792l56-56 736 736-56 56Zm-8-232-58-58q17-31 25.5-65t8.5-70q0-94-55-168T560-749v-82q124 28 202 125.5T840-481q0 53-14.5 102T784-288ZM650-422l-90-90v-130q47 22 73.5 66t26.5 96q0 15-2.5 29.5T650-422ZM480-592 376-696l104-104v208Zm-80 238v-94l-72-72H200v80h114l86 86Zm-36-130Z"/></svg>`,
        volume_up: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-131v-82q90-26 145-100t55-168q0-94-55-168T560-749v-82q124 28 202 125.5T840-481q0 127-78 224.5T560-131ZM120-360v-240h160l200-200v640L280-360H120Zm440 40v-322q47 22 73.5 66t26.5 96q0 51-26.5 94.5T560-320ZM400-606l-86 86H200v80h114l86 86v-252ZM300-480Z"/></svg>`,
        volume_down: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-360v-240h160l200-200v640L360-360H200Zm440 40v-322q45 21 72.5 65t27.5 97q0 53-27.5 96T640-320ZM480-606l-86 86H280v80h114l86 86v-252ZM380-480Z"/></svg>`,
        volume_mute: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M280-360v-240h160l200-200v640L440-360H280Zm80-80h114l86 86v-252l-86 86H360v80Zm100-40Z"/></svg>`,

        settings: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>`
    }

    playerContenaire = null
    playerControls = null

    videoElement = null
    videoButtonPlay = null
    videoButtonFullscreen = null
    videoButtonVolume = null
    videoButtonSettings = null

    videoTimestampCurrent = null
    videoTimestampDuration = null

    videoProgressbar = null

    obj = {}

    constructor(container, obj) {
        this.obj = obj
        this.playerContenaire = container

        this.html()

        this.playerControls = this.playerContenaire.querySelector('.enn-controls-player')

        this.videoElement = this.playerContenaire.querySelector('#enn-video')
        this.videoButtonPlay = this.playerContenaire.querySelector('#enn-video-button-play')
        this.videoButtonFullscreen = this.playerContenaire.querySelector('#enn-video-button-fullscreen')
        this.videoButtonVolume = this.playerContenaire.querySelector('#enn-video-button-volume')
        this.videoButtonSettings = this.playerContenaire.querySelector('#enn-video-button-settings')

        this.videoTimestampCurrent = this.playerContenaire.querySelector('#enn-video-timestamp-current')
        this.videoTimestampDuration = this.playerContenaire.querySelector('#enn-video-timestamp-duration')

        this.videoProgressbar = this.playerContenaire.querySelector('#enn-video-progressbar')


        if(this.obj.currentQuality) this.currentQuality = this.obj.currentQuality
        if(this.obj.currentAudio) this.currentAudio = this.obj.currentAudio

        this.loadEvent()
    }

    html() {
        let nextVideo = ''
        if(this.obj.nextVideo) nextVideo = `<button class="itm enn-controls-btn" id="enn-video-button-nextvideo"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M664.07-224.93v-510.14h91v510.14h-91Zm-459.14 0v-510.14L587.65-480 204.93-224.93Z"/></svg></button>`
        
        let backVideo = ''
        if(this.obj.backVideo) backVideo = `<button class="itm enn-controls-btn" id="enn-video-button-backvideo"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M204.93-224.93v-510.14h91v510.14h-91Zm550.14 0L372.35-480l382.72-255.07v510.14Z"/></svg></button>`
        
        const cssClass = this.playerContenaire.classList[0];

        this.playerContenaire.innerHTML = `<style>
        * {
            margin: 0;
            padding: 0;

            box-sizing: box-sizing;

            font-size: 14px;
            line-height: 1.4em;
        }
        video,
        svg {
            display: block;

            color: inherit;
            fill: inherit;
        }
        button {
            border: none;
            background-color: transparent;
        }
        .${cssClass} {
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            background-color: #000;
            color: #fff;
            fill: #fff;

            overflow: hidden;
        }
        .${cssClass} video {
            width: 100%;
            height: 100%;
        }
        .${cssClass} .enn-controls-player {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;

            background-color: blue;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.5) 0%, transparent 100%);

            padding: 12px;
            apadding-top: 128px;
            opacity: 0.0;
        }

        .${cssClass}.show-control .enn-controls-player { opacity: 1.0; }

        .${cssClass} .enn-controls-player-bar #enn-video-progressbar {
            height: 4px;
            background-color: rgba(0, 0, 0, .3);
            border-radius: 4px;

            overflow: hidden;

            position: relative;

            cursor: pointer;
        }

        .${cssClass} .enn-controls-player-bar #enn-video-progressbar:hover {
            height: 6px;
            top: 1px;
        }

        .${cssClass} .enn-controls-player-bar #enn-video-progressbar span {
            height: 100%;

            display: block;

            pointer-events: none;
        }

        .${cssClass} .enn-controls-player-bar #enn-video-progressbar span.color {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;

            background-color: #21BCFF;
        }

        .${cssClass} .enn-controls-player-bar #enn-video-progressbar span.color2 {
            background-color: rgba(150, 150, 150, .5);
        }

        .${cssClass} .enn-controls-player-button {
            margin-top: 8px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }


        .${cssClass} .enn-controls-btn {
            background-color: rgba(0, 0, 0, .3);
            abackdrop-filter: blur(12px);

            padding: 8px;
            border-radius: 4px;
        }
        .${cssClass} .enn-controls-btn:hover {
            background-color: rgba(0, 0, 0, .8);
        }
        .${cssClass} .enn-controls-btn svg {
            height: 22px;
            width: 22px;
        }
        .${cssClass} .enn-controls-player-button button { cursor: pointer; }

        .${cssClass} .enn-controls-player-button .enn-video-timestamp { padding: 8px 12px; }

        .${cssClass} .enn-controls-player-button .parent {
            display: flex;
            align-items: center;
        }

        .${cssClass} .enn-controls-player-button .parent .itm:first-child { margin-left: 0; }
        .${cssClass} .enn-controls-player-button .parent .itm { margin-left: 8px; }




        .${cssClass} .enn-controls-player-button .enn-button-volume {
            display: flex;
            align-items: center;
        }

        .${cssClass} .enn-controls-player-button .enn-button-volume button {
            padding: 0;
        }

        .${cssClass} .enn-controls-player-button .enn-button-volume .enn-video-volume-bar {
            height: 12px;
            width: 0px;
            position: relative;

            cursor: pointer;

            margin-left: 0px;

            display: flex;
            align-items: center;
            transition: .2s;
        }
        .${cssClass} .enn-controls-player-button .enn-button-volume:hover .enn-video-volume-bar { margin-left: 8px; width: 80px; transition: .2s; }
        .${cssClass} .enn-controls-player-button .enn-button-volume:hover { padding-right: 16px; }
        .${cssClass} .enn-controls-player-button .enn-button-volume .enn-video-volume-bar::after {
            content: "";
            height: 4px;
            width: 100%;
            background-color: rgba(150, 150, 150, .5);

            position: absolute;
            top: 50%;

            transform: translateY(-50%);

            cursor: pointer;

            display: block;
        }

        .${cssClass} .enn-controls-player-button .enn-button-volume .enn-video-volume-bar span.color {
            z-index: 1;
            position: relative;

            height: 4px;
            width: 50%;

            display: block;

            pointer-events: none;

            background-color: #21BCFF;
        }

        .${cssClass} .enn-option-player {
            position: absolute;
            bottom: 64px;
            right: -100%;

            margin-bottom: 16px;
            transition: 0.5s; 
        }
        .${cssClass} .enn-option-player.open { transition: 0.5s; right: 16px; }

        .${cssClass} .enn-option-player .enn-settings-player {
            background-color: rgba(0, 0, 0, .8);
            border-radius: 4px;

            max-height: 400px;
            width: 250px;
        }

        .${cssClass} .enn-option-player .enn-settings-player .head {
            display: flex;
            align-items: center;

            padding: 16px;
        }

        .${cssClass} .enn-option-player .enn-settings-player div.content {
            padding: 8px;
        }

        .${cssClass} .enn-option-player .enn-settings-player ul { list-style: none; }
        .${cssClass} .enn-option-player .enn-settings-player .content a {
            color: inherit;
            fill: inherit;

            text-decoration: none;

            padding: 8px;
            border-radius: 4px;

            display: flex;
            align-items: center;
        }
        .${cssClass} .enn-option-player .enn-settings-player .content a svg {
            opacity: 0.0;
        }

        .${cssClass} .enn-option-player .enn-settings-player .content a.focus svg {
            opacity: 1.0;
        }

        .${cssClass} .enn-option-player .enn-settings-player .content a:hover {
            background-color: rgba(60, 60, 60, .4);
        }

        .${cssClass} .enn-option-player .enn-settings-player .content a .content {
            margin-left: 8px;
        }
        </style>
        <video
        id="enn-video"
        src="${this.obj.src}"
        poster="${this.obj.poster}"
        ></video>
        <div class="enn-controls-player">
            <div class="enn-option-player">
                <div class="enn-settings-player">
                    <div class="head">
                        <!-- <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M407.65-72.35 0-480l407.65-407.65L486.3-809l-329 329 329 329-78.65 78.65Z"/></svg>
                        </a> -->
                        <div class="name">
                            <span class="name">Qualité vidéo</span>
                        </div>
                    </div>
                    <div class="content">
                        <ul>
                            <li>
                                <a class="updateQuality"  data-var="720p">
                                    <span class="checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-232.35 146.35-468l64.89-64.89L382-362.13l366.76-366.76L813.65-664 382-232.35Z"/></svg>
                                    </span>
                                    <span class="content">720p</span>
                                </a>
                            </li>
                            <li>
                                <a class="updateQuality"  data-var="480p" class="focus">
                                    <span class="checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-232.35 146.35-468l64.89-64.89L382-362.13l366.76-366.76L813.65-664 382-232.35Z"/></svg>
                                    </span>
                                    <span class="content">480p</span>
                                </a>
                            </li>
                            <li>
                                <a class="updateQuality"  data-var="360p">
                                    <span class="checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-232.35 146.35-468l64.89-64.89L382-362.13l366.76-366.76L813.65-664 382-232.35Z"/></svg>
                                    </span>
                                    <span class="content">360p</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="head">
                        <!-- <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M407.65-72.35 0-480l407.65-407.65L486.3-809l-329 329 329 329-78.65 78.65Z"/></svg>
                        </a> -->
                        <div class="name">
                            <span class="name">Langue audio</span>
                        </div>
                    </div>
                    <div class="content">
                        <ul>
                            <li>
                                <a class="updateAudio" data-var="VF" class="focus">
                                    <span class="checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-232.35 146.35-468l64.89-64.89L382-362.13l366.76-366.76L813.65-664 382-232.35Z"/></svg>
                                    </span>
                                    <span class="content">Français</span>
                                </a>
                            </li>
                            <li>
                                <a class="updateAudio" data-var="VO">
                                    <span class="checked">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-232.35 146.35-468l64.89-64.89L382-362.13l366.76-366.76L813.65-664 382-232.35Z"/></svg>
                                    </span>
                                    <span class="content">Anglais</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="enn-controls-player-bar">
                <div id="enn-video-progressbar">
                    <span class="color2" style="width: 0%;"></span>
                    <span class="color" style="width: 0%;"></span>
                </div>
            </div>
            <div class="enn-controls-player-button">
                <div class="parent">
                    ${backVideo}
                    <button class="itm enn-controls-btn" id="enn-video-button-play"><svg style="transform: scale(1.2);" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M320-200v-560l440 280-440 280Z"/></svg></button>
                    ${nextVideo}
                    <div class="itm enn-controls-btn enn-button-volume">
                        <button id="enn-video-button-volume"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-131v-82q90-26 145-100t55-168q0-94-55-168T560-749v-82q124 28 202 125.5T840-481q0 127-78 224.5T560-131ZM120-360v-240h160l200-200v640L280-360H120Zm440 40v-322q47 22 73.5 66t26.5 96q0 51-26.5 94.5T560-320ZM400-606l-86 86H200v80h114l86 86v-252ZM300-480Z"/></svg></button>
                        <div class="enn-video-volume-bar">
                            <span class="color"></span>
                        </div>
                    </div>
                    <div class="itm enn-controls-btn enn-video-timestamp">
                        <span id="enn-video-timestamp-current">NaN</span>
                        <span>/</span>
                        <span id="enn-video-timestamp-duration">NaN</span>
                    </div>
                </div>
                <div class="parent">
                    <button class="itm enn-controls-btn" id="enn-video-button-settings"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg></button>
                    <button class="itm enn-controls-btn" id="enn-video-button-fullscreen"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M120-120v-320h80v184l504-504H520v-80h320v320h-80v-184L256-200h184v80H120Z"/></svg></button>
                </div>
            </div>
        </div>`
    }

    loadEvent() {
        this.playerContenaire.querySelectorAll('a.updateQuality').forEach((e) => {
            e.addEventListener('click', () => {
                this.updateQuality(e, e.dataset.var)
            })
        })
        this.playerContenaire.querySelectorAll('a.updateAudio').forEach((e) => {
            e.addEventListener('click', () => {
                this.updateAudio(e, e.dataset.var)
            })
        })

        this.videoElement.addEventListener('click', () => this.playOrPause())
        this.videoButtonPlay.addEventListener('click', () => this.playOrPause())
        this.videoButtonFullscreen.addEventListener('click', () => this.toggleFullscreen())
        this.videoButtonVolume.addEventListener('click', () => this.toggleVolume())
        this.videoButtonSettings.addEventListener('click', () => this.toggleSetting())

        if(this.obj.nextVideo) this.playerContenaire.querySelector('#enn-video-button-nextvideo').addEventListener('click', () => document.location = this.obj.nextVideo)
        if(this.obj.backVideo) this.playerContenaire.querySelector('#enn-video-button-backvideo').addEventListener('click', () => document.location = this.obj.backVideo)

        this.videoElement.addEventListener('play', () => {
            this.videoButtonPlay.innerHTML = this.icons.pause
        })

        this.videoElement.addEventListener('pause', () => {
            this.videoButtonPlay.innerHTML = this.icons.play
        })

        this.playerContenaire.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) this.videoButtonFullscreen.innerHTML = this.icons.fullscreen_close
            else this.videoButtonFullscreen.innerHTML = this.icons.fullscreen_open
        })

        this.videoElement.addEventListener('timeupdate', () => {
            this.videoProgressbar.querySelector('.color').style.width = (this.videoElement.currentTime / this.videoElement.duration * 100) + '%'
            if (this.videoElement.buffered.length > 0) {
                const bufferedEnd = this.videoElement.buffered.end(this.videoElement.buffered.length - 1)
                this.videoProgressbar.querySelector('.color2').style.width = (bufferedEnd / this.videoElement.duration * 100) + '%'
            }
            this.videoTimestampCurrent.textContent = this.formatTime(this.videoElement.currentTime)
        })
        this.videoElement.addEventListener('durationchange', () => {
            this.videoTimestampCurrent.textContent = this.formatTime(this.videoElement.currentTime)
            this.videoTimestampDuration.textContent = this.formatTime(this.videoElement.duration)
        })
        this.videoElement.addEventListener('volumechange', () => {
            if(this.videoElement.muted) this.videoButtonVolume.innerHTML = this.icons.volume_off
            else this.videoButtonVolume.innerHTML = this.icons.volume_up

            this.videoButtonVolume.parentNode.querySelector('.enn-video-volume-bar .color').style.width = (this.videoElement.volume / 1.0 * 100) + '%'
        })
        this.videoProgressbar.addEventListener('click', (e) => {
            const rect = this.videoProgressbar.getBoundingClientRect()
            const clickX = e.clientX - rect.left
            const percent = clickX / rect.width
            this.videoElement.currentTime = percent * this.videoElement.duration
        })

        this.videoButtonVolume.parentNode.querySelector('.enn-video-volume-bar').addEventListener('click', (e) => {
            const volumeBar = this.videoButtonVolume.parentNode.querySelector('.enn-video-volume-bar')
            const rect = volumeBar.getBoundingClientRect()
            const clickX = e.clientX - rect.left
            const percent = Math.min(Math.max(clickX / rect.width, 0), 1)
            this.videoElement.volume = percent
        })

        this.videoElement.addEventListener('error', () => {
            const cssClass = this.playerContenaire.classList[0];
            const error = this.videoElement.error

            this.playerContenaire.innerHTML = `<style>
.${cssClass} {
            position: relative;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            background-color: #000;
            color: #fff;
            fill: #fff;

            overflow: hidden;
        }
</style><div style="width: 100%;height: 100%;overflow: hidden;user-select: none;pointer-events: none;display: flex;align-items: center;justify-content: center;">

          <div style="aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px;">

            <div style="width: 52px; height: 52px; border-radius: 50%;border: 1.5px solid #E24B4A; display: flex; align-items: center; justify-content: center;">
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
              <span style="color: #E24B4A; font-size: 11px; font-family: monospace;">ERR_MEDIA_NOT_SUPPORTED</span>
            </div>
          </div>
        </div>`
        })

        let timer = null;

        const show = () => {
          this.playerContenaire.classList.add('show-control')
          this.playerContenaire.style.cursor = 'default';
        };

        const hide = () => {
          this.playerContenaire.classList.remove('show-control')
          this.playerContenaire.style.cursor = 'none';
        };

        const schedule = () => {
          clearTimeout(timer);
          timer = setTimeout(hide, 10000);
        };

        this.playerContenaire.addEventListener('mousemove', () => { show(); schedule(); });
        this.playerContenaire.addEventListener('mouseleave', () => { clearTimeout(timer); hide(); });
        this.playerControls.addEventListener('mouseenter', () => clearTimeout(timer));
        this.playerControls.addEventListener('mouseleave', () => schedule());

        show();
        schedule();
    }

    playOrPause() {
        if(this.videoElement.paused) this.videoElement.play()
        else this.videoElement.pause()
    }
    toggleFullscreen() {
        if (!document.fullscreenElement) this.playerContenaire.requestFullscreen()
        else document.exitFullscreen()
    }

    toggleVolume() {
        if(this.videoElement.muted) this.videoElement.muted = false
        else this.videoElement.muted = true
    }

    toggleSetting() {
        document.querySelector('.enn-option-player').classList.toggle('open')
    }

    formatTime(seconds) {
        const h = Math.floor(seconds / 3600)
        const m = Math.floor((seconds % 3600) / 60)
        const s = Math.floor(seconds % 60)

        if (h > 0) return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`
        else return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`
    }

    updateQuality(t, qual) {
        const c = this.videoElement.currentTime
        const p = this.videoElement.paused
        
        t.parentNode.parentNode.querySelectorAll('li a').forEach((e) => e.classList.remove('focus'))
        t.classList.add('focus')
        this.videoElement.src = this.videoElement.src.replace(this.currentQuality, qual)

        this.currentQuality = qual
        this.videoElement.currentTime = c
        if(!p) this.videoElement.play()
        document.querySelector('.enn-option-player').classList.remove('open')
    }

    updateAudio(t, qual) {
        const c = this.videoElement.currentTime
        const p = this.videoElement.paused

        t.parentNode.parentNode.querySelectorAll('li a').forEach((e) => e.classList.remove('focus'))
        t.classList.add('focus')
        this.videoElement.src = this.videoElement.src.replace(this.currentAudio, qual)

        this.currentAudio = qual
        this.videoElement.currentTime = c
        if(!p) this.videoElement.play()
        document.querySelector('.enn-option-player').classList.remove('open')
    }

    initVideoControls(hideDelay = 3000) {
      
    }
}