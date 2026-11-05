<?php
__OmsyMain->addModule('OmsyUrl');

$catalogue = __OmsySql->execute(
	'SELECT * FROM enncore_catalogues WHERE slug_ = ? LIMIT 1',
	[$_POST['catalogue']]
)->fetchAll()[0] ?? NULL;
if (!isset($catalogue)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$malId = __EnnCrawler->getMalId($catalogue['slug_']);
if(!isset($malId)) return __OmsyMain->api('PUT', ['msg' => 'Données inconnue']);

$mal = __EnnCrawler->request("/{$malId}/full");
sleep(1);

// Images du catalogue
$err = [];
$thumbnailAndHero = file_get_contents("https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/{$_POST['catalogue']}.jpg");

$poster = 'share/_omsy/download/poster/' . $_POST['catalogue'] . '.jpg';
$thumbnail = 'share/_omsy/download/thumbnail/' . $_POST['catalogue'] . '.jpg';
$hero = 'share/_omsy/download/hero/' . $_POST['catalogue'] . '.jpg';

$err['poster'] = __EnnCrawler->download(
	$mal['data']['images']['jpg']['large_image_url'],
	__Root . '/' . $poster
);
$err['thumbnail'] = __EnnCrawler->download(
	"https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/{$_POST['catalogue']}.jpg",
	__Root . '/' . $thumbnail,
);
$err['hero'] = __EnnCrawler->download(
	"https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/{$_POST['catalogue']}.jpg",
	__Root . '/' . $hero
);

return __OmsyMain->api('PUT', null, $err);