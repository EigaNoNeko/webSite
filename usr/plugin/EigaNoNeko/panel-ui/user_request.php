<?php
if(isset($_GET['remove'])) {
    __OmsySql->execute('DELETE FROM enncore_tmp_catalogue WHERE ID = ?', [$_GET['rm']]);
}
$allDepot = __OmsySql->execute('SELECT * FROM enncore_tmp_catalogue')->fetchAll();
$buff = [];
foreach ($allDepot as $value) {
    if(isset($bff[$value['name']]['tot'])) $bff[$value['name']]['tot']++;
    
    $bff[$value['name']] = $value;
    if(!isset($bff[$value['name']]['tot'])) $bff[$value['name']]['tot'] = 1;

    $buff[] = [
        "content" => $value['name'],
        "date" => __OmsyMain->GFunction->convertDate($value['date_']),
        "omsyPanel.table.options" => [
            "remove" => [
                "value" => $value['ID'],
                "page" => "channels"
            ],
        ]
    ];
}
?>
<?= omsp_header() ?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Liste des demandes utilisateur</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul>
            <li>
                <a href="/panel/getter?plugin=omsyBlog&page=post">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M460-460H240v-40h220v-220h40v220h220v40H500v220h-40v-220Z"/></svg>
                    </span>
                    <span class="name">Créer un article</span>
                </a>
            </li>
        </ul>
    </div>
</section>
<section class="kit-contenaire">
<?php
SQLX_table([
    "head" => [
        "Content",
        "Date"
    ],
    "body" => $buff
]);
?>
</section>
<?= omsp_footer() ?>