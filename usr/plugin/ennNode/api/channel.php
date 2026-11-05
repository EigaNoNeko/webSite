<?php
/*
// $getInfo = __OmsySql->execute('SELECT * FROM plug_server WHERE uniqid = ?', [$serverKey])->fetch();
$data = __OmsyPlugin->ennNode->informationServerVideo($_POST['server']);
$datares = __OmsyPlugin->ennNode->request('http://'.$data->adress.'/channel', [ 'channel' => $_POST['channel'] ]);

$header = [];

$jsonreturn = __OmsyPlugin->ennNode->constructAPI($header, $datares);
echo $jsonreturn;
$fileNameCache = "channel-".$data->uniqid."_".$_POST['channel'];
file_put_contents(dirname(__FILE__)."/../.cache/$fileNameCache.json", $jsonreturn);


*/

if(!isset($_POST['channel'])) $cannel = $_GET['channel'];
else $cannel = $_POST['channel'];

$data = __OmsySql->execute("SELECT * FROM pvs_channel WHERE uniqid = ?", [$cannel])->fetchAll()[0];


$arrChannelLang = __OmsyPlugin->ennNode->getLangueItems($data['uniqid']);
$langId = $arrChannelLang['default'];
$buffChannelInfo = $arrChannelLang['items'];

$buffImg = [];
$channelImgSQL = __OmsySql->execute('SELECT * FROM pvs_channel_obj WHERE channel_id = ? AND type = "img"', [$data['uniqid']])->fetchAll();
foreach ($channelImgSQL as $key => $value) {
    $buffImg[$value['name']] = $value['content'];
}

$buffApi = [
    "channel" => [
        "id" => $data['uniqid'],
        "tags" => __OmsyString->htmlDecode($data['categorie']),
        "title" => __OmsyString->htmlDecode($buffChannelInfo[$langId]['title']),
        "description" => __OmsyString->htmlDecode($buffChannelInfo[$langId]['description']),
        "images" => $buffImg,
    ],
    "watch" => [],
    "watch_enfant" => [],
    "list" => [],
    "scan" => [],
];

$d = __OmsySql->execute(
	"SELECT * FROM pvs_watch WHERE channel_id = ? ORDER BY title ASC", /*" GROUP BY uniqid ORDER BY title ASC",*/
	[$data['uniqid']]
)->fetchAll();

foreach ($d as $value) {
    /* $buffApi['watch'][$value['cannal']][] = [
        "id" => $value['uniqid'],
        "title" => 'Episode '.$value['title'],
        "thumbnail" => jsonParse($value['thumbnail']) ?? ['']
    ]; */

    $buffApi['list']["EP"][$value['cannal']][] = [
        "id" => $value['uniqid'],
        "title" => 'Episode '.$value['title'],
        "url" => 'watch',
        "channel" => $value['channel_id'],
        "thumbnail" => jsonParse($value['thumbnail']) ?? ['']
    ];
}

$parent = __OmsySql->execute("SELECT * FROM pvs_channel WHERE parent_ = ?", [$cannel])->fetchAll();
if(isset($parent)) {

foreach ($parent as $value) {
    $buffImg = [];
    $channelImgSQL = __OmsySql->execute('SELECT * FROM pvs_channel_obj WHERE channel_id = ? AND type = "img"', [$value['uniqid']]);
    foreach ($channelImgSQL->fetchAll() as $v) {
        $buffImg[$v['name']] = $v['content'];
    }

    $arrChannelLang = __OmsyPlugin->ennNode->getLangueItems($value['uniqid']);
    $langId = $arrChannelLang['default'];
    $buffChannelInfo = $arrChannelLang['items'];

    $buffApi['list'][$buffChannelInfo[$langId]['title']][1][] = [
        "id" => $value['uniqid'],
        "channel" => $value['uniqid'],
        "url" => 'channel',
        "title" => html_entity_decode($buffChannelInfo[$langId]['title']),
        "thumbnail" => $buffImg
    ];
}
}

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
echo json_encode(['body' => $buffApi]);
$fileNameCache = "channel-".$_POST['channel'];
file_put_contents(dirname(__FILE__)."/../.cache/$fileNameCache.json", json_encode(['body' => $buffApi]));
/*
$allScan = __OmsySql->execute('SELECT * FROM scan_channel WHERE channel_id = ? ORDER BY chapter ASC', [$cannel]);
foreach ($allScan as $key => $value) {
    $buffApi['scan'][] = [
        "uniqid" => $value['uniqid'],
        "channel" => $value['channel_id'],
        "title" => $value['title'],
        "chapter" => $value['chapter'],
        "cover" => $value['cover'],
    ];

    $buffApi['list']['Manga Scan - '.$buffChannelInfo[$langId]['title']][1][] = [
        "id" => $value['uniqid'],
        "channel" => $value['channel_id'],
        "url" => 'reading',
        "title" => 'Chapter '.$value['chapter'],
        "thumbnail" => [$value['cover']],
    ];
}*/

function jsonParse($string) {
    $st = html_entity_decode(htmlspecialchars_decode($string));
    return json_decode($st, true);
}