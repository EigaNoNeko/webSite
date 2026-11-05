<?php
header('Content-type: text/plain');

$catalogues = __OmsySql->execute('SELECT uniqid FROM enncore_catalogues')->fetchAll();

$buffFile = [];
foreach ($catalogues as $value) {
	$channelImgSQL = __OmsySql->execute(
		'SELECT ID, type, name, content FROM enncore_catalogue_obj WHERE channel_id = ? ORDER BY ID DESC',
		[$value['uniqid']],
	)->fetchAll();

	$buff = [];
	foreach ($channelImgSQL as $obj) {
		if (!isset($buff[$obj['type'].'-'.$obj['name']])) {
			if (!empty(trim($obj['content']))) {
				$buff[$obj['type'].'-'.$obj['name']] = $obj;
				$path = parse_url($obj['content'], PHP_URL_PATH);
				if ($path) $buffFile[$path] = true;
			} else {
				// le plus récent est vide, on le supprime aussi
				__OmsySql->execute('DELETE FROM enncore_catalogue_obj WHERE ID = ?', [$obj['ID']]);
			}
			continue;
		}
		// doublon → supprimer
		__OmsySql->execute('DELETE FROM enncore_catalogue_obj WHERE ID = ?', [$obj['ID']]);
	}
	//echo "\n";
}

//var_dump($buffFile);

foreach (scandir(__Root . 'share/content-delivry/image/') as $value) {
	if(in_array($value, ['.', '..'])) continue;
	if(isset($buffFile['/share/content-delivry/image/'.$value])) continue;

	var_dump('/share/content-delivry/image/'.$value);

	unlink(__Root . 'share/content-delivry/image/' . $value);
}