<?php
class ennNode {
	public function __construct($omsymain) { }
	public $keyToText = [
        'vo' => "Version original",
        'vf' => "Version française",
        've' => "Version anglaise",

        'vjst' => "Version japonaise",
        'vostfr' => "Version original - fr",
        'vosten' => "Version original - en",
    ];

    public $langUpdate = [
        'french' => [
            'continueWatching' => "Continuer à regarder",
            'newEpisodesAdded' => "Nouveaux episodes",
            'mostWatched' => "Les plus regarder",
            'theClassic' => "Les classics"
        ],
        'english' => [
            'continueWatching' => "Continue watching",
            'newEpisodesAdded' => "New episodes added",
            'mostWatched' => "Most watched",
            'theClassic' => "The classic"
        ]
    ];

    public function langVideo($lang) {
        $l = ['vf', 'fr', 'vostfr'];
        switch($lang) {
            case 'french':
                $l = ['vf', 'fr', 'vostfr'];
                break;
            case 'english':
                $l = ['ve', 'en', 'vosta'];
                break;
        }

        return $l;
    }

    public function apiSync() {
    	__OmsySql->execute('DELETE FROM enncore_catalogue');
    	__OmsySql->execute('DELETE FROM enncore_catalogue_obj');
    	__OmsySql->execute('DELETE FROM enncore_player');
    	__OmsySql->execute('DELETE FROM enncore_player_subtitle');

    	__OmsySql->execute('DELETE FROM omsy_account');

    	$allAccount = __OmsySql->execute('SELECT * FROM account')->fetchAll();
    	foreach ($allAccount as $key => $value) {
    		var_dump($value);

    		__OmsySql->execute(
    			'INSERT INTO omsy_account (uniqid, username, password, role, register_date) VALUES(?, ?, ?, ?, ?)',
    			[$value['uniqid'], $value['username'], $value['password'], intval($value['role']), $value['register_date']]
    		);
    	}

    	$oldChannel = __OmsySql->execute('SELECT * FROM pvs_channel')->fetchAll();

    	foreach ($oldChannel as $key => $value) {
    		$uniqid = uniqid();

    		$oldWatch = __OmsySql->execute('SELECT * FROM pvs_watch WHERE channel_id = ?', [$value['uniqid']])->fetchAll();
    		foreach ($oldWatch as $v) {
    			$oldWatchSubtitle = __OmsySql->execute('SELECT * FROM enncore_player_subtitle WHERE player_id = ?', [$v['uniqid']])->fetchAll();
	    		foreach ($oldWatchSubtitle as $vv) {
	    			__OmsySql->execute(
						"INSERT INTO
							enncore_player_subtitle (uniqid, watch_id, lang, file)
							VALUES (?, ?, ?, ?)
						",
						[
							$vv['uniqid'],
							$vv['watch_id'],
							$vv['lang'],
							$vv['file'],
						]
					);
	    		}


    			__OmsySql->execute(
					"INSERT INTO
						enncore_player (uniqid, catalogue_id, thumbnail, title, episode, saison, src, mime, quality, lang_)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
					",
					[
						$v['uniqid'],
						$uniqid,
						$v['thumbnail'],
						'Episode '.$v['title'],
						$v['title'],
						$v['cannal'],
						$v['src'],
						$v['mime'],
						$v['quality'],
						$v['lang_'],
					]
				);
    		}

    		$oldChannelObj = __OmsySql->execute('SELECT * FROM pvs_channel_obj WHERE channel_id = ?', [$value['uniqid']])->fetchAll();
    		foreach ($oldChannelObj as $v) {
    			__OmsySql->execute(
					"INSERT INTO
						enncore_catalogue_obj (channel_id, type, name, content)
						VALUES (?, ?, ?, ?)
					",
					[
						$uniqid,
						$v['type'],
						$v['name'],
						$v['content'],
					]
				);
    		}

    		__OmsySql->execute(
				"INSERT INTO
					enncore_catalogue (uniqid, slug_, parent_, title, categorie, view, episode, saison, date_, last_)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
				",
				[
					$uniqid,
					$value['uniqid'],
					$value['parent_'],
					$value['title'],
					$value['categorie'],
					$value['view'],
					$value['episode'],
					$value['saison'],
					$value['date_'],
					$value['last_']
				]
			);
    	}
    }



















