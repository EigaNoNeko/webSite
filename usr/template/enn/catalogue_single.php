<?php
$cataloguewebmap = [
	"watchs" => [
		"icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M339.23-339.23h60v-221.54h78.46L560-339.23h61.54l105.38-281.54h-73.07l-63.08 181.16-62.31-181.16h-300v60h110.77v221.54ZM212.31-140Q182-140 161-161q-21-21-21-51.31v-535.38Q140-778 161-799q21-21 51.31-21h535.38Q778-820 799-799q21 21 21 51.31v535.38Q820-182 799-161q-21 21-51.31 21H212.31Zm0-60h535.38q4.62 0 8.46-3.85 3.85-3.84 3.85-8.46v-535.38q0-4.62-3.85-8.46-3.84-3.85-8.46-3.85H212.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46v535.38q0 4.62 3.85 8.46 3.84 3.85 8.46 3.85ZM200-760v560-560Z"/></svg>',
		"txt" => "Episodes"
	],
	"about" => [
		"icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M240-100q-41.92 0-70.96-29.04Q140-158.08 140-199.82V-300h120v-552.31l55.39 47.7 56.15-47.7 56.15 47.7 56.16-47.7 56.15 47.7 56.15-47.7 56.16 47.7 56.15-47.7 56.15 47.7 55.39-47.7V-200q0 41.92-29.04 70.96Q761.92-100 720-100H240Zm480-60q17 0 28.5-11.5T760-200v-560H320v460h360v100q0 17 11.5 28.5T720-160ZM367.69-610v-60h226.92v60H367.69Zm0 120v-60h226.92v60H367.69Zm310-114.62q-14.69 0-25.04-10.34-10.34-10.35-10.34-25.04t10.34-25.04q10.35-10.34 25.04-10.34t25.04 10.34q10.35 10.35 10.35 25.04t-10.35 25.04q-10.35 10.34-25.04 10.34Zm0 120q-14.69 0-25.04-10.34-10.34-10.35-10.34-25.04t10.34-25.04q10.35-10.34 25.04-10.34t25.04 10.34q10.35 10.35 10.35 25.04t-10.35 25.04q-10.35 10.34-25.04 10.34ZM240-160h380v-80H200v40q0 17 11.5 28.5T240-160Zm-40 0v-80 80Z"/></svg>',
		"txt" => "Détails"
	]
];

$Args = __OmsySecurity->getArgs();

$channel = $Args[1] ?? null;
$cataloguePage = $Args[2] ?? 'watchs';

$getSaison = $_GET['saison'] ?? 1;

switch ($cataloguePage) {
	case 'watchs':
		$cataloguePage = 'watchs';
		break;

	case 'reports':
		$cataloguePage = 'reports';
		break;
	
	default:
		$cataloguePage = 'watchs';
		break;
}
$data___ = []; //__SessionCache->get('catalogue-single.'.$channel);
if(isset($data___[0])) {
	$catalogueInfo = json_decode(html_entity_decode($data___[0]['content']), true);
} else {
	//$interfaceCatalogue = 'catalogue_single';
	$catalogueInfo = __EnnCatalogues->get($channel, "slug_", [
		"object" => true,
		"staff" => true,
		"status" => true,
		"description" => true,
		"genres" => true,
		"streaming" => true,
		"image" => ["poster", "thumbnail", "hero", "logo"],
		"data" => ["episodes", "episodes-hs"],
		"currentSaison" => $getSaison,
		"statistics" => ['saison', 'episode']
	]);
	if(is_string($catalogueInfo['genres'])) $catalogueInfo['genres'] = [$catalogueInfo['genres']];
	$catalogueInfo['information'] = [
		"Type" => (($catalogueInfo['statistics']['episode'] ?? 0) > 1) ? 'TV' : 'Movie',
		"Genres" => implode(', ', $catalogueInfo['genres'] ?? []),
		"Saisons" => $catalogueInfo['statistics']['saison'] ?? 1,
		"Episodes" => $catalogueInfo['statistics']['episode'] ?? 1,
	];
	__SessionCache->insert('catalogue-single.'.$channel, json_encode($catalogueInfo, JSON_UNESCAPED_UNICODE), 7);
}


$verify['follow'] = count(__OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-follow" AND object_name = ? AND object_content = ?',
	[$catalogueInfo['uniqid'], $_SESSION['uniqid'] ?? '']
)->fetchAll());

$verify['like'] = count(__OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-like" AND object_name = ? AND object_content = ?',
	[$catalogueInfo['uniqid'], $_SESSION['uniqid'] ?? '']
)->fetchAll());

$verify['unlike'] = count(__OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-unlike" AND object_name = ? AND object_content = ?',
	[$catalogueInfo['uniqid'], $_SESSION['uniqid'] ?? '']
)->fetchAll());

$counting['follow'] = count(__OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-follow" AND object_name = ?',
	[$catalogueInfo['uniqid']]
)->fetchAll());

$counting['like'] = count(__OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-like" AND object_name = ?',
	[$catalogueInfo['uniqid']]
)->fetchAll());

$counting['unlike'] = count(__OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-unlike" AND object_name = ?',
	[$catalogueInfo['uniqid']]
)->fetchAll());

__OmsyInterface->setTitle($catalogueInfo['title']  . ' - Eiga No Neko');
$embeding['description'] = htmlspecialchars_decode($catalogueInfo['description'] ?? '');
$embeding['keywords'] = htmlspecialchars_decode(implode(',', $catalogueInfo['genres'] ?? []));
$embeding['image'] = $catalogueInfo['image']['hero'] ?? '';
include 'interface/catalogue_single.php';