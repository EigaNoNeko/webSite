<?php
class Catalogues {
	private $buffWatched = "buff_watched";

	function __construct($OmsyMain) {
		define("__EnnCatalogues", $this);
	}

	/* Admin function */
	public function rmObj($string, $uid) {
		__OmsySql->execute(
			'DELETE FROM enncore_catalogue_obj WHERE type = ? AND channel_id = ?',
			[$string, $uid],
		);
	}
	public function addObj($uid, $type, $value, $content) {
		__OmsySql->execute(
			'INSERT INTO enncore_catalogue_obj ( channel_id, type, name, content ) VALUES(?, ?, ?, ?)',
			[$uid, $type, $value, $content],
		);
	}
	public function create($uid, $slugify, $parent, $tags) {
		__OmsySql->execute(
			"INSERT INTO
				enncore_catalogues (uniqid,
					slug_,
					parent_,
					genres,
					total_view,
					total_saison,
					total_episode,

					added_date,
					last_update
				)
				VALUES (?, ?, ?, ?, ?, ?, ?, now(), now())
			",
			[$uid, $slugify, $parent, $tags, 0, 0, 0],
		);

		return true;
	}
	public function updateSlug($string, $uid) {
		__OmsySql->execute(
			"UPDATE enncore_catalogues SET slug_ = ? WHERE uniqid = ?",
			[$string, $uid],
		);

		return true;
	}
	public function updateTags($string, $uid) {
		__OmsySql->execute(
			"UPDATE enncore_catalogues SET genres = ? WHERE uniqid = ?",
			[$string, $uid],
		);

		return true;
	}
	public function updateParent($string, $uid) {
		__OmsySql->execute(
			"UPDATE enncore_catalogues SET parent_ = ? WHERE uniqid = ?",
			[$string, $uid],
		);

		return true;
	}

