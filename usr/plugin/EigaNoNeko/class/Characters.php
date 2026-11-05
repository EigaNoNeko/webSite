<?php
class Characters {
	function __construct() { define('__EnnCharacters', $this); }

	public function research($query, $limit = 6) {
		$datas = [];
		$querySearch = '%'.htmlentities($query).'%';
		$querySearchNotSpace = preg_replace('/\s+/', '-', $querySearch);

		$buffCatalogId = [];
		$ennCatalogObj = __OmsySql->execute(
			'SELECT * FROM enncore_characters WHERE name LIKE ? LIMIT '.intval($limit),
			[$query]
		)->fetchAll();
		foreach ($ennCatalogObj as $key => $value) {
			$value['image'] = json_decode(html_entity_decode(htmlspecialchars_decode($value['image'])), true);
			$value['url_single'] = "single_character.php?ID=".$value['ID'];
			$datas[] = $value;
		}

		return [
			"meta" => [
				"data_type" => "character"
			],
			"data" => $datas
		];
	}
}