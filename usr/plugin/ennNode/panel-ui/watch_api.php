<?php
$channelId = '';
if(isset($_POST['send_form_lang'])) {
if(
	!empty($_POST['links']) &&
	!empty($_POST['saison']) &&
	!empty($_POST['max_saison']) &&
	!empty($_POST['channel']) &&
	!empty($_POST['lang'])
) {
	$text = $_POST['links'];
	$saison = intval($_POST['saison']);
	$max_saison = intval($_POST['max_saison']);
	$channelId = htmlentities($_POST['channel']);
	$lang = htmlentities($_POST['lang']);
	$serie = htmlentities($_POST['serie']);

	$results = [];
	if (preg_match_all('/var\s+([a-zA-Z0-9_]+)\s*=\s*\[(.*?)\];/s', $text, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			$varName = $match[1];
			$inside = $match[2];

			preg_match_all('/https?:\/\/[^\s\'",\]]+/i', $inside, $urls);

			$cleaned = [];
			foreach ($urls[0] as $url) {
				if(str_contains($url, 'anime-sama.')) continue;
				$url = trim($url, " \t\n\r'\"");
				$cleaned[] = $url;
			}
			$results[$varName] = array_values(array_unique($cleaned));
		}
	}

	foreach ($results as $vars) {
	    $i = 0;
		foreach ($vars as $value) {
			$m = 'iframe';
			$ui = $i+1;
			$uniq = uniqid();

			if(!isset($buffId[$i])) $buffId[$i] = $uniq;

			$insert = [
				'uniqid' => $buffId[$i],
				'saison' => $saison,
				'episode' => $ui,

				'quality' => htmlentities(json_encode([])),
				'mime' => htmlentities(json_encode([$m])),
				'lang' => $lang,

				'video' => $value,
				'channel' => $channelId
			];

			__OmsySql->execute(
				'INSERT INTO enncore_player(uniqid, catalogue_id, episode, saison, src, mime, quality, lang_) VALUES(?, ?, ?, ?, ?, ?, ?, ?)',
				[
					$insert['uniqid'],
					$insert['channel'],
					$insert['episode'],
					$insert['saison'],
					$insert['video'],
					$insert['mime'],
					$insert['quality'],

					$insert['lang']]
			);
			$i++;
		}
	}
}
}

$_GET['channel'] = $_GET['channel'] ?? '';
?>
<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Ajouter une liste de lien</h1>
    </div>
    <div class="content">
        <ul>
            <li><a href="/panel/getter?plugin=ennNode&page=channels" data-update="embed">Liste des chaine</a></li>
            <li><a href="/panel/getter?plugin=ennNode&page=channel" data-update="embed">Ajouter une chaine</a></li>
            <li><a class="active" href="/panel/getter?plugin=ennNode&page=watch_api" data-update="embed">Ajouter une liste de lien</a></li>
        </ul>
    </div>
</section>
<section class="appkit-form-contenaire">
	<form method="post" enctype="multipart/form-data">
		<input name="send_form_lang" value="true" hidden>
		<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Détails</h2>
				<button id="call">Calls</button>
			</div>
			<div class="content">
				<div class="appKitUi-input">
					<label>Série</label>
					<select name="channel">
						<?php $channelList = __OmsySql->execute('SELECT * FROM enncore_catalogue ORDER BY ID DESC'); ?>
						<?php foreach($channelList->fetchAll() as $value): ?>
						<option value="<?= $value['uniqid'] ?>" <?php if($_GET['channel'] == $value['uniqid']) echo 'selected'; ?>><?= $value['title'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="multi-input appUiKit-triple-input appUiKit-marginTop">
					<div class="appKitUi-input">
						<label>Actual saison</label>
						<input type="number" name="saison" id="saison" min="1" value="<?= $_GET['actual'] ?? 1 ?>">
					</div>
					<div class="appKitUi-input">
						<label>Max saison</label>
						<input type="number" name="max_saison" min="1" value="<?= $_GET['max'] ?? 1 ?>">
					</div>
					<div class="appKitUi-input">
						<label>Serie</label>
						<input type="text" name="serie" id="serie" min="0" value="<?= $_GET['serie'] ?? 'le-monde-incroyable-de-gumball' ?>">
					</div>
					<div class="appKitUi-input">
						<label>Langue</label>
						<input type="text" name="lang" id="lang" value="<?= $_GET['lang'] ?? 'vostfr' ?>">
					</div>
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Link list</label>
					<textarea type="text" name="links" id="links"><?= $text ?? '' ?></textarea>
				</div>
			</div>
		</section>
	</form>
</section>
<?= omsp_footer() ?>
<script>
document.getElementById('call').addEventListener('click', (e) => {
	e.preventDefault()
	upadate()
})

<?php if(isset($_GET['auto'])) echo 'upadate()'; ?>

function upadate() {
	let serie = document.getElementById('serie').value
	let saison = document.getElementById('saison').value
	let lang = document.getElementById('lang').value

	let url = `https://anime-sama.eu/catalogue/${serie}/saison${saison}/${lang}/episodes.js`
	request('/api/ennNode/watch_curl', {
		saison: `saison${saison}`,
		lang: lang,
		watch: serie
	})
	.then((e) => {
		document.getElementById('links').value = e
		document.querySelector('form').submit()
	})
}
</script>