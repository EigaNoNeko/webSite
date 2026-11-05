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
		'title'  => __OmsyString->htmlDecode(__OmsyString->substr($catalogueObj['title'] ?? '', $titleMaxLen)),
		'id'	 => $slug,
		'images' => $catalogueObj['image'] ?? getDefaultImages(),
		// 'images' => [
		// 	'poster' => "https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/$slug.jpg"
		// ]
	];
}

// ─── Init ────────────────────────────────────────────────────────────────────
$buff = [
	'continueWatching' => [],
	'newEpisodesAdded' => [],
	'favoriteWatching' => [],
];
$buff_ = [];

// ─── Session : surcharge depuis la BDD ──────────────────────────────────────
$viewTy	  = 'POST';
$channel_view = json_decode(htmlspecialchars_decode($_COOKIE['watch_channel'] ?? ''), true) ?? [];
if (isset($_SESSION['uniqid'])) {
	$userId = $_SESSION['uniqid'];

	$row = __OmsySql->execute(
		'SELECT content FROM omsy_account_obj WHERE user_id = ? AND name = "watch_channel" LIMIT 1',
		[$userId]
	)->fetchAll()[0] ?? null;

	$db_channel_view = [];

	if ($row) {
		$db_channel_view = json_decode(
			html_entity_decode(htmlspecialchars_decode($row['content'])),
			true
		) ?? [];
	}

	// Fusion + comparaison
	foreach ($channel_view as $channelId => $cookieData) {
		$cookieVideo = is_array($cookieData) ? ($cookieData['video'] ?? null) : $cookieData;
		$cookieDate  = is_array($cookieData) ? ($cookieData['date'] ?? 0) : 0;
		if (!$cookieVideo) continue;
		$dbData  = $db_channel_view[$channelId] ?? null;
		$dbDate  = is_array($dbData) ? ($dbData['date'] ?? 0) : 0;
		// Si la donnée cookie est plus récente, on remplace celle de la BDD
		if (!$dbData || $cookieDate >= $dbDate) {
			$db_channel_view[$channelId] = [
				'video' => $cookieVideo
			];
			if ($cookieDate > 0) {
				$db_channel_view[$channelId]['date'] = $cookieDate;
			}
		}
	}
	// Trier du plus récent au plus ancien, sans date à la fin
	uasort($db_channel_view, function ($a, $b) {
		$dateA = (int)($a['date'] ?? 0);
		$dateB = (int)($b['date'] ?? 0);
		if ($dateA === 0 && $dateB > 0) return 1;
		if ($dateB === 0 && $dateA > 0) return -1;
		return $dateB <=> $dateA;
	});
	$content = json_encode($db_channel_view);
	if ($row) {
		__OmsySql->execute(
			'UPDATE omsy_account_obj SET content = ? WHERE user_id = ? AND name = "watch_channel"',
			[$content, $userId]
		);
	} else {
		__OmsySql->execute(
			'INSERT INTO omsy_account_obj(user_id, name, content) VALUES(?,?,?)',
			[$userId, 'watch_channel', $content]
		);
	}
	$channel_view = $db_channel_view;
	$viewTy = 'ACCOUNT';
}
foreach ($channel_view as $channelId => $data) {
	$videoId = is_array($data) ? ($data['video'] ?? null) : $data;

	if (!$videoId) continue;
	$catalogue = __EnnCatalogues->get($channelId, "uniqid", [
		"object" => false,
		"genres" => false,
		"image" => ["thumbnail"],
		"statistics" => ['saison', 'view', 'list', 'episode']
	]);
	if (!$catalogue['uniqid']) continue;
	$player = __OmsySql->execute(
		'SELECT saison, episode FROM enncore_player WHERE uniqid = ?',
		[$videoId]
	)->fetchAll()[0] ?? null;
	if (!$player) continue;
	$buff['continueWatching'][] = [
		'title' => $catalogue['title'],
		'sub_title' => 'S' . $player['saison'] . ' E' . $player['episode'],
		'images'    => $catalogue['image'],
		'channel'   => $channelId,
		'video'     => $videoId,
		'max_episodes' => intval($catalogue['statistics']['episode']),
		'current_episodes' => intval($player['episode']),
		'date' => $data['date'] ?? null,
	];
}

// ─── Nouveaux épisodes ───────────────────────────────────────────────────────
$data___ = __SessionCache->get('homepage.newEpisodesAdded');
if(isset($data___[0])) {
	$buff['newEpisodesAdded'] = json_decode(html_entity_decode($data___[0]['content']), true);
} else {
	$recents = __OmsySql->execute(
		'SELECT uniqid FROM enncore_catalogues ORDER BY last_update DESC LIMIT 7'
	)->fetchAll();
	foreach ($recents as $row) {
		$obj = __EnnCatalogues->get($row['uniqid']);
		$buff['newEpisodesAdded'][] = buildChannelItem_($obj);
	}
	__SessionCache->insert('homepage.newEpisodesAdded', json_encode($buff['newEpisodesAdded']), 7);
}
// ─── Nouveaux épisodes ───────────────────────────────────────────────────────
$data___ = __SessionCache->get('homepage.genring');
if(isset($data___[0])) {
	$buff_ = json_decode(html_entity_decode($data___[0]['content']), true);
} else {
	$playlists = __OmsySql->execute(
		'SELECT autogen, uniqid, name FROM plug_playlist'
	)->fetchAll();
	foreach ($playlists as $playlist) {
		$key = $playlist['name'];

		$rows = __OmsySql->execute(
			'SELECT uniqid FROM enncore_catalogues WHERE genres LIKE ? ORDER BY RAND() LIMIT 7',
			['%' . $key . '%']
		)->fetchAll();

		if (count($rows) <= 3) continue;

		foreach ($rows as $row) {
			$obj = __EnnCatalogues->get($row['uniqid']);
			$buff_[$key][] = buildChannelItem_($obj);
		}
	}
	__SessionCache->insert('homepage.genring', json_encode($buff_), 7);
}
$buff = array_merge($buff, $buff_);

header('Content-Type: application/json');
echo json_encode($buff);