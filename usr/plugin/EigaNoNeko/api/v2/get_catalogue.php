<?php
$genre = $_POST['filter_genre'] ?? [];
$page = max(1, (int)($_POST['page'] ?? 1));
$perPage = (int)($_POST['per_page'] ?? 20);
$offset = ($page - 1) * $perPage;

$data = [];
// if (empty($genre)) {
//	$sql = 'SELECT catalogue_id, COUNT(catalogue_id) as rank FROM enncore_search GROUP BY catalogue_id';
// } else {
//	$conditions = array_fill(0, count($genre), 'query_search LIKE ?');
//	$sql = 'SELECT catalogue_id, COUNT(catalogue_id) as rank 
//			FROM enncore_search 
//			WHERE ' . implode(' OR ', $conditions) . ' 
//			GROUP BY catalogue_id 
//			ORDER BY rank DESC';
//	foreach ($genre as $value) {
//		$data[] = '%' . $value . '%';
//	}
// }
if (empty($genre)) {
	$sql = 'SELECT catalogue_id FROM enncore_search';
} else {
	$conditions = array_fill(0, count($genre), 'query_search LIKE ?');
	$sql = 'SELECT DISTINCT catalogue_id FROM enncore_search WHERE ' . implode(' AND ', $conditions);
	foreach ($genre as $value) {
		$data[] = '%' . $value . '%';
	}
}

$all = __OmsySql->execute($sql, $data)->fetchAll();
$totalItems = count($all);
$totalPages = (int)ceil($totalItems / $perPage);
$paginated = array_slice($all, $offset, $perPage);

$buff = [];
foreach ($paginated as $value) {
	$buff[] = __EnnCatalogues->get($value['catalogue_id'], 'uniqid', [
		"description" => true,
		"genres" => true,
		"langues" => true,
		"image" => ["poster"],
	]);
}
echo json_encode([
	'pagination' => [
        'total_items' => $totalItems,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'per_page'     => $perPage,
        'has_next'     => $page < $totalPages,
        'has_prev'     => $page > 1,
    ],
	'data' => $buff
]);