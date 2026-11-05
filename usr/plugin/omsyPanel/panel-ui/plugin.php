<?php
$buff = [];
$ri = "usr/plugin/";
$folderTemplate = scandir(__root__ . $ri);


foreach($folderTemplate as $value) {
	if(in_array($value, ['.', '..', '.DS_Store'])) continue;
	if(!file_exists(__root__ . $ri . $value . '/' . 'template.json')) continue;

	$config = file_get_contents(__root__ . $ri . $value . '/' . 'template.json');
	$config = json_decode($config);

	if(isset($config->disabled)) continue;

	$config->id = $value;
	//$buff[] = $config;

	$buff[] = [
        "title" => $config->name,
        "Plugins" => "0",
        "omsyPanel.table.options" => [
            "active" => [
                "value" => $value,
                "page" => "plugin"
            ]
        ]
	];
}
include "interface/plugin.php";