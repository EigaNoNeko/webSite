<?php
// JS
$apiKey = __OmsyMain->__Applications->discord->botkey;
$options = [
    "http" => [
        "method" => "GET",
        "header" => "x-api-key: $apiKey\r\n"
    ]
];

$context = stream_context_create($options);

$getApi = file_get_contents(
    "http://127.0.0.1:3000/api/get_channels",
    false,
    $context
);
$data = __OmsyJSON->decode($getApi, true)['data'];

// usort($data, function ($a, $b) {
//     return $b['position'] <=> $a['position'];
// });

$buff = [];
foreach ($data['categories'] as $value):
    $buff[] = [
        "name" => "
            <div class=\"username_avatar\">
                <span class=\"image\"><svg style=\"width: 16px; height: 16px;\" xmlns=\"http://www.w3.org/2000/svg\" height=\"24px\" viewBox=\"0 -960 960 960\" width=\"24px\" fill=\"#FFFFFF\"><path d=\"M221.91-140.22 263.61-307H100.78L127-413h162.83l33.78-134H160.78L187-653h162.83l41.69-166.22h106.57L456.39-653h133.44l41.69-166.22h106.57L696.39-653h162.83L833-547H670.17l-33.78 134h162.83L773-307H610.17l-41.69 166.78H461.91L503.61-307H370.17l-41.69 166.78H221.91ZM396.39-413h133.44l33.78-134H430.17l-33.78 134Z\"/></svg></span>
                <span class=\"text\">
                    <span class=\"text\">".$value['name'] ?? ''."</span>
                </span>
            </div>",
        "Access Level" => "<ul>{$value['type']}</lu>",
        "omsyPanel.table.options" => [
            "copy" => [
                "value" => $value['id'],
                "page" => "members"
            ],
        ]
    ];
endforeach;
foreach ($data['categories'] as $value):
	$buff[] = [
        "name" => "
            <div class=\"username_avatar\">
                <span class=\"image\"><svg style=\"width: 18px; height: 18px;\" xmlns=\"http://www.w3.org/2000/svg\" height=\"24px\" viewBox=\"0 -960 960 960\" width=\"24px\" fill=\"#FFFFFF\"><path d=\"M166.78-140.78q-44.3 0-75.15-30.85-30.85-30.85-30.85-75.15v-466.44q0-44.3 30.85-75.15 30.85-30.85 75.15-30.85h224.74L480-730.74h313.22q44.3 0 75.15 30.85 30.85 30.85 30.85 75.15v377.96q0 44.3-30.85 75.15-30.85 30.85-75.15 30.85H166.78Z\"/></svg></span>
                <span class=\"text\">
                    <span class=\"text\">".$value['name'] ?? ''."</span>
                </span>
            </div>",
        "Access Level" => "<ul>{$value['type']}</lu>",
        "omsyPanel.table.options" => [
        	"copy" => [
                "value" => $value['id'],
                "page" => "members"
            ],
        ]
    ];
    foreach ($value['channels'] as $channels):
        $buff[] = [
            "name" => "
                <div class=\"username_avatar\">
                    <span class=\"image\"><svg style=\"width: 16px; height: 16px;\" xmlns=\"http://www.w3.org/2000/svg\" height=\"24px\" viewBox=\"0 -960 960 960\" width=\"24px\" fill=\"#FFFFFF\"><path d=\"M221.91-140.22 263.61-307H100.78L127-413h162.83l33.78-134H160.78L187-653h162.83l41.69-166.22h106.57L456.39-653h133.44l41.69-166.22h106.57L696.39-653h162.83L833-547H670.17l-33.78 134h162.83L773-307H610.17l-41.69 166.78H461.91L503.61-307H370.17l-41.69 166.78H221.91ZM396.39-413h133.44l33.78-134H430.17l-33.78 134Z\"/></svg></span>
                    <span class=\"text\">
                        <span class=\"text\">".$channels['name'] ?? ''."</span>
                    </span>
                </div>",
            "Access Level" => "<ul>{$channels['type']}</lu>",
            "omsyPanel.table.options" => [
                "copy" => [
                    "value" => $channels['id'],
                    "page" => "members"
                ],
            ]
        ];
    endforeach;
endforeach;
// JS

omsp_header();
//var_dump($data[0]);
SQLX_table([
    "head" => [
        "Name",
        "Type"
    ],
    "body" => $buff
]);
omsp_footer();