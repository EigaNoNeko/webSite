<?php
// ─── Helpers ────────────────────────────────────────────────────────────────
function getDefaultImages(): array {
	return [
		'poster'	=> '/interface/img/poster.png',
		'thumbnail' => '/interface/img/thumbnail.png',
		'banner'	=> '/interface/img/banner.png',
	];
}
function buildChannelItem(array $catalogueObj, string $slug, int $titleMaxLen = 45): array {
	return [
		'title'  => __OmsyString->htmlDecode(__OmsyString->substr($catalogueObj['channel-language']['default_item']['title'] ?? '', $titleMaxLen)),
		'id'	 => $slug,
		'images' => $catalogueObj['img'] ?? getDefaultImages(),
		// 'images' => [
		// 	'poster' => "https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/$slug.jpg"
		// ]
	];
}

function buildChannelItem_(array $catalogueObj, int $titleMaxLen = 45): array {
	return [
		'title'  => __OmsyString->substr(__OmsyString->htmlDecode($catalogueObj['title'] ?? ''), $titleMaxLen),
		'description'  => __OmsyString->substr(__OmsyString->htmlDecode($catalogueObj['description'] ?? ''), 120),
		'id'	 => $catalogueObj['slug'],
		'uniqid'	 => $catalogueObj['uniqid'],
		'genres'	 => $catalogueObj['genres'],
		'langues'	 => $catalogueObj['langues'],
		'images' => $catalogueObj['image'] ?? getDefaultImages(),
		// 'images' => [
		// 	'poster' => "https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/$slug.jpg"
		// ]
	];
}

$bann = ['my-dress-up-darling', 're-zero', 'one-piece', 'made-in-abyss', 'horimiya', 'dandadan'];
shuffle($bann);

$heroes = [];
foreach ($bann as $key => $value) {
	$heroes[] = __EnnCatalogues->get($value, 'slug_', [
		"genres" => true,
		"description" => true,
		"image" => ["poster", "thumbnail", "hero"],
		"langues" => true,
	]);
}

$c = 0;
$buff = [
	'newEpisodesAdded' => [],
];
$buff_ = [];
$buff__ = [];
$data___ = __SessionCache->get('homepage.newEpisodesAdded');
if(isset($data___[0])) {
	$buff['newEpisodesAdded'] = json_decode(html_entity_decode($data___[0]['content']), true);
} else {
	$recents = __OmsySql->execute(
		'SELECT uniqid FROM enncore_catalogues ORDER BY last_update DESC LIMIT 7'
	)->fetchAll();
	foreach ($recents as $row) {
		$obj = __EnnCatalogues->get($row['uniqid'], 'uniqid', [
			"genres" => true,
			"description" => true,
			"image" => ["poster", "thumbnail", "hero"],
			"langues" => true,
		]);
		$buff['newEpisodesAdded'][] = buildChannelItem_($obj);
	}
	$buff['newEpisodesAdded'] = array_map(function ($item) {
	    return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
	}, $buff['newEpisodesAdded']);
	__SessionCache->insert('homepage.newEpisodesAdded', json_encode($buff['newEpisodesAdded'], JSON_UNESCAPED_UNICODE), 7);
}
$data___ = __SessionCache->get('homepage.genrings');
if(isset($data___[0])) {
	$buff_ = json_decode(html_entity_decode($data___[0]['content']), true);
} else {
	$a = [
		"Tendances" => ["697ddb6f130d8", "6827bb6eb7ae4", "6827bb741f37d", "6827bb6f9f68a", "697a815297582", "697a48cc582db", "697b6bddc26db"],
		"Les plus regardés" => ["6827bb6fab190", "6827bb73970e6", "697a3a94bbb76", "6827bb795895f", "690d4390bf883", "68ebf13b6ae01", "68299571a783d"],
		"A Découvrir" => ["693ede727ceae", "697e47b21239a", "6829c5003d7dd", "697e29462e51c", "697d1025430f9", "697bb408a31df", "697a5e93f421e"],
		"Anime sombres / psychologiques" => ["68ebf13b6ae01", "697d386c3d496", "697d1efa1229c", "697d3232f30f9", "69011923b685f", "697a964993210", "697a644288a4b"]
	];
	foreach ($a as $key => $value) {
		foreach ($value as $v) {
			$obj = __EnnCatalogues->get($v, 'uniqid', [
					"genres" => true,
					"description" => true,
					"image" => ["poster"],
					"langues" => true,
			]);
			$buff_[$key][] = buildChannelItem_($obj);
		}
	}
	$buff_ = array_map(function ($item) {
	    return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
	}, $buff_);
	//__SessionCache->insert('homepage.genring', json_encode($buff_, JSON_UNESCAPED_UNICODE), 7);
}
$data___ = []; //__SessionCache->get('homepage.blog');
if(isset($data___[0])) {
	$buff__ = json_decode(html_entity_decode($data___[0]['content']), true);
} else {
	$allBlog = __OmsySql->execute(
		"SELECT * FROM omsyblog_single WHERE blog_group = ? OR blog_group = ? ORDER BY ID DESC LIMIT 4",
		['anime', 'animation']
	)->fetchAll();
	$allBlog2 = __OmsySql->execute(
		"SELECT * FROM omsyblog_single WHERE blog_group = ? OR blog_group = ? OR blog_group = ? ORDER BY ID DESC LIMIT 4",
		['realease-anime', 'new-saeson', 'top']
	)->fetchAll();

	$buff__ = [
		'Nos avis' => [],
		'Actualité' => [],
	];
	foreach ($allBlog as $key => $value) {
		$buff__['Nos avis'][] = [
			"slug" => $value['blog_slug'],
			"group" => $value['blog_group'],
			"title" => __OmsyString->substr(__OmsyString->htmlDecode($value['blog_title']), 90),
			"sub_title" => __OmsyMain->GFunction->convertDate($value['date_time']),
			"thumbnail" => $value['blog_min'],
			"publish_date" => $value['date_time']
		];
	}
	foreach ($allBlog2 as $key => $value) {
		$buff__['Actualité'][] = [
			"slug" => $value['blog_slug'],
			"group" => $value['blog_group'],
			"title" => __OmsyString->substr(__OmsyString->htmlDecode($value['blog_title']), 90),
			"sub_title" => __OmsyMain->GFunction->convertDate($value['date_time']),
			"thumbnail" => $value['blog_min'],
			"publish_date" => $value['date_time']
		];
	}

	__SessionCache->insert('homepage.blog', json_encode($buff__), 7);
}

$b = array_merge($buff_, $buff__);
__OmsyMain->GFunction->shuffle_assoc($b);
$buff = array_merge($buff, $b);

function langToText($id) {
	$langUpdate = [
		'french' => [
			'continueWatching' => 'Continuer à regarder',
			'newEpisodesAdded' => 'Nouveaux episodes',
			'mostWatched' => 'Les plus regarder',
			'favoriteWatching' => 'Vos favoris',
			'theClassic' => 'Les classics'
		],
		'english' => [
			'continueWatching' => 'Continue watching',
			'newEpisodesAdded' => 'New episodes added',
			'mostWatched' => 'Most watched',
			'favoriteWatching' => 'Your favorite',
			'theClassic' => 'The classic'
		]
	];

	$lang = '';
	if (!$lang) $lang = 'french';
	if (!isset($langUpdate[$lang])) $lang = 'french';
	if (isset($langUpdate[$lang][$id])) return $langUpdate[$lang][$id];
	return $id;
}
include 'interface/index.php';