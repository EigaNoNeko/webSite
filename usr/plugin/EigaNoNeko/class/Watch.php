<?php
class Watch {
	private $isAuth = true;

	private $langPriority   = ['vf', 'vf1', 'vf2', 'vostfr', 've', 'vo', 'vjst', 'vosten', 'vosta'];
	private $sourcePriority = ['sibnet.ru', 'vidmoly.to'];

	private $mapping = [
        "vo"   => "Doublage original",
        "vj"   => "Doublage japonais",
        "vf"   => "Doublage français",
        "ve"   => "Doublage anglais",
        "va"   => "Doublage anglais",

        "stfr" => "Sous-titrage français",
        "sten" => "Sous-titrage anglais",
        "sta"  => "Sous-titrage anglais",
    ];

	function __construct($OmsyMain) {
		define('__EnnWatch', $this);
	}

	public function langToLitleString($lang) {
		$hasVF = str_contains($lang, 'vf');
		$hasVOST = str_contains($lang, 'vost') || str_contains($lang, 'st');

		if($hasVF && $hasVOST):
			return "Sous-titrage | Doublage";
		elseif($hasVF):
			return "Doublage";
		elseif($hasVOST):
			return "Sous-titrage";
		endif;
	}

	private function genItem($obj) {
		return [
			"uniqid" => $obj['uniqid'] ?? null,
			"slug" => $obj['slug'] ?? null,
			"title" => $obj['title'] ?? null,
			"description" => $obj['description'] ?? null,
			"image" => [
				"poster" => $obj['image']['poster'] ?? null,
				"thumbnail" => $obj['image']['thumbnail'] ?? null,
				"hero" => $obj['image']['hero'] ?? null,
				"logo" => $obj['image']['logo'] ?? null
			],
			"last_update" => $obj['last_update'] ?? null,
			"video" => $obj['video'] ?? null,
		];
	}
	
	public function get($uuid, $saison = 1) {
		$rowSaison = 0;
		$buff = [];
		$videoChannel = __OmsySql->execute(
			"SELECT * FROM enncore_players WHERE catalogue_id = ? AND saison = ? AND type IS NULL ORDER BY episode ASC",
			[$uuid, $saison],
		)->fetchAll();
		foreach ($videoChannel as $value):
			$buff[$saison][$value["episode"]] = [
				"id" => $value["uniqid"],
				"title" => 'Episode '.$value["episode"],
				"saison" => $value["saison"],
				"episode" => $value["episode"],
				"lang" => implode(', ', array_unique(json_decode(htmlspecialchars_decode($value["lang_"])))),
			];
		endforeach;

		return $buff[$saison] ?? [];
	}
	public function getHorsSerie($uuid, $saison = 1) {
		$rowSaison = 0;
		$buff = [];
		$videoChannel = __OmsySql->execute(
			"SELECT * FROM enncore_players WHERE catalogue_id = ? AND saison = ? AND type IS NOT NULL ORDER BY episode ASC",
			[$uuid, $saison],
		)->fetchAll();
		foreach ($videoChannel as $value):
			$buff[$value["type"]][$saison][$value["episode"]] = [
				"id" => $value["uniqid"],
				"title" => 'Episode '.$value["episode"],
				"saison" => $value["saison"],
				"episode" => $value["episode"],
				"lang" => implode(', ', array_unique(json_decode(htmlspecialchars_decode($value["lang_"])))),
			];
		endforeach;

		return $buff ?? [];
	}
	public function count($uuid) {
		$result = __OmsySql->execute(
			"SELECT 
				COUNT(DISTINCT saison) AS saisons,
				COUNT(DISTINCT CONCAT(saison, '-', episode)) AS episodes
			FROM enncore_players
			WHERE catalogue_id = ?
			AND saison > 0
			AND episode > 0
			AND type IS NULL",
			[$uuid]
		)->fetchAll()[0] ?? [];

		return [
			"episode" => (int) $result['episodes'] ?? 0,
			"saison"  => (int) $result['saisons'] ?? 0,
		];
	}
	public function langues($catalogue) {
		return json_decode(
			html_entity_decode(__OmsySql->execute(
				"SELECT lang_ FROM enncore_players WHERE catalogue_id = ? LIMIT 1",
				[$catalogue]
			)->fetchAll()[0]['lang_'] ?? '[]'),
			true
		);
	}


	public function langToString($l) {
	    // Normalisation : vf1, vf2, ve1, vo2... → vf, ve, vo...
	    $l = preg_replace('/^(v[a-z]+)\d+$/i', '$1', $l);

	    // Correspondance exacte
	    if (isset($this->mapping[$l])) return $this->mapping[$l];

	    // Décomposition : ex "vostfr" → "vo" + "stfr"
	    $prefixes = ["vo", "vj", "vf", "ve", "va"];
	    $suffixes = ["stfr", "sten", "sta"];

	    foreach ($prefixes as $prefix) {
	        foreach ($suffixes as $suffix) {
	            if ($l === $prefix . $suffix) {
	                return $this->mapping[$prefix] . ' | ' . $this->mapping[$suffix];
	            }
	        }
	    }

	    return $l;
	}
	public function getSourceScore($src) {
		foreach ($this->sourcePriority as $index => $domain) {
			if (stripos($src, $domain) !== false) {
				return $index;
			}
		}
		return count($this->sourcePriority);
	}
	public function getLangScore($lang) {
		$pos = array_search($lang, $this->langPriority);
		return $pos !== false ? $pos : count($this->langPriority);
	}
	public function backVideo($uniqid, $episode, $saison, $type) {
		$back = __OmsySql->execute(
			'SELECT episode, saison FROM enncore_players WHERE catalogue_id = ? AND episode = ? AND saison = ?',
			[$uniqid, ($episode-1), ($saison)]
		)->fetchAll()[0] ?? NULL;

		if(!isset($back)) $back = __OmsySql->execute(
			'SELECT episode, saison FROM enncore_players WHERE catalogue_id = ? AND saison = ? ORDER BY episode DESC',
			[$uniqid, ($saison-1)]
		)->fetchAll()[0] ?? NULL;

		return $back;
	}
	public function nextVideo($uniqid, $episode, $saison, $type) {
		$next = __OmsySql->execute(
			'SELECT episode, saison FROM enncore_players WHERE catalogue_id = ? AND episode = ? AND saison = ?',
			[$uniqid, ($episode+1), ($saison)]
		)->fetchAll()[0] ?? NULL;

		if(!isset($next)) $next = __OmsySql->execute(
			'SELECT episode, saison FROM enncore_players WHERE catalogue_id = ? AND episode = ? AND saison = ?',
			[$uniqid, (1), ($saison+1)]
		)->fetchAll()[0] ?? NULL;

		return $next;
	}
}