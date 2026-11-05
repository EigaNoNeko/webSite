<?php
$buffMlp = [
	"68efcd9e96e00" => [
		"src" => [
			"vf" => "https://mlpfr.ponies.fr/marco/StarVF(EP)-(S)_480p.mp4",
			"vo" => "https://mlpfr.ponies.fr/marco/StarVO(EP)-(S)_480p.mp4",
		],
		"saison" => [
			13,
			22,
			21,
			21,
		]
	],
	"68efcd9e96e00_" => [
		"src" => [
			"vf" => "https://mlpfr.ponies.fr/sunil/VFLPS(EP)-(S)_480p.mp4",
			"vo" => "https://mlpfr.ponies.fr/sunil/VOLPS(EP)-(S)_480p.mp4",
		],
		"saison" => [
			26,
			26,
			26,
			26,
		]
	]
];

header('content-type: text/plain');
foreach ($buffMlp as $catalogueID => $catalogue) {
	$srcTemplates = $catalogue['src'];
	$saisons = $catalogue['saison'];

	foreach ($saisons as $saisonIndex => $episodeCount) {
		$saisonNum = $saisonIndex + 1;

		for ($ep = 1; $ep <= $episodeCount; $ep++) {
			$epFormatted = str_pad($ep, 2, '0', STR_PAD_LEFT);

			$sources = [];
			foreach ($srcTemplates as $lang => $template) {
				$url = str_replace(['(EP)', '(S)'], [$epFormatted, $saisonNum], $template);
				$sources[] = ['src' => $url, 'lang' => $lang];
			}

			$data = [
				uniqid(),
				$catalogueID,
				"Episode $ep",
				$ep,
				$saisonIndex,
				json_encode(array_keys($srcTemplates)),
				json_encode($sources),
			];

			var_dump($data);
		}
	}
}