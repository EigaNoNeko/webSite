<?php
class Peoples {
	function __construct() { define("__EnnPeoples", $this); }

	public function research($query, $limit = 6) {
		$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
		if ($page < 1) $page = 1;

		$datas = [];
		$querySearch = "%".$query."%";

		$total = __OmsySql->execute(
			'SELECT ID FROM encore_peoples WHERE name LIKE ?', 
			[$querySearch]
		)->fetchAll();

		$pagesTotales = ceil(count($total)/$limit);
		$depart = ($page-1)*$limit;

		$ennCatalogObj = __OmsySql->execute(
			'SELECT * FROM encore_peoples WHERE name LIKE ? LIMIT '.$depart.','.intval($limit), 
			[$querySearch]
		)->fetchAll();

		foreach ($ennCatalogObj as $key => $value) {
			$value['image'] = $value['image'];
			$value['url_single'] = "single_people.php?ID=".$value['ID'];
			$datas[] = $value;
		}

		return [
			"meta" => [
				"data_type" => "peoples"
			],
			"pagination" => [
				"current_page" => $page,
				"max_page" => $pagesTotales,
				"items" => [
					"count" => count($datas),
					"total" => count($total),
					"per_page" => $limit,
				],
			],
			"data" => $datas
		];
	}
}