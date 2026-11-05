<?php
if(isset($_GET['clear'])) {
	// __OmsySql->execute('DELETE FROM enncore_players WHERE catalogue_id = ?', [$_GET['channel']]);
}

$data = __EnnCatalogues->get($_GET['channel'], 'uniqid', [
	"object" => true,
	"description" => true,
	"genres" => true,
	"image" => ["poster", "thumbnail", "hero", "logo"],
	"statistics" => ['saison', 'view', 'list', 'episode']
]);
// var_dump($data);
$buff = [];
for ($i=1; $i <= $data['statistics']['saison']; $i++) {
	foreach (__OmsySql->execute('SELECT * FROM enncore_players WHERE saison = ? AND catalogue_id = ? ORDER BY CAST(episode AS UNSIGNED)', [$i, $data['uniqid']])->fetchAll() as $value) {
		$buff[] = [
			"url" => $value['src'],
			"saison" => $value['saison'],
			"episode" => $value['episode'],
			"langue" => $value['lang_'],
			"omsyPanel.table.options" => [
				"edit" => [
					"value" => $value['uniqid'],
					"page" => "watch"
				],
				"remove" => [
					"value" => $value['uniqid'],
					"page" => "watchs"
				],
			]
		];
	}
	
	continue;
}
?>
<?= omsp_header() ?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Liste des players</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul>
            <li>
                <a href="/panel/getter?plugin=EigaNoNeko&page=watch&channel=<?= $_GET['channel'] ?>">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M304.62-160q-26.85 0-45.74-18.88Q240-197.77 240-224.62V-720h-40v-40h160v-30.77h240V-760h160v40h-40v495.38q0 27.62-18.5 46.12Q683-160 655.38-160H304.62ZM680-720H280v495.38q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h350.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-720ZM392.31-280h40v-360h-40v360Zm135.38 0h40v-360h-40v360ZM280-720v520-520Z"/></svg>
                    </span>
                    <span class="name">Ajouter une vidéos</span>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=EigaNoNeko&page=watchs&channel=<?= $_GET['channel'] ?>&clear=true">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M304.62-160q-26.85 0-45.74-18.88Q240-197.77 240-224.62V-720h-40v-40h160v-30.77h240V-760h160v40h-40v495.38q0 27.62-18.5 46.12Q683-160 655.38-160H304.62ZM680-720H280v495.38q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h350.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-720ZM392.31-280h40v-360h-40v360Zm135.38 0h40v-360h-40v360ZM280-720v520-520Z"/></svg>
                    </span>
                    <span class="name">Supprimer les vidéos</span>
                </a>
            </li>
        </ul>
    </div>
</section>
<section class="kit-contenaire">
<?php
SQLX_table([
	"head" => [
		"url",
		"saison",
		"episode",
		"langue",
	],
	"body" => $buff
]);
?>
</section>
<?= omsp_footer() ?>