	</section>
</main>
<script type="text/javascript">
omsyInterval.setRequestInterval('/api/session', 'sessionCounting', (res) => {
	document.getElementById('get-session').textContent = res.total_session+' Session'
})

//window.onload = () => {
	omsyInterval.loadInterval()
//}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
<script>
// ⚠️ adapte l’URL avec ton serveur (ex: ws://localhost:8080)
const socket = new WebSocket("ws://152.228.163.14:8080");

socket.addEventListener("open", () => {
	console.log("Connecté au serveur WebSocket");
	if (window.location.hostname === '127.0.0.1' || window.location.hostname === 'localhost') socket.send('refresh');

	afficherInfos();

	// Écoute le mouvement de la souris
	window.addEventListener('mousemove', (e) => {
		sourisX = e.clientX;
		sourisY = e.clientY;
		afficherInfos();
	});

	// Écoute le scroll
	window.addEventListener('scroll', () => {
		afficherInfos();
	});
});

socket.addEventListener("message", event => {
	const c = event.data.split(',')
	let mouseX = c[0];
	let mouseY = c[1];
	let windowHeight = c[2];
	let windowWidth = c[3];
	let scrollX = c[4];
	let scrollY = c[5];
	let ID = "a"+c[6];
	console.log(mouseX, mouseY, windowHeight, windowWidth, scrollX, scrollY, ID, document.querySelector('#'+ID))

	if(document.querySelector('#'+ID) === null) {
		console.log('ad')
		document.querySelector('body').innerHTML += `<div id="${ID}" style="width: ${windowWidth}px; height: ${windowHeight}px; z-index: 9999; position: fixed; top: 0; left: 0; background: red;"><div class="mouse" style="position: relative; top: ${mouseY}px; left: ${mouseX}px; background: ; display: inline-block;"><img src="https://www.freeiconspng.com/thumbs/cursor-png/white-mouse-cursor-arrow-by-qubodup-11.png" width="12px" style="display: block;"></div></div>`
	}

	document.querySelector('#'+ID+' .mouse').style.left = mouseX+'px'
		document.querySelector('#'+ID+' .mouse').style.top = mouseY+'px'
});

socket.addEventListener("close", () => {
	console.log("Déconnecté du serveur WebSocket");
});

// Variables pour la souris
let sourisX = 0;
let sourisY = 0;

// Afficher les infos
function afficherInfos() {
	const scrollX = window.scrollX;
	const scrollY = window.scrollY;
	const windowHeight = window.innerHeight;
	const windowWidth = window.innerWidth;

	//console.log(`mouseX: ${sourisX}, mouseY: ${sourisY} scrollX: ${scrollX}, scrollY: ${scrollY} WindowHeight: ${windowHeight}, WindowWidth: ${windowWidth}`);
	//console.log(`${sourisX},${sourisY},${windowHeight},${windowWidth},${scrollX},${scrollY}`);
	socket.send(`${sourisX},${sourisY},${windowHeight},${windowWidth},${scrollX},${scrollY},a${md5(document.location)}`);
}
</script>
</body>
</html>