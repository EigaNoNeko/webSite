const subButtonMenu = document.querySelector('#bubul-button')

let activeSubmenu = ''
const loadSubmenu = (button) => {
	document.body.classList.add('open-submenu')
	if(activeSubmenu === button.dataset.itemId) {
		document.body.classList.remove('open-submenu')
		activeSubmenu = ''
		subButtonMenu.style.top = -100+'px'
		return
	}

	if(activeSubmenu !== button.dataset.itemId) activeSubmenu = button.dataset.itemId
 
	request(`${button.href}`, {}, true)
	.then((res) => {
		console.log(res)
		let html = ''
		document.getElementById('app-contenaire-all-plugin-buttons').innerHTML = ''
		res.forEach((i) => {
			html += `<li>
			  <a href="${i.url}" data-update="embed">
			    <span class="icon">${i.icon ?? ''}</span>
			    <span class="text">${i.name}</span>
			  </a>
			</li>`

			//document.getElementById('app-contenaire-all-plugin-buttons').appendChild(li)
		})
		document.getElementById('app-contenaire-all-plugin-buttons').innerHTML = html
		loadButtonNotLocation()
	})
	//console.log(cal, button.offsetTop, subButtonMenu.offsetHeight, subButtonMenu.offsetHeight)
}
let oldButton = null
const fetchedContentParser = (event, button) => {
	document.body.classList.remove('open-submenu')
	activeSubmenu = ''

	if(button.dataset.update === 'submenu') {
		event.preventDefault()
		loadSubmenu(button)
		return
	}
}

const loadButtonNotLocation = () => {
	//return null;

	document.querySelectorAll('a').forEach((button) => {
		if(button.dataset.rooter == 'not') return
		if(button.classList.contains('event')) return
		button.classList.add('event')

		button.addEventListener('click', (event) => {fetchedContentParser(event, button)})
		if(button.classList.contains('button-header-menu')) {
			button.addEventListener('mouseover', () => {
				subButtonMenu.style.display = 'block'
				let cal = ((button.offsetHeight-subButtonMenu.offsetHeight)/2)+button.offsetTop

				subButtonMenu.style.top = cal+'px'
				subButtonMenu.textContent = button.dataset.name
			})
			button.addEventListener('mouseleave', () => {
				subButtonMenu.style.display = 'none'
			})
		}
	})
}

const loadPage = () => {
	loadButtonNotLocation()
}

const omsyIcon = {
	"omsy.button.research": '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M783.52-110.91 529.85-364.59q-29.76 23.05-68.64 36.57-38.88 13.52-83.12 13.52-111.16 0-188.33-77.17-77.17-77.18-77.17-188.33t77.17-188.33q77.17-77.17 188.33-77.17 111.15 0 188.32 77.17 77.18 77.18 77.18 188.33 0 44.48-13.52 83.12-13.53 38.64-36.57 68.16l253.91 254.15-63.89 63.66ZM378.09-405.5q72.84 0 123.67-50.83 50.83-50.82 50.83-123.67t-50.83-123.67q-50.83-50.83-123.67-50.83-72.85 0-123.68 50.83-50.82 50.82-50.82 123.67t50.82 123.67q50.83 50.83 123.68 50.83Z"/></svg>',
	"omsy.button.save": '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M224.62-160q-26.85 0-45.74-18.88Q160-197.77 160-224.62v-510.76q0-26.85 18.88-45.74Q197.77-800 224.62-800h438.46L800-663.08v181.23q-9.77-3.38-19.88-3.96-10.12-.57-20.12.12v-160.54L646.23-760H224.62q-10.77 0-17.7 6.92-6.92 6.93-6.92 17.7v510.76q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h209.23v40H224.62ZM200-760v560-560ZM529.23-80v-88.38l213.31-212.31q5.92-5.93 12.31-8 6.38-2.08 12.77-2.08 6.61 0 13.38 2.58 6.77 2.57 11.92 7.73l37 37.77q4.93 5.92 7.5 12.31Q840-324 840-317.62q0 6.39-2.46 12.89-2.46 6.5-7.62 12.42L617.62-80h-88.39Zm275.39-237.62-37-37.76 37 37.76Zm-240 202.24h38l138.69-138.93-18.77-19-18.23-19.54-139.69 139.47v38Zm157.92-157.93-18.23-19.54 37 38.54-18.77-19ZM270.77-569.23h296.92v-120H270.77v120ZM480-298.46q7.15 0 13.54-1.54 6.38-1.54 12.77-5.38l46.77-45.77q3.84-6.62 5.38-13.27 1.54-6.66 1.54-14.04 0-33.08-23.46-56.54T480-458.46q-33.08 0-56.54 23.46T400-378.46q0 33.08 23.46 56.54T480-298.46Z"/></svg>',
	"omsy.button.publish": '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M755.38-200v-483.38L696-624l-28.31-28.31L775.38-760l107.7 107.69L854.54-624l-59.16-59.15V-200h-40ZM160-275.38v-128.39l295-290.92q19.38-18.62 46.35-18.39 26.96.23 45.34 19.62l37.23 37.69q18.39 19.15 18.27 46-.11 26.85-19.27 45.23l-293 289.16H160Zm40-40h73.85l195.84-193.54-36.54-36.16-36.53-36.15L200-387.69v72.31Zm298.23-222.08-73.08-72.31 73.08 72.31Z"/></svg>'
}

const omsyMessage = {
	"omsy.button.research": 'Rechercher',
	"omsy.button.save": 'Sauvegarder',
	"omsy.button.publish": 'Publier'
}

const loadFormButton = () => {
	const btnFormSubmit_Contenaire = document.getElementById('publishChangeButton-contenaire')
	const elementForm = document.querySelector('form')
	if(elementForm) {
		const buttonType = elementForm.dataset.sub ?? 'omsy.button.save'

		btnFormSubmit_Contenaire.style.display = 'block'
		const btnFormSubmit = document.getElementById('publishChangeButton')
		btnFormSubmit.querySelector('span.icon').innerHTML = omsyIcon[buttonType]
		btnFormSubmit.querySelector('span.text').textContent = omsyMessage[buttonType]
		btnFormSubmit.addEventListener('click', () => {
			elementForm.submit()
			console.log('load')
		})
	} else { btnFormSubmit_Contenaire.style.display = 'none' }
}

window.onload = () => {
	loadFormButton()
	loadButtonNotLocation()
}


const getImage = (input, image) => {
	if(input.files[0]) image.src = URL.createObjectURL(input.files[0])
}