    public function langToText($id) {
        $lang = $_COOKIE['lang'] ?? 'french';
        if(!$this->langUpdate[$lang]) $lang = 'french';

        if($this->langUpdate[$lang][$id]) return $langUpdate[$lang][$id];
        return $id;
    }
	public function getAllObjectChannel($unqid) {
		$buffImg = ['channel-language' => []];
		//$channelImgSQL = __OmsySql->execute('SELECT * FROM pvs_channel_obj WHERE channel_id = ?', [$unqid])->fetchAll();
		$channelImgSQL = __OmsySql->execute('SELECT * FROM enncore_catalogue_obj WHERE channel_id = ? AND content != ""', [$unqid])->fetchAll();
		foreach ($channelImgSQL as $key => $value) {
			$buffImg[$value['type']][$value['name']] = $value['content'];
		}

		$channelImgSQL = __OmsySql->execute('SELECT * FROM omsy_object WHERE object_name = ?', [$unqid])->fetchAll();

		foreach ($channelImgSQL as $key => $value) {
			$buffImg[$value['object_type']][] = $value['object_content'];
		}

		$reqAllTitle = $buffImg['channel-title'] ?? [];
		$reqAllDescription = $buffImg['channel-description'] ?? [];
		unset($buffImg['channel-title']);
		unset($buffImg['channel-description']);

		$buffChannelInfo = [];
		foreach ($reqAllTitle as $key => $value) {
			$lang = $key;
			$buffLang[] = $lang;

			$title = $value;
			$description = $reqAllDescription[$key] ?? '';
			$buffChannelInfo[$lang] = [
				'title' => __OmsyString->htmlDecode($title),
				'description' => __OmsyString->htmlDecode($description),
			];
		}

		if(isset($buffChannelInfo['french'])) $langId = 'french';
		else $langId = $buffLang[0] ?? '';

		$buffImg['channel-language'] = [
			'default' => $langId,
			'default_item' => $buffChannelInfo[$langId] ?? [],
			'items' => $buffChannelInfo
		];

		return $buffImg;
    }

