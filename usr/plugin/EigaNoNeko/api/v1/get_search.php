<?php
if(!isset($_POST['query'])) die(json_encode(["query" => "", "data" => []]));
else $querySearch = $_POST['query'];


if(strlen($querySearch) > 255) die(json_encode(["err" => "Chaine de rechercher superrieur à 255caractères", "query" => "", "data" => []]));

$data = __EnnCatalogues->research_($querySearch, 6);

$new = [];
foreach ($data['data'] as $key => $value) {
	//$value['title'] = OmsyString->htmlDecode($value['title']);
	$new[] = $value;
}

header('Content-type: text/json');
die(json_encode([ "query" => $querySearch, "data" => $new ]));