	/* Recuperration d'information */
	public function getObject($catalogueId) {
		$buff = [];

		$channelImgSQL = __OmsySql->execute(
			'SELECT * FROM enncore_catalogue_obj WHERE channel_id = ? AND content != "" ORDER BY ID ASC',
			[$catalogueId],
		)->fetchAll();
		foreach ($channelImgSQL as $key => $value) $buff[$value["type"]][$value["name"]] = $value["content"];

		$channelImgSQL = __OmsySql->execute(
			'SELECT * FROM omsy_object WHERE object_name = ? AND object_content != "" ORDER BY ID ASC',
			[$catalogueId],
		)->fetchAll();
		foreach ($channelImgSQL as $key => $value) $buff[$value["object_type"]][] = $value["object_content"];

		return $buff;
	}
	public function get($string, $idType = "uniqid", $obj = null) {
		if(!isset($obj)) {
			$obj = [
				"description" => true
			];
		}
		$recent = __OmsySql->execute("SELECT * FROM enncore_catalogues WHERE $idType = ?", [$string])->fetchAll();
		if(!isset($recent[0])) return null;

		$object = $this->getObject($recent[0]['uniqid']);
		$buff = [
			"uniqid" => $recent[0]['uniqid'],
			"slug" => $recent[0]['slug_'],

			"title" => __EnnMain->getLangue($object, 'channel-title') ?? null
		];

		if(isset($obj['staff'])) $buff['staff'] = json_decode(htmlspecialchars_decode($recent[0]['staff'] ?? '[]'), true);
		if(isset($obj['added_date'])) $buff['added_date'] = $recent[0]['added_date'];
		if(isset($obj['status'])) $buff['status'] = $recent[0]['status'] ?? null; 
		if(isset($obj['streaming'])) $buff['streaming'] = $recent[0]['streaming'] ?? null; 

		if(isset($obj['object'])) {
			$buff['object'] = $object;
		}

		if(isset($obj['description'])) $buff['description'] = __EnnMain->getLangue($object, 'channel-description') ?? null;
		if(isset($obj['genres'])){
			if(str_contains(($recent[0]['genres'] ?? ''), ',')) $recent[0]['genres'] = explode(',', $recent[0]['genres'] ?? '');
			elseif(str_contains(($recent[0]['genres'] ?? ''), '-')) $recent[0]['genres'] = explode('-', $recent[0]['genres'] ?? '');
			else $recent[0]['genres'] = [$recent[0]['genres']];
			$buff['genres'] = $recent[0]['genres'];
		}

		if(isset($obj['data'])) {
			$buff['data'] = [];
			if(in_array('episodes', $obj['data'])) $buff['data']['episodes'] = __EnnWatch->get($recent[0]['uniqid'], $obj['currentSaison'] ?? 1);
			if(in_array('episodes-hs', $obj['data'])) $buff['data']['episodes-hs'] = __EnnWatch->getHorsSerie($recent[0]['uniqid'], $obj['currentSaison'] ?? 1);
		}

		if(isset($obj['statistics'])) {
			$buff['statistics'] = [];
			if(in_array('episode', $obj['statistics']) || in_array('saison', $obj['statistics'])) {
				$counting = __EnnWatch->count($recent[0]['uniqid']);

				$buff['statistics']['episode'] = $counting['episode'];
				$buff['statistics']['saison'] = $counting['saison'];
			}
		}

		if(isset($obj['langues'])) {
			$langs = __EnnWatch->langues($recent[0]['uniqid']);

			$buff['langues'] = implode(
				', ',
				array_unique(is_array($langs) ? $langs : [])
			);
		}
		$newValue = $this->genItem($buff);
		return $newValue;
	}
	private function genItem($obj) {
		if(isset($obj['title'])) $obj['title'] = __OmsyString->htmlDecode($obj['title']);
		return [
			"added_date" => $obj['added_date'] ?? null,
			"uniqid" => $obj['uniqid'] ?? null,
			"status" => $obj['status'] ?? null,
			"slug" => $obj['slug'] ?? null,
			"title" => $obj['title'] ?? null,
			"description" => $obj['description'] ?? null,
			"genres" => $obj['genres'] ?? null,
			"object" => $obj['object'] ?? null,
			"langues" => $obj['langues'] ?? null,
			"staff" => $obj['staff'] ?? null,
			"last_update" => $obj['last_update'] ?? null,
			"streaming" => $obj['streaming'] ?? null,

			"data" => [
				"episodes" => $obj['data']['episodes'] ?? null,
				"episodes-hs" => $obj['data']['episodes-hs'] ?? null,
				"peoples" => $obj['data']['peoples'] ?? null,
				"characters" => $obj['data']['characters'] ?? null,
				"article" => $obj['data']['article'] ?? null,
			],
			"statistics" => [
				"saison" => $obj['statistics']['saison'] ?? 0,
				"episode" => $obj['statistics']['episode'] ?? 0,
				"view" => $obj['statistics']['view'] ?? 0,
				"list" => $obj['statistics']['list'] ?? 0
			]
		];
	}

