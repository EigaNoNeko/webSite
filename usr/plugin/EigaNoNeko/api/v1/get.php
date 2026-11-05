<span id="po"></span>
<script>
let start = 6776;
const reqJSON = (page) => {
	fetch('/api/v1/db?db=enncore_player&page='+page)
	.then((res) => res.json())
	.then((res) => {
		console.log(start, res.total_page)
		document.getElementById('po').textContent = `${start} / ${res.total_page}`
		sendJSON(res)
	})
}

const sendJSON = (json) => {
	const formData = new FormData();
	formData.append("data", JSON.stringify(json));

	fetch('https://4oipffhcjv.eiganoneko.com/api/v1/_db?db=enncore_player', {
		method: "POST",
		headers: { "Content-Type": "application/json" },
  		body: JSON.stringify(json)
	})
	.then((res) => res.text())
	.then((res) => {
		//console.log(res)
		if((start + 1) <= json.total_page) {
			start++;
			reqJSON(start)
		}
		else {
			console.log('ended')
		}
	})
}

// reqJSON(6776)
</script>