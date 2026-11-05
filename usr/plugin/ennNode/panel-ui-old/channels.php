<?php
$allCatalogue = CMS->Plugins->ennNode->sqlPagex('enncore_catalogue', 'ORDER BY ID DESC');



foreach($allCatalogue['items'] as $key => $value):
	$catalogueObj = CMS->Plugins->ennNode->getAllObjectChannel($value['uniqid']);
	$allCatalogue['items'][$key]['information'] = $catalogueObj['channel-language']['default_item'];

	//var_dump($catalogueObj);

	$allCatalogue['items'][$key]['follow'] = count($catalogueObj['channel-follow'] ?? []);
	$allCatalogue['items'][$key]['like'] = count($catalogueObj['channel-like'] ?? []);
	$allCatalogue['items'][$key]['unlike'] = count($catalogueObj['channel-unlike'] ?? []);

endforeach;

include 'interface/channels.php';