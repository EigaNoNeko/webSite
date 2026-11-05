<?php
$url = $_GET['url'] ?? '';
$catalogue = $_GET['catalogue'] ?? '';
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'URL invalide']);
    return __OmsyMain->api(
        'GET',
        [
            'error' => 'URL invalide'
        ]
    );
}

$host = parse_url($url, PHP_URL_HOST);
if ($host === 'video.sibnet.ru') {
    return __OmsyMain->api(
        'GET', null,
        [ 'ok' => true, 'catalogue' => $catalogue ]
    );
    exit;
}

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_NOBODY => true,    // HEAD request
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_USERAGENT => 'Mozilla/5.0',
]);
curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if($status < 200 || $status >= 300) {
    return __OmsyMain->api(
        'GET',
        [
            'error' => $error ?: null,
            'status' => $status
        ],
        [ 'ok' => false, 'catalogue' => $catalogue ]
    );
}

return __OmsyMain->api('GET', null, [ 'ok' => true, 'catalogue' => $catalogue ]);