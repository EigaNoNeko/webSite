<?php
__OmsyMain->addModule('OmsyUrl');

$catalogue = __OmsySql->execute(
	'SELECT * FROM enncore_catalogues WHERE slug_ = ? LIMIT 1',
	[$_POST['catalogue']]
)->fetchAll()[0] ?? NULL;
if (!isset($catalogue)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$in = __EnnWatch->count($catalogue['uniqid']);
__OmsySql->execute(
	'UPDATE enncore_catalogues SET total_saison = ?, total_episode = ? WHERE uniqid = ?',
	[(int) $in['saison'], (int) $in['episode'], $catalogue['uniqid']]
);

$malId = __EnnCrawler->getMalId($catalogue['slug_']);
if(!isset($malId)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$mal = __EnnCrawler->request("/{$malId}/full");
sleep(1);

if(isset($mal['data']['status'])) {
	__OmsySql->execute('UPDATE enncore_catalogues SET status = ? WHERE uniqid = ?', [$mal['data']['status'], $catalogue['uniqid']]);
}


if(isset($mal['data']['streaming'])) {
	$buff = [];
	foreach ($mal['data']['streaming'] ?? [] as $value) {
		$value['name'] = strtolower($value['name']);
		if(str_contains($value['name'], 'netflix')) $buff[] = 'netflix';
		if(str_contains($value['name'], 'crunchyroll')) $buff[] = 'crunchyroll';
		if(str_contains($value['name'], 'anime digital network')) $buff[] = 'adn';
	}
	__OmsySql->execute('UPDATE enncore_catalogues SET streaming = ? WHERE uniqid = ?', [implode(',', $buff), $catalogue['uniqid']]);
}

return __OmsyMain->api('PUT', null, ['catalogue' => $catalogue['slug_'], 'status' => $mal['data']['status'] ?? null, 'saisons' => $in['saison'], 'episodes' => $in['episode']]);