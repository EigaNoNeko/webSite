<?php
$data = [];

$db = $_GET['db'] ?? "all";

$limit = $_GET['limit'] ?? 25;
$page = $_GET['page'] ?? 1;

$search = $_GET['query_search'] ?? " ";

$paramsBack = [
	'db' => $_GET['db'] ?? 'all',
	'limit' => $_GET['limit'] ?? 25,
	'page' => ($_GET['page'] ?? 1) - 1,
	'query_search' => $_GET['query_search'] ?? ''
];

$paramsNext = [
	'db' => $_GET['db'] ?? 'all',
	'limit' => $_GET['limit'] ?? 25,
	'page' => ($_GET['page'] ?? 1) + 1,
	'query_search' => $_GET['query_search'] ?? ''
];

$limit = 42;
if($db == "all") $limit = 7;

if($search) {
	$search = trim($search);

	$querySearch = '%'.htmlentities($search).'%';

	if(in_array($db, ["all", "catalogue"])) {
		if(!empty($search)) $data["Catalogues"] = __EnnCatalogues->research($search);
		else $data["Catalogues"] = __EnnCatalogues->research($search, $limit);
	}

	if(in_array($db, ["categorie"])) {
		foreach (__OmsySql->execute('SELECT * FROM enncore_categorie')->fetchAll() as $value) {
			$dataBuff = [];
			foreach ($omsyCatalogObj = __OmsySql->execute('SELECT uniqid FROM enncore_catalogues WHERE genres LIKE "%'.$value['name'].'%" LIMIT 7')->fetchAll() as $ca) {
				$dataBuff[] = __EnnCatalogues->get($ca['uniqid']);
			}
			if(count($dataBuff) > 0) $data[$value['name']] = [
				"meta" => [
					"data_type" => "catalogue",
					"see_more" => "/archive?db=catalogue&query_search=".$value['name']
				], 
				"data" => $dataBuff
			];
		}
	}

	if(in_array($db, ["all", "people"])) $data["Peoples"] = __EnnPeoples->research($search, $limit);
	if(in_array($db, ["all", "character"])) $data["Characters"] = __EnnCharacters->research($querySearch, $limit);
	if(in_array($db, ["all", "user"])) $data["Users"] = __OmsyAccounts->research($querySearch, $limit);

	if(in_array($db, ["all", "playlist"])) $data["Playlist"] = __EnnPlaylists->research($search, $limit);
}

header('content-type: application/json');
echo __OmsyJSON->encode($data);