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
	$nom = $xpath->query('//h4[@id="titreOeuvre"]');
	$nom = $nom->length > 0 ? trim($nom->item(0)->textContent) : 'N/A';

	$description = $xpath->query('//p[
	    contains(concat(" ", normalize-space(@class), " "), " text-sm ") and
	    contains(concat(" ", normalize-space(@class), " "), " text-gray-300 ") and
	    contains(concat(" ", normalize-space(@class), " "), " leading-relaxed ")
	]');
	$description = $description->length > 0 ? trim($description->item(0)->textContent) : 'N/A';

	$genres_nodes = $xpath->query('//a[
	    contains(concat(" ", normalize-space(@class), " "), " text-sm ") and
	    contains(concat(" ", normalize-space(@class), " "), " text-gray-300 ")
	]');
	$genres = [];
	foreach ($genres_nodes as $genre) {
	    $genres[] = trim($genre->textContent);
	}



	// $channel = "one-piece";
	$scripts = $xpath->query('//script');
	$buff = [
		"title" => $nom,
		"description" => $description,
		"genres" => $genres,

		"data" => []
	];
	foreach ($scripts as $script) {
		$contenu = $script->textContent;
		preg_match_all('/panneauAnime\(\s*["\']([^"\']+)["\']\s*,\s*["\']([^"\']+)["\']\s*\)/', $contenu, $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
			$ex = explode('/', $match[2]);
			if(!isset($ex[1])) continue;
			$buff['data'][] = [
				"name" => $match[1],
				"url" => $match[2],

				"saison" => $ex[0],
				"lang" => $ex[1] ?? null,
			];
		}
	}
	$existe = __OmsySql->execute(
		'SELECT * FROM as_crawler WHERE source = ?',
		[$channel]
	)->fetchAll();

	if(!isset($existe[0])) {
		__OmsySql->execute(
			'INSERT INTO as_crawler(source, source_type, catalogue, data, taille) VALUES(?,?,?,?,?)',
			[$channel, "catalogue", $channel, json_encode($buff), count($buff["data"])]
		);
	} else {
		__OmsySql->execute(
			'UPDATE as_crawler SET data = ?, taille = ? WHERE ID = ?',
			[json_encode($buff), count($buff["data"]), $existe[0]['ID']]
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