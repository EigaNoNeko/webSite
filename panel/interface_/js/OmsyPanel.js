const omsyDate = new class omsPanel_Date {
	convertNumberToDayString(dateString) {
	    // Création d'un objet Date à partir de la chaîne de date
	    let date = new Date(dateString);
	    
	    // Tableau des jours de la semaine en français
	    let jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
	    
	    // Récupération du jour de la semaine
	    return jours[date.getDay()];
	}
}

const omsyStat = new class omsPanel_statistique {
	maxView = {number: 0, link: {}, date: ''}
	viewTotalWeek = 0
	maxViewLastDay = 0

	request(callback) {
		omsyInterval.setRequestInterval('/api/session', 'session', (res) => {
			document.getElementById('total-session').textContent = res.total_session
			document.getElementById('get-session').textContent = res.total_session+' Session'

			document.getElementById('first-watch-page').innerHTML = ''
			document.getElementById('best-watch-page').innerHTML = ''
			res.list.forEach((e) => {
				document.getElementById('first-watch-page').insertAdjacentHTML('afterbegin',`<li class="list-stat-flex-space">
					<span>${e.page}</span>
					<span>${e.appareil}</span>
				</li>`)
			})

			for (const [key, value] of Object.entries(res.best)) {
				document.getElementById('best-watch-page').insertAdjacentHTML('afterbegin',`<li class="list-stat-flex-space">
					<span>${key}</span>
					<span>${value}</span>
				</li>`)
			}
		})
	}

	getSession(res) {
	  /* console.log(res)

	  const total = res.total_session
	  const barHeight = ((total / maxViewLastDay) * 100).toFixed(2);

	  document.getElementById('session-li').innerHTML = `<li class="item-anatyique-data">
	    <span class="name">Session Active sur ${maxViewLastDay} view <span style="color: #747474;">(${total})</span></span>
	    <span class="progress-bar">
	      <span class="content" style="width: ${barHeight}%;"></span>
	    </span>
	  </li>` */
	}
}