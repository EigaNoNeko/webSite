<?php
if(!isset($_POST['query'])) $querySearch = '%'.htmlentities($_GET['query']).'%';
else $querySearch = '%'.htmlentities($_POST['query']).'%';

$data = __OmsySql->execute("SELECT * FROM pvs_channel WHERE (title LIKE ? OR categorie LIKE ?)", [$querySearch,$querySearch])->fetchAll();

$reponse = [];
foreach ($data as $value) {
	$buffImg = [];
	$buffInfo = [];
	$channelImgSQL = __OmsySql->execute('SELECT * FROM pvs_channel_obj WHERE channel_id = ?', [$value['uniqid']]);
	foreach ($channelImgSQL->fetchAll() as $valuecc) {
		$buffImg[$valuecc['name']] = $valuecc['content'];
	}

	$channelInfo = __OmsySql->execute('SELECT * FROM pvs_channel_info WHERE channel_id = ?', [$value['uniqid']]);
	foreach ($channelInfo->fetchAll() as $valuecc) {
		$buffInfo[$valuecc['name']][] = $valuecc['content'];
	}

	$reponse[] = [
		"title" => $value['title'],
		"id" => $value['uniqid'],
		'images' => $buffImg,
		'tags' => $buffInfo
	];
}


/*
$data = CMS->Plugins->ennNode->informationServerVideo($_POST['server']);
$datares = CMS->Plugins->ennNode->request('http://'.$data->adress.'/search', [ 'query' => $_POST['query'] ]);

$header = ["host" => null];
$i=0;
$buff = [];

foreach ($datares['objects'] as $key => $value) {
	$buff[] = [
		"id" => $value['id'],
		"title" => $value['title'],
		"tags" => $value['tags'],
		"images" => $value['images']
	];
} */
$header = ["host" => null];
$body = [
	"objects" => [
		"host" => $_POST['server'],
		"objects" => $reponse
	]
];

echo CMS->Plugins->ennNode->constructAPI($header, $body);