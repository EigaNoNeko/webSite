<?php
class Playlists {
	function __construct() { define('__EnnPlaylists', $this); }

	public function get($id, $type = "uid") {
		switch ($type) {
			case 'slug':
				$typeD = "slug_";
				break;

			case 'uid':
				$typeD = "uid";
				break;
			
			default:
				$typeD = "uid";
				break;
		}

		$catalogues = __OmsySql->execute("SELECT * FROM enncore_account_playlist WHERE $typeD = ?", [$id])->fetchAll();
		if(!isset($catalogues[0])) return null;

		$user = __OmsyAccounts->get($catalogues[0]['user_uid']);

		$buff = [];
		foreach ($catalogues as $key => $value) {
			$cat = __EnnCatalogues->get($value['content']);
			if(!isset($cat)) {
				continue;
			}

			$cat['uid'] = $value['uid'];

			$buff[] = $cat;
		}
		
		return [
			"user" => $user,
			"name" => $catalogues[0]['name'],
			"about" => $catalogues[0]['about'],
			"data" => $buff,
		];
	}

	public function get_($id, $type = "uid") {
		switch ($type) {
			case 'slug':
				$typeD = "slug_";
				break;

			case 'uid':
				$typeD = "uid";
				break;
			
			default:
				$typeD = "uid";
				break;
		}

		$catalogues = __OmsySql->execute("SELECT * FROM enncore_account_playlist WHERE $typeD = ?", [$id])->fetchAll();
		if(!isset($catalogues[0])) return null;

		$user = __OmsyAccounts->get($catalogues[0]['user_uid']);
		
		return [
			"uid" => $catalogues[0]['uid'],
			"user" => $user,
			"name" => $catalogues[0]['name'],
			"about" => $catalogues[0]['about']
		];
	}

	public function research($query, $limit = 16) {
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		if($page < 1) $page = 1;

		$datas = [];
		$querySearch = '%'.htmlentities($query).'%';

		$parse = [];

		$request = "SELECT uid FROM enncore_account_playlist";
		if(!empty($query)):
			$request .= " WHERE name LIKE ?";
			$parse = [$querySearch];
		endif;
		$request .= " GROUP BY uid";

		$total = __OmsySql->execute(
			$request,
			$parse
		)->fetchAll();

		$pagesTotales = ceil(count($total)/$limit);
		$depart = ($page-1)*$limit;

		$request .= " LIMIT $depart, $limit";
		$parse = [];
		if(!empty($query)):
			$parse = [$querySearch];
		endif;

		$req = __OmsySql->execute(
			$request,
			$parse
		)->fetchAll();

		$buff = [];
		foreach ($req as $key => $val) {
			$value = $this->get_($val['uid']);

			$buff[] = [
				"user" => $value['user'],
				"uniqid" => $value['uid'],
				"url_single" => "single_playlist?id=".$value['uid'],
				"img" => $value['user']['user_avatar'] ?? "/use/img/avatar/news/gumballs/Capture_decran_2025-12-24_003034.png",
				"name" => $value['name']
			];
		}
		return [
			"meta" => [
				"data_type" => "playlist"
			],
			"pagination" => [
				"current_page" => $page,
				"max_page" => $pagesTotales,
				"items" => [
					"count" => count($buff),
					"total" => count($total),
					"per_page" => $limit,
				]
			],
			"data" => $buff
		];
	}

	public function getItemListUser($nameList, $catalogueID) {
		$data = __OmsySql->execute(
			'SELECT * FROM enncore_account_playlist WHERE user_uid = ? AND name = ? AND content = ?',
			[__OmsyAccounts->uniqid, $nameList, $catalogueID]
		)->fetchAll();

		if(isset($data[0])) return true;
		return false;
	}

	public function deleteItemListUser($nameList, $catalogueID) {
		__OmsySql->execute(
			'DELETE FROM enncore_account_playlist WHERE user_uid = ? AND name = ? AND content = ?',
			[__OmsyAccounts->uniqid, $nameList, $catalogueID]
		);
	}

	public function insertListUser($nameList, $catalogueID) {
		$data = __OmsySql->execute(
			'SELECT * FROM enncore_account_playlist WHERE user_uid = ? AND name = ?',
			[__OmsyAccounts->uniqid, $nameList]
		)->fetchAll();
		if(isset($data[0])) $unid = $data[0]['uid'];
		else $unid = uniqid();

		__OmsySql->execute(
			'INSERT INTO enncore_account_playlist (uid, user_uid, name, content, about, added_date) VALUES(?, ?, ?, ?, "", now())',
			[$unid, __OmsyAccounts->uniqid, $nameList, $catalogueID]
		);
	}

	public function getListByUser($id) {
		$catalogues = __OmsySql->execute("SELECT * FROM enncore_account_playlist WHERE user_uid = ?", [$id])->fetchAll();
		if(!isset($catalogues[0])) return null;

		$buff = [];
		foreach ($catalogues as $key => $value) {
			if(!isset($buff[$value['uid']])) $buff[$value['uid']] = $value;
			if(!isset($buff[$value['uid']]['items_count'])) $buff[$value['uid']]['items_count'] = 0;
			$buff[$value['uid']]['items_count']++;
		}

		return $buff;
	}
}