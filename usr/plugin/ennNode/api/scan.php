<?php
$data = CMS->Plugins->ennNode->informationServerVideo($_POST['server']);
$datares = CMS->Plugins->ennNode->request('http://'.$data->adress.'/scan', [ "scan" => $_POST['scan'], "channel" => $_POST['channel'] ]);
$header = ["host" => null];
echo CMS->Plugins->ennNode->constructAPI($header, $datares);