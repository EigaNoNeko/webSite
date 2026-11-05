<?php
$url = $_GET['url'] ?? '';

if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'URL invalide']);
    exit;
}

$host = parse_url($url, PHP_URL_HOST);
if ($host === 'video.sibnet.ru') {
    echo json_encode([
        'ok' => 200,
        'status' => 200,
        'error' => null
    ]);
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

header('Content-Type: application/json');

echo json_encode([
    'ok' => $status >= 200 && $status < 300,
    'status' => $status,
    'error' => $error ?: null
]);