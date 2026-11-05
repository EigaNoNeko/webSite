<?php
$ri = 'usr/template/';
if(isset($_GET['active'])) {
	$name = htmlentities($_GET['active']);

	$folderTemplateJSON = __Root . $ri . $name . '/template.json';
	if(!file_exists($folderTemplateJSON)) die('Not existe !' . $folderTemplateJSON);

	$getConfig = json_decode(file_get_contents($folderTemplateJSON));

	$getConfig->id = $name;
	$name = $getConfig->name;

	$plugins = ['omsyPanel'];

	foreach ($getConfig->install->plugin as $value) {
		$plugins[] = $value->id;
	}
	__OmsyMain->setTemplate($getConfig, ['omsyPanel']);
}

$buff = [];

$folderTemplate = scandir(__Root . $ri);
foreach($folderTemplate as $value) {
	if(in_array($value, ['.', '..', '.DS_Store'])) continue;
	if(str_contains($value, ".old")) continue;
	if(!file_exists(__Root . $ri . $value . '/' . 'template.json')) continue;

	$config = file_get_contents(__Root . $ri . $value . '/' . 'template.json');
	$config = json_decode($config);

	if(isset($config->disabled)) continue;

	$buff[] = [
        "title" => '<a class="username_avatar">
            <span class="image"><img src="'.$config->thumbnail.'" width="48px" height="48px" style="border-radius: 4px; object-fit: cover;"></span>
            <div class="text">
                <span class="text">'.$config->name.'</span>
                <span class="sub_text">Created by '.$config->author.'</span>
            </div>
        </a>',
        "omsyPanel.table.options" => [
            "active" => [
                "value" => $value,
                "page" => "template"
            ]
        ]
	];
}

__OmsyInterface->setTitle('Mes thème');
include "interface/template.php";
?>