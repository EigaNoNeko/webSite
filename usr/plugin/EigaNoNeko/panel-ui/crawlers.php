<?php
set_time_limit(1000);
// header('Content-type: text/plain');

// $data = __OmsySql->execute('SELECT * FROM as_crawler WHERE taille > 0')->fetchAll();
// foreach ($data as $value) {
// 	$existes = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$value['catalogue']])->fetchAll();
// 	if(isset($existes[0])) continue;

// 	$uniqid = uniqid();
// 	$slugify = $value['catalogue'];

// 	$value['data'] = json_decode(html_entity_decode($value['data']), true);

// 	$name = $value['data']['title'];
// 	$content = $value['data']['description'];
// 	$categorie = $value['data']['genres'][0];
// 	__EnnCatalogues->create($uniqid, $slugify, NULL, $categorie);
// 	__EnnCatalogues->addObj($uniqid, "channel-title", 'french', $name);
// 	__EnnCatalogues->addObj($uniqid, "channel-description", 'french', $content);
// 	var_dump($value);
// }
// die();

$allCatalogue = __OmsySql->sqlPagex('as_crawler', 'ORDER BY source ASC');
$buff = [];
foreach($allCatalogue['items'] as $key => $value):
	$existes = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$value['catalogue']])->fetchAll();
	$stat = '';
	if(isset($existes[0])) $stat = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>';
	$buff[] = [
        //"image" => $value['img']['thumbnail'] ?? '/panel/interface/image/avatar/haha.png',
        "etat" => $stat,
        "source" => $value['source'],
        "source_type" => $value['source_type'],
        "catalogue" => $value['catalogue'],
        "taille" => $value['taille'],
        "omsyPanel.table.options" => [
        	"edit" => [
                "value" => $value['ID'],
                "page" => "channel"
            ],
        	"remove" => [
                "value" => $value['ID'],
                "page" => "channels"
            ]
        ]
    ];
endforeach;
__OmsyMain->addModule('OmsyUrl');

omsp_header();
// $characters = [];
// $staff = [];
// foreach ($allCatalogue['items'] as $key => $value) {
//     $existes = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$value['source']])->fetchAll();
//     if(!isset($existes[0])) continue;
//     // Recherche par nom
//     $da = __OmsyUrl->request('https://api.jikan.moe/v4/anime?q='.urlencode(str_replace('-', ' ', $value['source'])).'&limit=1');
//     $da = json_decode(htmlspecialchars_decode($da), true);
//     if (!isset($da['data'][0])) continue;

//     $malId = $da['data'][0]['mal_id'];

//     // Détail complet
//     $mal = __OmsyUrl->request("https://api.jikan.moe/v4/anime/{$malId}/full");
//     $mal = json_decode(htmlspecialchars_decode($mal), true);
//     sleep(1);

//     // Personnages + acteurs voix
//     $characters = __OmsyUrl->request("https://api.jikan.moe/v4/anime/{$malId}/characters");
//     $characters = json_decode(htmlspecialchars_decode($characters), true)['data'] ?? [];
//     foreach ($characters as $char) {
//         $character = $char['character'];
//         $voiceActors = $char['voice_actors'];
//         $ennid = uniqid();

//         $voiceAct = [];
//         foreach ($voiceActors as $va) {
//             $voiceActorId = uniqid();
//             $voiceAct[$va['language']][] = $voiceActorId;

//             __OmsySql->execute(
//                 'INSERT INTO enncore_characters(uniqid, catalogue_id, name, role, voice_actors, about) VALUES(?, ?, ?, ?, "", "voice_actor")',
//                 [$voiceActorId, '', $va['person']['name'], '']
//             );

//             $imageUrl = $va['person']['images']['jpg']['image_url'];
//             if (!empty($imageUrl)) {
//                 $imageData = file_get_contents($imageUrl);
//                 if ($imageData !== false) {
//                     file_put_contents(__Root . 'share/_omsy/download/voice-actor_' . $voiceActorId . '.jpg', $imageData);
//                 }
//             }
//         }

//         __OmsySql->execute(
//             'INSERT INTO enncore_characters(uniqid, catalogue_id, name, role, voice_actors, about) VALUES(?, ?, ?, ?, ?, "character")',
//             [$ennid, $existes[0]['uniqid'], $character['name'], $char['role'], json_encode($voiceAct, JSON_UNESCAPED_UNICODE)]
//         );
//         $imageUrl = $character['images']['jpg']['image_url'];
//         if (!empty($imageUrl)) {
//             $imageData = file_get_contents($imageUrl);
//             if ($imageData !== false) {
//                 file_put_contents(__Root . 'share/_omsy/download/' . $existes[0]['slug_'] . '_' . $ennid . '.jpg', $imageData);
//             }
//         }
//     }
//     sleep(1);

//     // Staff
//     $staff = __OmsyUrl->request("https://api.jikan.moe/v4/anime/{$malId}/staff");
//     $staff = json_decode(htmlspecialchars_decode($staff), true)['data'] ?? [];
//     $staffs = [];
//     foreach ($staff as $member) {
//         $person = $member['person'];
//         $positions = implode(', ', $member['positions']);

//         if(isset(__OmsySql->execute('SELECT * FROM enncore_characters WHERE name = ?', [$person['name']])->fetchAll()[0])) continue;

//         $staffId = uniqid();

//         __OmsySql->execute(
//             'INSERT INTO enncore_characters(uniqid, catalogue_id, name, role, voice_actors, about) VALUES(?, "", ?, "", "", "staff")',
//             [$staffId, $person['name']]
//         );
//         $imageUrl = $person['images']['jpg']['image_url'];
//         if (!empty($imageUrl)) {
//             $imageData = file_get_contents($imageUrl);
//             if ($imageData !== false) {
//                 file_put_contents(__Root . 'share/_omsy/download/staff_' . $staffId . '.jpg', $imageData);
//             }
//         }

//         $staffs[] = [
//             "id" => $staffId,
//             "role" => $positions
//         ];
//     }
//     if(count($staffs) > 0) {
//         __OmsySql->execute(
//             'UPDATE enncore_catalogues SET staff = ? WHERE uniqid = ?',
//             [json_encode($staffs, JSON_UNESCAPED_UNICODE), $existes[0]['uniqid']]
//         );
//     }
//     sleep(1);
// }

SQLX_pagetab($allCatalogue);
SQLX_table([
    "head" => [
        "etat",
        "source",
        "source_type",
        "catalogue",
        "taille",
    ],
    "body" => $buff
]);
SQLX_pagetab($allCatalogue);
omsp_footer();