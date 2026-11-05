const loadFormButton = () => {
	const btnFormSubmit_Contenaire = document.getElementById('publishChangeButton-contenaire')
	if(document.querySelector('form')) {
		btnFormSubmit_Contenaire.style.display = 'block'
		const btnFormSubmit = document.getElementById('publishChangeButton')
		if(document.querySelector('form').dataset.submit) btnFormSubmit.querySelector('span.text').textContent = document.querySelector('form').dataset.submit
		btnFormSubmit.addEventListener('click', () => {
			document.querySelector('form').submit()
			console.log('load')
		})
	} else { btnFormSubmit_Contenaire.style.display = 'none' }
}

Omsy.event.event.load['welcome'] = () => {
	loadFormButton()
}