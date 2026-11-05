</main>
<script>
function timeUpdateFun() {
	const date = new Date()
    const pad = (n) => n.toString().padStart(2, '0')
    let dateToString = `${pad((date.getDate()))}/${pad((date.getMonth() + 1))}/${pad(date.getFullYear())} ${pad(date.getHours())}:${pad(date.getMinutes())}`
	document.getElementById('timestamp').textContent = dateToString

	setTimeout(timeUpdateFun, 30000)
}
Omsy.event.event.load['OmsyTime'] = timeUpdateFun
</script>
</body>
</html>