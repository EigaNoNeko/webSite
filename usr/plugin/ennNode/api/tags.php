<?php
$cat = __OmsySql->execute("SELECT * FROM pvs_categorie ORDER BY RAND()");

$header = ["host" => null];
$reponse = [];

foreach ($cat->fetchAll() as $value) {
	$count = count(__OmsySql->execute('SELECT * FROM pvs_channel WHERE categorie LIKE ?', ['%'.$value['name'].'%'])->fetchAll());

	if($count <= 1) continue;

	$reponse[] = $value;
}

$body = [
	"objects" => $reponse
];
echo __OmsyPlugin->ennNode->constructAPI($header, $body);