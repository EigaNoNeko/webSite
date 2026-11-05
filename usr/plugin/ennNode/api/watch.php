<?php
header('Content-Type: application/json');

if(!isset($_POST['video'])) $video = $_GET['video'];
else $video = $_POST['video'];

$videoInfo = [];
$defaultIndex = '';
$st = __OmsySql->execute("SELECT * FROM pvs_watch WHERE uniqid = ?", [$video])->fetchAll();
$vBuff = [];
foreach ($st as $k => $value) {
    /* $subtitleRequest = __OmsySql->execute('SELECT * FROM subtitle WHERE watch_id = ?', [$value['uniqid']]);
    $subtitleList = [];
    foreach ($subtitleRequest->fetch_all(MYSQLI_ASSOC) as $sub) {
        $subtitleList[$sub['lang']] = $sub['file_'];
    } */

    if($k == 0)
        $defaultIndex = $value['lang_'];

    $vBuff[$value['lang_']] = [
        "id" => $value['uniqid'],
        "name" => 'Episode '.$value['title'],
        "mediaQuality" => jsonParse($value['quality']),
        "mediaType" => jsonParse($value['mime']),
        "src" => $value['src'],
        "subtitle" => $subtitleList ?? [],
        "poster" => jsonParse($value['thumbnail'])[0]
    ];
   $data = $value;
   $videoInfo = $value;
}

$channelInfo = __OmsySql->execute('SELECT * FROM pvs_channel WHERE uniqid = ?', [$data['channel_id']])->fetchAll()[0];

$buffImg = [];
$channelImgSQL = __OmsySql->execute('SELECT * FROM pvs_channel_obj WHERE channel_id = ? AND type = "img"', [$channelInfo['uniqid']])->fetchAll();
foreach ($channelImgSQL as $key => $value) {
    $buffImg[$value['name']] = $value['content'];
}

$arrChannelLang = __OmsyPlugin->ennNode->getLangueItems($channelInfo['uniqid']);
$langId = $arrChannelLang['default'];


$buffV = [];
$buffVideos = [];
$d = __OmsySql->execute("SELECT * FROM pvs_watch WHERE channel_id = ?", [$channelInfo['uniqid']]);
$da = $d->fetchAll();
foreach ($da as $value) {
    $buffVideos[$value['cannal']][] = [
        "id" => $value['uniqid'],
        "title" => 'Episode '.$value['title'],
        "thumbnail" => jsonParse($value['thumbnail']) ?? ['']
    ];

    $buffV[$value['cannal']][$value['title']]=$value['uniqid'];
}

$videoInfo['cannal'] = intval($videoInfo['cannal']);
$videoInfo['title'] = intval($videoInfo['title']);

$buffApi = [
    "channel" => [
        "id" => $channelInfo['uniqid'],
        "title" => $arrChannelLang['default_item']['title'],
        "description" => $arrChannelLang['default_item']['description'],
        "images" => $buffImg,
    ],
    "watch" => [
        "default" => $defaultIndex,
        "videos" => $vBuff,
        "next" => $buffV[$videoInfo['cannal']][$videoInfo['title']+1] ?? NULL,
        "back" => $buffV[$videoInfo['cannal']][$videoInfo['title']-1] ?? NULL,
    ],
    "watchs" => $buffVideos
];

$reponse = [
	"body" => $buffApi
];
echo json_encode($reponse);

function jsonParse($string) {
    $st = html_entity_decode(htmlspecialchars_decode($string));
    return json_decode($st, true);
}