	/* ... */
	public function getLastUpdated() {
		$buff = [];
		$recent = __OmsySql->execute("SELECT * FROM enncore_catalogues ORDER BY last_update DESC LIMIT 7");
		foreach ($recent->fetchAll() as $value) {
			$object = $this->getObject($value['uniqid']);

			$newValue = $this->genItem([
				"uniqid" => $value['uniqid'],
				"slug" => $value['slug_'],

				"title" => __EnnMain->getLangue($object, 'channel-title') ?? null,
				"description" => __EnnMain->getLangue($object, 'channel-description') ?? null,

				"image" => [
					"poster" => $object['img']['poster'] ?? null,
					"thumbnail" => $object['img']['thumbnail'] ?? null,
					"banner" => $object['img']['banner'] ?? null,
					"logo" => $object['img']['logo'] ?? null
				],

				"last_update" => $value['last_update']
			]);

			$buff[] = $newValue;
		}
		return $buff;
	}
	/* public function getAllByCategorie($max = 7) {
		$buffA = [];
		$buffId = [];
		foreach (__OmsySql->execute('SELECT * FROM enncore_categorie')->fetchAll() as $value) {
			$omsyCatalogObj = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE genres LIKE "%'.$value['name'].'%" LIMIT 7')->fetchAll();
			if(count($omsyCatalogObj) < 7) continue;
			$buff = [];
			foreach ($omsyCatalogObj as $getItem) {
				if(in_array($getItem['uniqid'], $buffId)) continue;

				$object = $this->getObject($getItem['uniqid']);

				$newValue = $this->genItem([
					"uniqid" => $getItem['uniqid'],
					"slug" => $getItem['slug_'],

					"title" => __EnnMain->getLangue($object, 'channel-title') ?? null,
					"description" => __EnnMain->getLangue($object, 'channel-description') ?? null,

					"image" => [
						"poster" => $object['img']['poster'] ?? null,
						"thumbnail" => $object['img']['thumbnail'] ?? null,
						"banner" => $object['img']['banner'] ?? null,
						"logo" => $object['img']['logo'] ?? null
					]
				]);

				$buffId[] = $newValue['uniqid'];
				$buff[] = $newValue;
			}

			if(count($buff) < 7) continue;

			$buffA[$value['name']] = $buff;
		}

		return $buffA;
	} */
	public function getContinueWatched() {
		$cook = [];
		if(isset($_COOKIE[$this->buffWatched])) {
			$cook = json_decode($_COOKIE[$this->buffWatched]);
			__OmsyAccounts->insertObj($this->buffWatched, json_encode($cook));
		}
		$getCook = __OmsyAccounts->getObj($this->buffWatched);
		if(isset($getCook[0]['content'])) $cook = json_decode(__OmsyString->htmlDecode($getCook[0]['content'])) ?? [];
		$buffHis = [];
		$i=0;
		foreach ($cook as $key => $value) {
			$ca = $this->get($key);
			if(!isset($ca)) continue;
			if($i > 6) continue;

			$ca['video'] = $value;
			$buffHis[] = $ca;
			$i++;
		}
		return $buffHis;
	}
	public function research_($query, $limit = 6) {
		$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
		if ($page < 1) $page = 1;
	 
		// ──────────────────────────────────────────────────────
		//  1. RÉCUPÉRATION  — tous les docs qui matchent la requête
		//	 On ramène le contenu + le rank (PageRank stocké en base)
		// ──────────────────────────────────────────────────────
		$querySearch = "%" . $query . "%";
	 
		$rows = __OmsySql->execute(
			'SELECT catalogue_id, query_search, search_rank
			 FROM enncore_search
			 WHERE query_search LIKE ?',
			[$querySearch],
		)->fetchAll();
	 
		if (empty($rows)) {
			return [
				"meta"	   => ["data_type" => "catalogue"],
				"pagination" => [
					"current_page" => $page,
					"max_page"	 => 0,
					"items"		=> ["count" => 0, "total" => 0, "per_page" => $limit],
				],
				"data" => [],
			];
		}
	 
		// ──────────────────────────────────────────────────────
		//  2. TOKENISATION
		//	 Minuscules, sans ponctuation, sans stop words
		// ──────────────────────────────────────────────────────
		$stop_words = [
			'le','la','les','de','des','du','un','une','et','en','est','pour',
			'par','sur','dans','avec','qui','que','à','au','aux','il','elle',
			'ils','elles','on','ou','ne','pas','se','ce','sa','a','an','the',
			'of','in','to','is','and','or','that','this','it','as','at','be',
		];
	 
		$tokenize = function (string $text) use ($stop_words): array {
			$text  = mb_strtolower($text);
			$text  = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
			$words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
			return array_values(array_filter(
				$words,
				fn($w) => !in_array($w, $stop_words) && mb_strlen($w) > 1
			));
		};
	 
		// ──────────────────────────────────────────────────────
		//  3. TF — Term Frequency par document
		//	 TF(mot, doc) = occurrences / total mots du doc
		// ──────────────────────────────────────────────────────
		$query_tokens  = $tokenize($query);
		$N			 = count($rows);
		$all_tf		= [];   // [ catalogue_id => [ mot => tf ] ]
		$doc_freq	  = [];   // [ mot => nb de docs qui le contiennent ]
	 
		foreach ($rows as $row) {
			$tokens  = $tokenize($row['query_search']);
			$total   = count($tokens);
			$counts  = array_count_values($tokens);
			$tf	  = [];
	 
			foreach ($counts as $word => $count) {
				$tf[$word] = $total > 0 ? $count / $total : 0;
			}
	 
			$all_tf[$row['catalogue_id']] = $tf;
	 
			foreach (array_unique($tokens) as $word) {
				$doc_freq[$word] = ($doc_freq[$word] ?? 0) + 1;
			}
		}
	 
		// ──────────────────────────────────────────────────────
		//  4. IDF — Inverse Document Frequency (global au corpus)
		//	 IDF(mot) = log( N / nb_docs_contenant_le_mot )
		//	 → les mots rares sont plus discriminants
		// ──────────────────────────────────────────────────────
		$idf = [];
		foreach ($doc_freq as $word => $df) {
			$idf[$word] = log($N / $df);
		}
	 
		// ──────────────────────────────────────────────────────
		//  5. SCORE FINAL
		//	 relevance  = Σ TF-IDF pour chaque token de la requête
		//	 pagerank   = valeur normalisée du champ `rank` en base
		//	 score	  = relevance × (1 + 0.3 × pagerank)
		//
		//	 Le PageRank booste les résultats populaires sans écraser
		//	 la pertinence textuelle (coefficient 0.3 ajustable)
		// ──────────────────────────────────────────────────────
		$max_rank = max(array_column($rows, 'search_rank')) ?: 1; // pour normaliser
	 
		$scored = [];
		foreach ($rows as $row) {
			$cid	   = $row['catalogue_id'];
			$tf		= $all_tf[$cid] ?? [];
			$pagerank  = $row['search_rank'] / $max_rank; // normalisé entre 0 et 1
	 
			$relevance = 0;
			foreach ($query_tokens as $token) {
				$relevance += ($tf[$token] ?? 0) * ($idf[$token] ?? 0);
			}
	 
			$scored[$cid] = $relevance * (1 + 0.3 * $pagerank);
		}
	 
		// ──────────────────────────────────────────────────────
		//  6. TRI + PAGINATION
		//	 Les meilleurs scores remontent en premier
		// ──────────────────────────────────────────────────────
		arsort($scored);
	 
		$total_items  = count($scored);
		$pagesTotales = (int) ceil($total_items / $limit);
		$offset	   = ($page - 1) * $limit;
		$slice		= array_slice($scored, $offset, $limit, true);
	 
		// ──────────────────────────────────────────────────────
		//  7. HYDRATATION  — charge les données complètes de chaque résultat
		// ──────────────────────────────────────────────────────
		$datas = [];
		foreach ($slice as $cid => $score) {
			$d = $this->get($cid, 'uniqid', ["image" => ["poster"], "langues" => true, "description" => true]);
			if (!isset($d["slug"])) continue;
			$datas[$cid] = $d;
			// Optionnel : exposer le score pour debug
			// $datas[$cid]["_score"] = round($score, 6);
		}
	 
		return [
			"meta"	   => ["data_type" => "catalogue"],
			"pagination" => [
				"current_page" => $page,
				"max_page"	 => $pagesTotales,
				"items"		=> [
					"count"	=> count($datas),
					"total"	=> $total_items,
					"per_page" => $limit,
				],
			],
			"data" => $datas,
		];
	}

