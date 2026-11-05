<?php
$getAllSection = __SessionOmsy->getAllSession();

$buffSession = [];
foreach ($getAllSection as $v) {
    unset($v['ID']);
    unset($v['session_id']);
    $v['last_activity'] = strtotime($v['last_activity']);
    $v['appareil'] = getDeviceType($v['agent']);
    $buffSession[]=$v;
}
header('Content-Type: application/json');

echo json_encode([
    "err" => false,
    "code" => 200,
    "message" => "Success request",
    'total_session' => count($buffSession),
    'list' => $buffSession,
]);

function getDeviceType($userAgent = null) {
    // Utiliser le User-Agent fourni ou celui du navigateur actuel
    if ($userAgent === null) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    }

    // Détection mobile
    if (preg_match('/mobile|iphone|ipod|android.*mobile|blackberry|nokia|opera mini|windows phone/i', $userAgent)) {
        return 'mobile';
    }

    // Détection tablette
    if (preg_match('/ipad|android(?!.*mobile)|tablet|kindle|silk/i', $userAgent)) {
        return 'tablet';
    }

    // Détection TV connectée
    if (preg_match('/smart-tv|smarttv|hbbtv|appletv|googletv|netcast|viera|roku|dtv/i', $userAgent)) {
        return 'tv';
    }

    // Par défaut, considérer comme un ordinateur
    return 'desktop';
}