	public function getChannel($channel) {
		$channelInfo = [];
		$videoChannel = [];
		$rowSaison = 1;

		$saisonActive = 1;
		if($saisonActive == 0) $saisonActive = 1;
		if($saisonActive > $rowSaison) $saisonActive = $rowSaison;

		$channelInfo = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$channel])->fetchAll();
		if(!isset($channelInfo[0])) return null;
		$channelInfo = $channelInfo[0];
		$getAllView = __OmsySql->execute('SELECT * FROM system_viewpage WHERE page LIKE ? ORDER BY ID DESC', ['%'.$channelInfo['uniqid'].'%'])->fetchAll();


		$channelMergeBuff = $this->getAllObjectChannel($channelInfo['uniqid']);
		if(!isset($channelMergeBuff['playlist'])) $channelMergeBuff['playlist'] = ['id' => ''];

		$arrChannelLang = $channelMergeBuff['channel-language'];
		$channelInfo = array_merge(
			$channelInfo,
			$channelMergeBuff,
			[
				'title' => $arrChannelLang['default_item']['title'] ?? "",
				'description' => $arrChannelLang['default_item']['description'] ?? "",
			]
		);
		$buffImg = $this->imageArrayConverter($channelInfo['img'] ?? null);

		$videoChannel = __OmsySql->execute("SELECT * FROM enncore_player WHERE catalogue_id = ? ORDER BY episode ASC", [$channelInfo['uniqid']])->fetchAll() ?? [];
		$listEpisode = [];
		$newListeEpisode = [];

		$i=0;
		foreach ($videoChannel as $value):
			if(!isset($listEpisode[$value['saison']][$value['uniqid']])) $listEpisode[$value['saison']][$value['uniqid']] = true;

			if(intval($value['saison']) > $rowSaison) $rowSaison = intval($value['saison']);

			if($value['saison'] == $saisonActive && $value['episode'] <= 6):
				if(!isset($newListeEpisode[$value['episode']])) $i++;
				$newListeEpisode[$value['episode']] = [
					"id" => $value['uniqid'],
					"title" => $value['title'],
					"thumbnail" => $this->jsonParse($value['thumbnail']) ?? null,
					"lang" => []
				];
			endif;
		endforeach;

		// Appeler la fonction pour chaque parent requis
		$kidsChannel = [];
		$kidsChannel = array_merge($kidsChannel, $this->fetchChannelData($channelInfo['uniqid'], 'parent_', $channelInfo['uniqid']));
		$kidsChannel = array_merge($kidsChannel, $this->fetchChannelData($channelInfo['parent_'], 'parent_', $channelInfo['uniqid']));
		$kidsChannel = array_merge($kidsChannel, $this->fetchChannelData($channelInfo['parent_'], 'uniqid', $channelInfo['uniqid']));

		$countEp = 0;
		if(isset($listEpisode[$saisonActive])) $countEp = (count($listEpisode[$saisonActive]) - $i);

		$newBuffCaching = [
			"meta" => [
				'uniqid' => $channelInfo['uniqid'],
				'slug' => $channelInfo['slug_'],
				'channel' => $channelInfo['uniqid'],
				'parent' => $channelInfo['parent_'],

				'count_view' => count($getAllView),
				'count_saison' => $rowSaison,
				'count_episode' => count($videoChannel),

				'images' => $this->imageArrayConverter($channelInfo['img'] ?? null),
			],
			"information" => [
				"title" => $arrChannelLang['default_item']['title'] ?? '',
				"description" => $arrChannelLang['default_item']['description'] ?? '',

				'catagorie' => explode(',', $channelInfo['genres']),
			],
			"objects" => [
				'videos' => $newListeEpisode,
				'kids' => $kidsChannel,
				'blog' => []
			],
		];

		/* $getPlaylist = __OmsySql->execute('SELECT * FROM plug_playlist_content WHERE play_id = ?', [$channelMergeBuff['playlist']['id']])->fetchAll() ?? [];
		foreach ($getPlaylist as $key => $value) {
			$newBuffCaching['publication'][] = __OmsySql->execute('SELECT * FROM blog_single WHERE ID = ?', [$value['content']])->fetchAll()[0];
		} */

		return $newBuffCaching;
	}

	public function fetchChannelData($parentId, $parent = 'parent_', $ign = '') {
        $channels = __OmsySql->execute("SELECT * FROM enncore_catalogue WHERE $parent = ? AND $parent != 'null' ", [$parentId])->fetchAll();
        $kidsChannel = [];

        if (!empty($channels)) {
            foreach ($channels as $value) {
            	$buff = $this->getAllObjectChannel($value['uniqid']);
                // Fusionner les données supplémentaires avec le channel
                $value = array_merge($value, $buff);

                // Récupérer la langue par défaut et les informations du channel
                $arrChannelLangForeach = $value['channel-language'];
                $langId = $arrChannelLangForeach['default'];
                $buffChannelInfo = $arrChannelLangForeach['items'];

                if($ign === $value['uniqid']) continue;

                // Ajouter l'élément au tableau final
                $kidsChannel[] = [
                    "id" => $value['uniqid'],
                    "slug" => $value['slug_'],
                    "categorie" => $value['categorie'],
                    "title" => html_entity_decode($buffChannelInfo[$langId]['title'] ?? ''),
                    "information" => $arrChannelLangForeach['default_item'],
                    "thumbnail" => $value['img']
                ];
            }
        }

        return $kidsChannel;
    }

	public function jsonParse($string) {
		$st = html_entity_decode(htmlspecialchars_decode($string));
		return json_decode($st, true);
	}



	public function sqlPagex($tab, $sqlEnded = '', $max = 10) {
		$listChannels = __OmsySql->execute("SELECT * FROM $tab $sqlEnded")->fetchAll();

		$countChannelVideo = count($listChannels);
		$pagesTotales = ceil($countChannelVideo/$max);
		if(isset($_GET['pp']) AND !empty($_GET['pp']) AND $_GET['pp'] > 0 AND $_GET['pp'] <= $pagesTotales) {
			$_GET['pp'] = intval($_GET['pp']);
			$pageCourante = $_GET['pp'];
		} else {
			$pageCourante = 1;
		}
		$depart = ($pageCourante-1)*$max;
		
		$listChannels = __OmsySql->execute("SELECT * FROM $tab $sqlEnded LIMIT $depart, $max")->fetchAll();
		

		return [
			'items' => $listChannels,
			'page' => $pageCourante,
			'max' => $max,
			'totalePage' => $pagesTotales,
			'totaleItems' => $countChannelVideo,
		];
	}

	public function catalogueInfo($ID) {
		$channelInfo = __OmsySql->execute('SELECT * FROM enncore_catalogue WHERE uniqid = ?', [$ID])->fetchAll();
		if(!isset($channelInfo[0])) return null;
		$channelInfo = $channelInfo[0];
		$catalogueObj = $this->getAllObjectChannel($channelInfo['uniqid']);

		$channelInfo['information'] = $catalogueObj['channel-language']['default_item'];
		$channelInfo['image'] = $this->imageArrayConverter($catalogueObj['img'] ?? null);

		return $channelInfo;
	}

	public function playerInfo($ID) {
		$getPlayer = __OmsySql->execute('SELECT * FROM enncore_player WHERE ID = ?', [$ID])->fetchAll();

		if(count($getPlayer) <= 0) return NULL;
		$getPlayer = $getPlayer[0];

		$getPlayer['thumbnail'] = $this->jsonParse($getPlayer['thumbnail']) ?? null;
		$getPlayer['mime'] = $this->jsonParse($getPlayer['mime']) ?? null;

		return $getPlayer;
	}

	public function imageArrayConverter($array) {
		return $array ?? [
			'poster' => '/interface/img/poster.png',
			'thumbnail' => '/interface/img/thumbnail.png',
			'banner' => '/interface/img/banner.png',
		];
	}
}