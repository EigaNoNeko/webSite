<?php
$apiKey = __OmsyMain->__Applications->discord->botkey;

$options = [
    "http" => [
        "method" => "GET",
        "header" => "x-api-key: $apiKey\r\n"
    ]
];

$context = stream_context_create($options);

$getApi = __OmsyJSON->decode(file_get_contents(
    "http://127.0.0.1:3000/api/get_members?limit=".($_GET['q_limit'] ?? 8)."&page=".($_GET['pp'] ?? 1),
    false,
    $context
));

$buff = [];
foreach ($getApi->data as $key => $value):
	$roles = "";
    usort($value->roles, function ($a, $b) { return $b->position <=> $a->position; });
	foreach ($value->roles as $a) $roles .= "<li>{$a->name}</li>";

	$buff[] = [
        "name" => "
            <div class=\"username_avatar\">
                <span class=\"image\"><img src=\"".($value->avatar ?? '/panel/interface/image/avatar/haha.png')."\" width=\"48px\" style=\"border-radius: 4px;\"></span>
                <span class=\"text\">
                    <span class=\"text\">".($value->displayName ?? '')."</span>
                    <span class=\"sub_text\">".($value->username ?? 'Unknow')."</span>
                </span>
            </div>",
        "Access Level" => "<ul style=\"flex-wrap: wrap;\">$roles</lu>",
        "Joined" => $value->joinedAt ?? '',
        "Created date" => $value->joinedAt ?? '',
        "omsyPanel.table.options" => [
        	"copy" => [
                "value" => $value->id,
                "page" => "members"
            ],
        ]
    ];
endforeach;

omsp_header();
SQLX_pagetab([
    'page' => $getApi->current_page,
    'max' => $getApi->per_page,
    'totalePage' => $getApi->total_pages,
    'totaleItems' => $getApi->total_items,
]);
SQLX_table([
    "head" => [
        "Name" ,
        "Access Level" ,
        "Joined" ,
        "Created date" ,
    ],
    "body" => $buff
]);
SQLX_pagetab([
    'page' => $getApi->current_page,
    'max' => $getApi->per_page,
    'totalePage' => $getApi->total_pages,
    'totaleItems' => $getApi->total_items,
]);
omsp_footer();