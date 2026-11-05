<?php
__OmsyMain->addModule('OmsyUrl');

set_time_limit(800);
$dataLenght = 0;

header('Content-type: text/plain');

$catalogue = $_POST['channel_id'];
$saison = $_POST['saison'] ?? 'saison1';
$lang = $_POST['lang'] ?? 'vf';

$seasonPath = $saison;
switch ($saison) {
    case 'kai1':
        $seasonPath = 'kai';
        break;

    case 'film':
        $seasonPath = 'film';
        break;

    case 'oav':
        $seasonPath = 'oav';
        break;

    default:
        $seasonPath = $saison;
}
$xpath = __OmsyUrl->request("https://anime-sama.to/catalogue/" . $catalogue . "/" . $seasonPath . '/' . $lang . '/episodes.js');

$channelId = '';
$existes = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$catalogue])->fetchAll();
if(!isset($existes[0])) {
	$channelId = uniqid();
	$exi = __OmsySql->execute('SELECT * FROM as_crawler WHERE source = ?', [$catalogue])->fetchAll();
	var_dump($exi);
	if(!isset($exi[0])) return __OmsyMain->api('POST', ['msg' => 'Error crawling '.$catalogue]);

	$exi[0]['data'] = json_decode(html_entity_decode($exi[0]['data']), true);

	$name = $exi[0]['data']['title'];
	$content = $exi[0]['data']['description'];
	$categorie = $exi[0]['data']['genres'][0];
	__EnnCatalogues->create($channelId, $catalogue, NULL, $categorie);
	__EnnCatalogues->addObj($channelId, "channel-title", 'french', $name);
	__EnnCatalogues->addObj($channelId, "channel-description", 'french', $content);

	$destination = 'share/content-delivry/image/poster-'.uniqid().'.jpg';
	$imageData = file_get_contents("https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/$catalogue.jpg");
	if ($imageData !== false) {
		file_put_contents(__Root . $destination, $imageData);
	}
} else {
	$channelId = $existes[0]['uniqid'];
}

$results = [];
if (preg_match_all('/var\s+([a-zA-Z0-9_]+)\s*=\s*\[(.*?)\];/s', $xpath, $matches, PREG_SET_ORDER)) {
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
$type = null;
$output_array = [];

if (preg_match('/\/saison([0-9]*)hs\//', '/'.$saison.'/', $output_array)) {
    $type = 'hs';
} else if (preg_match('/\/kai([0-9]*)\//', '/'.$saison.'/', $output_array)) {
    $type = 'kai';
} else if (preg_match('/\/film([0-9]*)\//', '/'.$saison.'/', $output_array)) {
    $type = 'film';
} else if (preg_match('/\/oav([0-9]*)\//', '/'.$saison.'/', $output_array)) {
    $type = 'oav';
} else if (preg_match('/\/saison([0-9]*)\//', '/'.$saison.'/', $output_array)) {
    $type = null;
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
			'saison' => !empty($output_array[1]) ? (int)$output_array[1] : 1,
			'episode' => $ui,
			'type' => $type,

			'lang' => $lang,

			'video' => $value,
			'channel' => $channelId
		];
		if(isset($insert['type'])) {
			$existe = __OmsySql->execute(
				'SELECT * FROM enncore_players WHERE catalogue_id = ? AND episode = ? AND saison = ? AND type = ?',
				[$insert['channel'], $insert['episode'], $insert['saison'], $insert['type']]
			)->fetchAll()[0] ?? [];
		} else {
			$existe = __OmsySql->execute(
				'SELECT * FROM enncore_players WHERE catalogue_id = ? AND episode = ? AND saison = ? AND type IS NULL',
				[$insert['channel'], $insert['episode'], $insert['saison']]
			)->fetchAll()[0] ?? [];
		}

		if(isset($existe['uniqid'])) {
			$existe['src'] = json_decode(html_entity_decode($existe['src']), true);
			$existe['lang_'] = json_decode(html_entity_decode($existe['lang_']), true);

			$existe['lang_'][] = $insert['lang'];
			$existe['src'][] = [
				'src' => $insert['video'],
				'lang' => $insert['lang'],
			];

			$ex = false;
			foreach ($existe['src'] as $item) {
				if ($item['src'] == $insert['video']) {
					$ex = true;
					break;
				}
			}
			if(!$ex) {
				$existe['src'][] = [
					'src'  => $insert['video'],
					'lang' => $insert['lang'],
				];
				$dataLenght++;
			}

			__OmsySql->execute(
				'UPDATE enncore_players SET src = ?, lang_ = ? WHERE uniqid = ?',
				[json_encode($existe['src']), json_encode($existe['lang_']), $existe['uniqid']]
			);
		} else {
			__OmsySql->execute(
				'INSERT INTO enncore_players(uniqid, catalogue_id, title, episode, saison, type, lang_, src) VALUES(?, ?, ?, ?, ?, ?, ?, ?)',
				[$insert['uniqid'], $insert['channel'], "Episode $ui", $insert['episode'], $insert['saison'], $insert['type'], json_encode([$insert['lang']]), json_encode([['src' => $insert['video'], 'lang' => $insert['lang']]])]
			);
			$dataLenght++;
		}
		$i++;
	}
}

__OmsyMain->api('POST', null, ['update_item_length' => $dataLenght]);