<?php
if(isset($_POST['send_form_lang'])) {
if(
	!empty($_POST['links']) &&
	!empty($_POST['saison']) &&
	!empty($_POST['channel']) &&
	!empty($_POST['lang'])
) {
	$text = $_POST['links'];
	$saison = intval($_POST['saison']);
	$countStarted = intval($_POST['ep_start']) ?? 0;
	$channelId = htmlentities($_POST['channel']);
	$lang = htmlentities($_POST['lang']);

	// Expression régulière pour capturer les liens
	preg_match_all('/https?:\/\/[^\s\'",]+/', $text, $matches);

	// Supprime les apostrophes éventuels autour des liens
	$links = array_map(fn($link) => trim($link, "'"), $matches[0]);

	// Extraction des liens
	$links = $matches[0];

	// Affichage des liens
	//print_r($links);

	foreach ($links as $i => $value) {
		$value = str_replace('&#039;', '', $value);

		var_dump($value);
		echo '<br>';

		$m = 'iframe';
		$ui = $countStarted+($i+1);
		$uniq = uniqid();
		$w = CMS->DataBase->execute('SELECT * FROM enncore_player WHERE episode = ? AND saison = ? AND catalogue_id = ?', [$ui, $saison, $channelId])->fetchAll();
		if(isset($w[0])) $uniq = $w[0]['uniqid'];

		$ww = CMS->DataBase->execute('SELECT * FROM enncore_player WHERE src = ? AND catalogue_id = ?', [$value, $channelId])->fetchAll();
		var_dump($ww);
		if(isset($ww[0])) continue;

		if(!isset($buffId[$i])) $buffId[$i] = $uniq;

		$insert = [
			'uniqid' => $buffId[$i],
			'saison' => $saison,
			'episode' => $ui,

			'thumbnail' => htmlentities(json_encode([])),
			'quality' => htmlentities(json_encode([])),
			'mime' => htmlentities(json_encode([$m])),
			'lang' => $lang,

			'video' => $value,
			'channel' => $channelId
		];

		var_dump([
				$insert['uniqid'],
				$insert['channel'],
				$insert['thumbnail'],
				"Episode ".$insert['episode'],
				$insert['episode'],
				$insert['saison'],
				$insert['video'],
				$insert['mime'],
				$insert['quality'],

				$insert['lang']
			]);
		
		CMS->DataBase->execute(
			'INSERT INTO enncore_player(uniqid, catalogue_id, thumbnail, title, episode, saison, src, mime, quality, lang_) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
			[
				$insert['uniqid'],
				$insert['channel'],
				$insert['thumbnail'],
				"Episode ".$insert['episode'],
				$insert['episode'],
				$insert['saison'],
				$insert['video'],
				$insert['mime'],
				$insert['quality'],

				$insert['lang']]
		);
		
		var_dump($insert);
		echo '<br>';
		echo '<br>';
	}
}
}
?>
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
			</div>
			<div class="content">
				<div class="appKitUi-input">
					<label>Série</label>
					<select name="channel">
						<?php $channelList = CMS->DataBase->execute('SELECT * FROM enncore_catalogue ORDER BY ID DESC'); ?>
						<?php foreach($channelList->fetchAll() as $value): ?>
						<option value="<?= $value['uniqid'] ?>"><?= $value['title'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="multi-input appUiKit-double-input appUiKit-marginTop">
					<div class="appKitUi-input">
						<label>Saison</label>
						<input type="number" name="saison" min="1" value="<?= $saison ?? 1 ?>">
					</div>
					<div class="appKitUi-input">
						<label>Start Episodes counting</label>
						<input type="number" name="ep_start" min="0" value="<?= $countStarted ?? 0 ?>">
					</div>
					<div class="appKitUi-input">
						<label>Langue</label>
						<input type="text" name="lang" value="<?= $lang ?? 'vo' ?>">
					</div>
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Link list</label>
					<textarea type="text" name="links"><?= $text ?? '' ?></textarea>
				</div>
			</div>
		</section>
	</form>
</section>
<!-- <section class="main-form main-panel">
	<form method="post">
		<section class="dashpadding content">
			<section class="input input-margin">
				<label>Série</label>
				<select name="channel">
					<?php $channelList = CMS->DataBase->execute('SELECT * FROM pvs_channel'); ?>
					<?php foreach($channelList->fetchAll() as $value): ?>
					<option value="<?= $value['uniqid'] ?>"><?= $value['title'] ?></option>
					<?php endforeach; ?>
				</select>
			</section>
			<div class="input textarea input-margin">
				<label>Link list</label>
				<textarea type="text" name="links"><?= $text ?? '' ?></textarea>
			</div>
			<div class="multi-input triple-input input-margin">
				<div class="input text">
					<label>Saison</label>
					<input type="number" name="saison" min="1" value="<?= $saison ?? 1 ?>">
				</div>
				<div class="input text">
					<label>Start Episodes counting</label>
					<input type="number" name="ep_start" min="0" value="<?= $countStarted ?? 0 ?>">
				</div>
				<div class="input text">
					<label>Langue</label>
					<input type="text" name="lang" value="<?= $lang ?? 'vo' ?>">
				</div>
			</div>
		</section>
	</form>
</section>
<form method="post">
	<textarea name="links"></textarea>
	<input type="number" name="saison" value="1">
	<input type="number" name="ep_start" value="0">
	<input type="text" name="channel" placeholder="channel">
	<input type="text" name="lang" value="vf">
</form> -->