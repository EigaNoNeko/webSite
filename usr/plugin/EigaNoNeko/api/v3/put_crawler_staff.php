<?php
__OmsyMain->addModule('OmsyUrl');

$catalogue = __OmsySql->execute(
	'SELECT * FROM enncore_catalogues WHERE slug_ = ? LIMIT 1',
	[$_POST['catalogue']]
)->fetchAll()[0] ?? NULL;
if (!isset($catalogue)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$malId = __EnnCrawler->getMalId($catalogue['slug_']);
if(!isset($malId)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$staff = __EnnCrawler->request("/{$malId}/staff")['data'] ?? [];

$staffs = [];
foreach ($staff as $member) {
	$person	= $member['person'];
	$positions = implode(', ', $member['positions']);
	$staffId = getOrCreatePerson(
		$person['name'],
		$person['images']['jpg']['image_url'],
		'staff',
		'staff'
	);

	$staffs[] = [
		'id'   => $staffId,
		'role' => $positions
	];
}

if (count($staffs) > 0) {
	__OmsySql->execute(
		'UPDATE enncore_catalogues SET staff = ? WHERE uniqid = ?',
		[json_encode($staffs, JSON_UNESCAPED_UNICODE), $catalogue['uniqid']]
	);
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