<?php

$channel = $_POST['channel'] ?? null;
$playlist = intval($_POST['playlist'] ?? 1);

header('Content-Type: application/json');

if (!$channel) {
    echo json_encode([]);
    exit;
}

$channelInfo = __OmsySql->execute(
    'SELECT uniqid, slug_ FROM enncore_catalogues WHERE uniqid = ? LIMIT 1',
    [$channel]
)->fetchAll()[0] ?? null;

if (!$channelInfo) {
    echo json_encode([]);
    exit;
}

$listVideo = __OmsySql->execute(
    "SELECT * FROM enncore_players
    WHERE saison = ? AND catalogue_id = ?
    ORDER BY episode ASC",
    [$playlist, $channelInfo['uniqid']]
)->fetchAll();

$watchListBuff = [];

foreach ($listVideo as $value) {
    $episode = intval($value['episode']);

    $watchListBuff[$episode] = [
        'uniqid' => $value['uniqid'],
        'episode' => $value['episode'],
        'playlist' => $value['saison'],
        'watching' => false,
        'lang' => implode(', ', json_decode(html_entity_decode($value['lang_']))) ?? null,
        'url' => $channelInfo['slug_'] . '/' . $value['uniqid']
    ];
}

ksort($watchListBuff, SORT_NUMERIC);

echo json_encode($watchListBuff);