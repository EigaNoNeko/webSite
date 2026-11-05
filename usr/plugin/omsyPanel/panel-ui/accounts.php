<?php
$allCatalogue = __OmsySql->sqlPagex('omsy_account', 'ORDER BY ID DESC');
?>
<?= omsp_header() ?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Liste des comptes</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul></ul>
    </div>
</section>
<section class="kit-contenaire">
<?php
foreach ($allCatalogue['items'] as $key => $value):
    $value = array_merge($value, __OmsyAccounts->getAccountObject($value['uniqid']));

    $buff[] = [
        "name" => "
            <div class=\"username_avatar\">
                <span class=\"image\"><img src=\"".($value['user_avatar'] ?? '/panel/interface/image/avatar/haha.png')."\" width=\"32px\" height=\"32px\" style=\"border-radius: 4px; object-fit: cover;\"></span>
                <span class=\"text\">
                    <span class=\"text\">".$value['username']."</span>
                    <span class=\"sub_text\">".($value['user_mail'] ?? '')."</span>
                </span>
            </div>
        ",
        "Access Level" => $value['role'],
        "Last login" => __OmsyMain->GFunction->convertDate($value['last_login'] ?? '', ''),
        "Created date" => __OmsyMain->GFunction->convertDate($value['register_date'], ''),
        "omsyPanel.table.options" => [
            "edit" => [
                "value" => $value['uniqid'],
                "page" => "account"
            ],
            "remove" => [
                "value" => $value['uniqid'],
                "page" => "accounts"
            ],
            "view" => [
                "value" => $value['uniqid'],
                "page" => "account"
            ],
            "view2" => [
                "value" => $value['uniqid'],
                "page" => "account"
            ],
        ]
    ];
endforeach;
HMain([
    "name" => "Liste des comptes",
    "ul" => [
        [
            "id" => "accounts",
            "name" => "Home",
            "href" => "/panel/getter?plugin=omsyPanel&page=/template",
        ]
    ]
]);
SQLX_pagetab($allCatalogue);
SQLX_table([
    "head" => [
        "Name" ,
        "Access Level" ,
        "Last login" ,
        "Created date" ,
    ],
    "body" => $buff
]);
SQLX_pagetab($allCatalogue);
?>
</section>
<?php
omsp_footer();
?>