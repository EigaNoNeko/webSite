<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
	<div class="head">
		<h1>Liste des chaine</h1>
	</div>
	<div class="content">
		<ul>
			<li><a onclick="streamCall('next')" data-update="embed">Next</a></li>
			<li><a onclick="streamCall('start')" data-update="embed">Start</a></li>
			<li><a onclick="streamCall('stop')" data-update="embed">Stop</a></li>
		</ul>
	</div>
</section>
<section class="appUiKit-table app-kit-main-padding">
	<table>
		<thead>
			<tr class="">
				<td width="32px"><input type="checkbox" name=""></td>
				<td width="32px"><b>stat</b></td>
				<td><b>ID</b></td>
				<td><b>URL</b></td>
			</tr>
		</thead>
		<tbody>
			
		</tbody>
	</table>
</section>
<?= omsp_footer() ?>
<script>
const streamAPi = 'https://flux.eiganoneko.com:8443/api/&wHo^xZ=At&iL*TZ32$dH5i69DyGdUc4-Vh^03E*c_V0XgU*b_jMKSxYAYxaQdIMp3oX5whDm1TexqCVJ*sr+XmZI26xzKuYM6'
function streamCall($p) {
	request(streamAPi+'/'+$p)
	.then((res) => {
		console.log(res)
	})
}

omsyInterval.setRequestInterval(streamAPi+'/stat', 'tvApi', (res) => {
	if(!document.querySelector('header.top-header ul:nth-child(1) #get-ennstreamflux')) {
		document.querySelector('header.top-header ul:nth-child(1)').insertAdjacentHTML('beforeend', `<li>
			<a href="/stat">
				<span class="icon">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M480-480Zm320 320H600q0-20-1.5-40t-4.5-40h206v-480H160v46q-20-3-40-4.5T80-680v-40q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160Zm-720 0v-120q50 0 85 35t35 85H80Zm200 0q0-83-58.5-141.5T80-360v-80q117 0 198.5 81.5T360-160h-80Zm160 0q0-75-28.5-140.5t-77-114q-48.5-48.5-114-77T80-520v-80q91 0 171 34.5T391-471q60 60 94.5 140T520-160h-80Z"/></svg>
				</span>
				<span class="text" id="get-ennstreamflux">${res.viewer} viewers</span>
			</a>
		</li>`)
	}

	document.querySelector('header.top-header ul:nth-child(1) #get-ennstreamflux').textContent = res.viewer + ' viewers'
	//console.log(res)
	document.querySelector('tbody').innerHTML = ''
	res.playlist.forEach((e) => {
		//e.status = 'null'
		const statusEmojis = {
		  end_stream: '✅',   // Fini
		  stream: '🚀',	   // En cours / en streaming
		  preload: '⚡',	  // Préchargement
		  not_preload: '⏳'   // En attente
		};

		if (statusEmojis[e.status]) {
		  e.status = statusEmojis[e.status];
		}

		console.log(e)
		document.querySelector('tbody').insertAdjacentHTML('beforeend', `<tr class=""><td width="32px"><input type="checkbox" name=""></td><td width="32px"><b>${e.status}</b></td><td><b>${e.id}</b></td><td><b>${e.video}</b></td></tr>`)
	})
})
</script>