<?php
function curl_crawler($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
	$html = curl_exec($ch);
	curl_close($ch);

	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML($html);
	libxml_clear_errors();

	$xpath = new DOMXPath($dom);
	return $xpath;
}

function crawl_panneau($xpath, $channel) {
	// =========================
	// TITRE (corrigé h2 -> h1 + XPath robuste)
	// =========================
	$nomNode = $xpath->query('//h1[contains(@class, "font-bold")]');
	$nom = ($nomNode->length > 0)
		? trim($nomNode->item(0)->textContent)
		: 'N/A';

	// =========================
	// DESCRIPTION (XPath plus robuste)
	// =========================
	$descriptionNode = $xpath->query('//p[contains(@class, "text-gray-300")]');
	$description = ($descriptionNode->length > 0)
		? trim($descriptionNode->item(0)->textContent)
		: 'N/A';

	// =========================
	// GENRES (évite trop strict sur les classes)
	// =========================
	$genres_nodes = $xpath->query('//span[contains(@class, "genre-pill")]');

	$genres = [];
	foreach ($genres_nodes as $genre) {
		$val = trim($genre->textContent);
		if ($val !== '') {
			$genres[] = $val;
		}
	}

	// =========================
	// SCRIPT DATA EXTRACTION
	// =========================
	$scripts = $xpath->query('//script');

	$buff = [
		"title" => $nom,
		"description" => $description,
		"genres" => $genres,
		"data" => []
	];

	foreach ($scripts as $script) {
		$contenu = $script->textContent;

		if (empty($contenu)) continue;

		preg_match_all(
			'/panneauAnime\(\s*["\']([^"\']+)["\']\s*,\s*["\']([^"\']+)["\']\s*\)/',
			$contenu,
			$matches,
			PREG_SET_ORDER
		);

		foreach ($matches as $match) {

			if (!isset($match[1], $match[2])) continue;

			$ex = explode('/', $match[2]);

			if (count($ex) < 2) continue;

			$buff['data'][] = [
				"name"   => $match[1],
				"url"	=> $match[2],
				"saison" => $ex[0] ?? null,
				"lang"   => $ex[1] ?? null,
			];
		}
	}

	// =========================
	// SQL SAFE CHECK
	// =========================
	$existe = __OmsySql->execute(
		'SELECT ID FROM as_crawler WHERE source = ? LIMIT 1',
		[$channel]
	)->fetchAll();

	$jsonData = json_encode($buff, JSON_UNESCAPED_UNICODE);

	if (empty($existe)) {
		__OmsySql->execute(
			'INSERT INTO as_crawler(source, source_type, catalogue, data, taille)
			 VALUES(?,?,?,?,?)',
			[$channel, "catalogue", $channel, $jsonData, count($buff["data"])]
		);
	} else {
		__OmsySql->execute(
			'UPDATE as_crawler SET data = ?, taille = ? WHERE ID = ?',
			[$jsonData, count($buff["data"]), $existe[0]['ID']]
		);
	}
}

function crawl_catalogue($xpath) {
	$query = '//div[@id="list_catalog"]//a';
	$liens = $xpath->query($query);

	foreach ($liens as $lien) {
		$href  = $lien->getAttribute('href');
		preg_match('/\/catalogue\/([^\/]+)/', $href, $matches);

		$existe = __OmsySql->execute(
			'SELECT * FROM as_crawler WHERE source = ?',
			[$matches[1]]
		)->fetchAll();
		if(isset($existe[0])) continue;
		echo "URL   : $matches[1]\n";
		echo "---\n";
		$xpath = curl_crawler($href);
		crawl_panneau($xpath, $matches[1]);
		sleep(2);
	}
}