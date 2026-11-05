<?php
$data = __OmsySql->execute('SELECT uniqid FROM enncore_catalogues')->fetchAll();
foreach ($data as $value) {
	$value = __EnnCatalogues->get($value['uniqid'], 'uniqid', [
		"description" => true,
		"genres" => true,
		"image" => ["poster", "thumbnail", "hero"]
	]);
	if(is_string($value['genres'])) $value['genres'] = explode(',', $value['genres'] ?? '');
	elseif(!is_array($value['genres'])) $value['genres'] = [];

	$str = ($value['title'] ?? '') . ', ' . ($value['description'] ?? '') . ', ' . implode(',', $value['genres']);
	__OmsySql->execute(
		'INSERT INTO enncore_search (catalogue_id, query_search, search_rank, date_) VALUES(?, ?, ?, now())',
		[$value['uniqid'], html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0]
	);
	echo '<br>';
	var_dump([$value['uniqid'], html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0]);
}
var_dump(__EnnCatalogues->research_('dan'));