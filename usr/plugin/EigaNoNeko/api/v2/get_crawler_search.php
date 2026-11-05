<?php
$data = __OmsySql->execute(
	'SELECT * FROM as_crawler WHERE source LIKE ? AND taille > 0',
	['%'.$_POST['query'].'%']
)->fetchAll();
echo json_encode($data);