	/* Es encore utilisé ??? */
	public function add( $slug, $parent, $genres, $uniqid = null, $date = null, $last = null) {
		if (!isset($uniqid)) $uniqid = uniqid();

		if (isset(__OmsySql->execute("SELECT * FROM enncore_catalogues WHERE uniqid = ?" [$uniqid])->fetchAll()[0])) {
			return null;
		}

		__OmsySql->execute(
			"INSERT INTO
			enncore_catalogues(uniqid, slug_, parent_, genres, total_view, total_saison, total_episode, added_date, last_update)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
			[$uniqid, $slug, $parent, $genres, 0, 0, 0, $date, $last],
		);

		var_dump($uniqid, $slug, $parent, $genres, 0, 0, $date, $last);

		return $uniqid;
	}
	public function searchByCategorie($id, $limit = 6) {
		$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
		if ($page < 1) $page = 1;

		$querySearch = "%" . htmlentities($id) . "%";

		$allCat = __OmsySql->execute(
			"SELECT uniqid FROM enncore_catalogues WHERE genres LIKE ?",
			[$querySearch],
		)->fetchAll();

		$pagesTotales = ceil(count($allCat) / $limit);
		$offset = ($page - 1) * $limit;

		$catalogues = __OmsySql->execute(
			"SELECT uniqid FROM enncore_catalogues WHERE genres LIKE ? LIMIT ".$offset.", ".$limit,
			[$querySearch],
		)->fetchAll();

		foreach ($catalogues as $k => $value) {
			$d = $this->get($value['uniqid']);
			$catalogues[$k] = $d;
			//if (isset($d["slug"])) $catalogues[$k] = $d;
		}

		return [
			"meta" => [
				"data_type" => "catalogue",
			],
			"pagination" => [
				"current_page" => $page,
				"max_page" => $pagesTotales,
				"items" => [
					"count" => count($catalogues),
					"total" => count($allCat),
					"per_page" => $limit,
				],
			],
			"data" => $catalogues,
		];
	}
	public function research($query, $limit = 6) {
		$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
		if ($page < 1) $page = 1;

		$datas = [];
		$querySearch = "%".$query."%";

		$buffCatalogId = [];
		$ennCatalogId = __OmsySql->execute(
			'SELECT uniqid FROM enncore_catalogues WHERE genres LIKE ?',
			[$querySearch],
		)->fetchAll();
		foreach ($ennCatalogId as $key => $value) $buffCatalogId[$value["uniqid"]] = $value["uniqid"];

		$ennCatalogObj = __OmsySql->execute(
			'SELECT * FROM enncore_catalogue_obj WHERE (type = "channel-title") AND content LIKE ? ORDER BY content',
			[$querySearch],
		)->fetchAll();
		foreach ($ennCatalogObj as $key => $value) $buffCatalogId[$value["channel_id"]] = $value["channel_id"];

		// $omsyCatalogObj = __OmsySql->execute(
		// 	'SELECT * FROM omsy_object WHERE (object_type = "channel-title") AND object_content LIKE ? ORDER BY content',
		// 	[$querySearch],
		// )->fetchAll();
		//foreach ($omsyCatalogObj as $key => $value) $buffCatalogId[$value["channel_id"]] = $value["channel_id"];
		
		$pagesTotales = ceil(count($buffCatalogId) / $limit);
		$offset = ($page - 1) * $limit;
		$slice = array_slice($buffCatalogId, $offset, $limit);

		foreach ($slice as $value) {
			$d = $this->get($value, 'uniqid', [
				"image" => ["poster"]
			]);
			if(!isset($d["slug"])) continue;

			$datas[$value] = $d;
		}

		return [
			"meta" => [
				"data_type" => "catalogue",
			],
			"pagination" => [
				"current_page" => $page,
				"max_page" => $pagesTotales,
				"items" => [
					"count" => count($datas),
					"total" => count($buffCatalogId),
					"per_page" => $limit,
				],
			],
			"data" => $datas,
		];
	}
}