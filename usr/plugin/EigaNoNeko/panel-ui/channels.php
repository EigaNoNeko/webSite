<?php
$allCatalogue = __OmsySql->sqlPagex('enncore_catalogues', 'ORDER BY ID DESC');

$buff = [];
foreach($allCatalogue['items'] as $key => $value):
	$value = __EnnCatalogues->get($value['uniqid'], 'uniqid', [
        "description"=>true,
        "image"=>['thumbnail']
    ]);
	$value['img'] = $value['img'] ?? [];
	$allCatalogue['items'][$key] = $value;

    $buff[] = [
        "title" => '<a class="username_avatar" href="/panel/getter?plugin='.__ACTIF_PLUGIN__.'&page=watchs&channel='.$value['uniqid'].'">
            <span class="image"><img src="'.__EnnMain->cdnImageUrl.'poster/'.($value['slug'] ?? '').'.jpg" width="32px" height="32px" style="border-radius: 4px; object-fit: cover;"></span>
            <div class="text">
                <span class="text">'.(__OmsyString->htmlDecode($value['title'] ?? 'Data is ot found')).'</span>
                <span class="sub_text">'.(__OmsyString->substr(__OmsyString->htmlDecode($value['description'] ?? 'Data is ot found'), 85)).'</span>
            </div>
        </a>',
        "omsyPanel.table.options" => [
        	"edit" => [
                "value" => $value['uniqid'],
                "page" => "channel"
            ],
        	"remove" => [
                "value" => $value['uniqid'],
                "page" => "channels"
            ],
        	"view" => [
                "value" => $value['uniqid'],
                "page" => "channel"
            ],
        	"view2" => [
                "value" => $value['uniqid'],
                "page" => "channel"
            ],
            "copy" => [
                "value" => $value['uniqid'],
                "page" => "channel"
            ],
        ]
    ];
endforeach;

if(isset($_GET['remove'])):
    omsp_header();
?>
<script>
Omsy.request('/api/EigaNoNeko/v3/delete_catalogue_all', {
    id: '<?= $_GET['remove'] ?>'
})
.then((e) => {
    console.log(e)
})
</script>
<?php
    omsp_footer();
else:
    include 'use/channels.php';
endif;