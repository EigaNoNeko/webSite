<?php
$channel = __OmsySecurity->getArgs()[1];

$channelInfo = __EnnCatalogues->get($channel, 'slug_', [
    "object" => true,
    "description" => true,
    "genres" => true
]);
if(!isset($channelInfo['uniqid'])) return __OmsyInterface->err(404);

$saison = intval($_GET['s'] ?? '1');
$episode = intval($_GET['ep'] ?? '1');
$typeVideo = $_GET['type'] ?? null;

if(isset($typeVideo)) {
    $videoChannel_____ = __OmsySql->execute("SELECT * FROM enncore_players WHERE episode = ? AND saison = ? AND catalogue_id = ? AND type = ?", [$episode, $saison, $channelInfo['uniqid'], $typeVideo])->fetchAll()[0] ?? null;
} else {
    $videoChannel_____ = __OmsySql->execute("SELECT * FROM enncore_players WHERE episode = ? AND saison = ? AND catalogue_id = ? AND type IS NULL", [$episode, $saison, $channelInfo['uniqid']])->fetchAll()[0] ?? null;
}
if(!isset($videoChannel_____['uniqid'])) return __OmsyInterface->err(404);

// --- Priorités ---
function getLangue($l) {
    $mapping = [
        "vo"   => "Doublage original",
        "vj"   => "Doublage japonais",
        "vf"   => "Doublage français",
        "ve"   => "Doublage anglais",
        "va"   => "Doublage anglais",

        "stfr" => "Sous-titrage français",
        "sten" => "Sous-titrage anglais",
        "sta"  => "Sous-titrage anglais",
    ];

    // Normalisation : vf1, vf2, ve1, vo2... → vf, ve, vo...
    $l = preg_replace('/^(v[a-z]+)\d+$/i', '$1', $l);

    // Correspondance exacte
    if (isset($mapping[$l])) return $mapping[$l];

    // Décomposition : ex "vostfr" → "vo" + "stfr"
    $prefixes = ["vo", "vj", "vf", "ve", "va"];
    $suffixes = ["stfr", "sten", "sta"];

    foreach ($prefixes as $prefix) {
        foreach ($suffixes as $suffix) {
            if ($l === $prefix . $suffix) {
                return $mapping[$prefix] . ' | ' . $mapping[$suffix];
            }
        }
    }

    return $l;
}

// -----------------
$videoChannel_____['src'] = json_decode(htmlspecialchars_decode($videoChannel_____['src']), true);
$videoChannel_____['lang_'] = json_decode(htmlspecialchars_decode($videoChannel_____['lang_']), true);

$videoLang = [
    'vf' => [],
    'vf2' => [],
];
$videoInfo = null;

foreach ($videoChannel_____['src'] as $value) {
    $value['src'] = str_replace("'", "", htmlspecialchars_decode($value['src']));
    $value['lang'] = strtolower(htmlspecialchars_decode($value['lang']));

    $entry = [
        'name' => 'Source ' . (getBaseDomainName($value['src']) ?? $value['lang']),
        'src'  => $value['src'],
        'lang' => $value['lang']
    ];
    $videoLang[$value['lang']][$value['src']] = $entry;

    $currentLangScore   = isset($videoInfo) ? __EnnWatch->getLangScore($videoInfo['lang'])  : PHP_INT_MAX;
    $currentSourceScore = isset($videoInfo) ? __EnnWatch->getSourceScore($videoInfo['src']) : PHP_INT_MAX;
    $newLangScore   = __EnnWatch->getLangScore($value['lang']);
    $newSourceScore = __EnnWatch->getSourceScore($value['src'] ?? '');
    if ( 
        $newLangScore < $currentLangScore ||
        ($newLangScore === $currentLangScore && $newSourceScore < $currentSourceScore)
    ) {
        $videoInfo = $entry;
    }
}

$next = __EnnWatch->nextVideo($channelInfo['uniqid'], $videoChannel_____['episode'], $videoChannel_____['saison'], $typeVideo);
$back = __EnnWatch->backVideo($channelInfo['uniqid'], $videoChannel_____['episode'], $videoChannel_____['saison'], $typeVideo);


__OmsyInterface->setTitle("{$channelInfo['title']} - Episode {$videoChannel_____['episode']}  - Saison {$videoChannel_____['saison']} - Eiga No Neko");

$embeding['description'] = htmlspecialchars_decode($channelInfo['description']) ?? '';
$embeding['keywords'] = htmlspecialchars_decode(implode(',', $channelInfo['genres'] ?? []));
$embeding['image'] = __EnnMain->cdnImageUrl . 'poster/' . ($channelInfo['slug'] ?? '') . '.jpg';

function getBaseDomainName($url) {
    $pattern = '/(?:https?:\/\/)?(?:www\.)?([^\/]+)/i';
    if (preg_match($pattern, $url, $matches)) {
        $domainParts = explode('.', $matches[1]);
        $dom = $domainParts[0].'.'.$domainParts[1];
        switch(count($domainParts)) {
            case 3:
                $dom = $domainParts[1].'.'.$domainParts[2];
                break;
        }
        return $dom;
    }
    return "Player #";
}


include 'interface/watch.php';