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

loadFormButton()


const data_request = [
	{
		call: '/api/omsyPanel/test',
		name: "Teste 1"
	},
	{
		call: '/api/omsyPanel/test',
		name: "Teste 2"
	},
	{
		call: '/api/omsyPanel/test',
		name: "Teste 3"
	},
	{
		call: '/api/omsyPanel/test',
		name: "Teste 4"
	},
	{
		call: '/api/omsyPanel/test',
		name: "Teste 5"
	},
	{
		call: '/api/omsyPanel/test',
		name: "Teste 6"
	}
]

console.log(data_request.length)

const progress = document.querySelector('.progress-id-izebvezbn')
let progressBar = 0
function teste(url) {
	progress.querySelector('.progress-message').textContent = data_request[progressBar].name
	request(url, {}, true)
	.then((res) => {
		addLogs(res.message)
		if(progressBar < data_request.length) {
			teste(data_request[progressBar].call)
		} else setTimeout(() => {
			progressBar = 0
			teste(data_request[progressBar].call)
		}, 5000)

		progress.querySelector('.progress-content').style.width = (((progressBar+1) / data_request.length) * 100)+'%'
		progressBar++
	})
}

//teste(data_request[progressBar].call)

function addLogs(msg, err = 'success') {
	if(document.querySelectorAll('#message-system li').length > 5) document.getElementById('message-system').removeChild(document.getElementById('message-system').firstChild);

	document.querySelector('#message-system').insertAdjacentHTML('beforeend', `<li class="${err}">${msg}</li>`)
}


//let progressBar = 0

const progressMsg = {
	10: {name: "Saison 1..."},
	20: {name: "Saison 2..."},
	30: {name: "Saison 3..."},
	40: {name: "Saison 4..."},
	50: {name: "Saison X..."},
	100: {name: "Terminer !"}
}
/*
setInterval(() => {
	if(progressBar > 100) {
		setTimeout(() => progress.remove(), 5000)
		return;
	}
	progress.querySelector('.progress-content').style.width = progressBar+'%'
	if(progressMsg[progressBar]) progress.querySelector('.progress-message').textContent = progressMsg[progressBar].name
	progressBar+=5
}, 1000)*/