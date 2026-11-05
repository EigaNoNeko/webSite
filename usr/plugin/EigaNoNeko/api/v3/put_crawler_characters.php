<?php
__OmsyMain->addModule('OmsyUrl');

$catalogue = __OmsySql->execute(
	'SELECT * FROM enncore_catalogues WHERE slug_ = ? LIMIT 1',
	[$_POST['catalogue']]
)->fetchAll()[0] ?? NULL;
if (!isset($catalogue)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$malId = __EnnCrawler->getMalId($catalogue['slug_']);
if(!isset($malId)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

// Personnages + voice actors
$characters = __EnnCrawler->request("/{$malId}/characters")['data'] ?? [];
foreach ($characters as $char) {
	$character   = $char['character'];
	$voiceActors = $char['voice_actors'];

	$voiceAct = [];
	foreach ($voiceActors as $va) {
		$vaId = getOrCreatePerson(
			$va['person']['name'],
			$va['person']['images']['jpg']['image_url'],
			'voice_actor',
			'voice-actors'
		);
		$voiceAct[$va['language']][] = $vaId;
	}

	// Vérifie si le personnage existe déjà pour ce catalogue
	$existingChar = __OmsySql->execute(
		'SELECT * FROM enncore_characters WHERE name = ? AND catalogue_id = ? AND about = "character"',
		[$character['name'], $catalogue['uniqid']]
	)->fetchAll();
	if (isset($existingChar[0])) continue;

	$charId = uniqid();
	__OmsySql->execute(
		'INSERT INTO enncore_characters(uniqid, catalogue_id, name, role, voice_actors, about) VALUES(?, ?, ?, ?, ?, "character")',
		[$charId, $catalogue['uniqid'], $character['name'], $char['role'], json_encode($voiceAct, JSON_UNESCAPED_UNICODE)]
	);

	$imageUrl = $character['images']['jpg']['image_url'];
	if (!empty($imageUrl)) {
		$imageData = file_get_contents($imageUrl);
		if ($imageData !== false) {
			$slug = __OmsyString->slugify($character['name']);
			file_put_contents(__Root . "share/_omsy/download/characters/{$catalogue['slug_']}_{$slug}_{$charId}.jpg", $imageData);
		}
	}
}

return __OmsyMain->api('PUT', null);


function getOrCreatePerson($name, $imageUrl, $about, $folder) {
	$existing = __OmsySql->execute(
		'SELECT * FROM enncore_characters WHERE name = ? AND about = ?',
		[$name, $about]
	)->fetchAll();

	if (isset($existing[0])) return $existing[0]['uniqid'];

	$id = uniqid();
	__OmsySql->execute(
		'INSERT INTO enncore_characters(uniqid, catalogue_id, name, role, voice_actors, about) VALUES(?, "", ?, "", "", ?)',
		[$id, $name, $about]
	);

	if (!empty($imageUrl)) {
		$imageData = file_get_contents($imageUrl);
		if ($imageData !== false) {
			$slug = __OmsyString->slugify($name);
			file_put_contents(__Root . "share/_omsy/download/{$folder}/{$slug}_{$id}.jpg", $imageData);
		}
	}

	return $id;
}