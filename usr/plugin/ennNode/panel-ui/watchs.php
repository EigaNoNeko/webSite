<?php
if(isset($_GET['rm'])) {
    __OmsySql->execute('DELETE FROM enncore_player WHERE uniqid = ?', [$_GET['rm']]);
}

if(isset($_GET['channel'])) {
	$objId = htmlentities($_GET['channel']);
	$channels = __OmsySql->execute('SELECT * FROM enncore_catalogue WHERE uniqid = ?', [$objId]);
}
$getChannel = $channels->fetchAll()[0];

$channelMergeBuff = CMS->Plugins->ennNode->getAllObjectChannel($getChannel['uniqid']);
$getChannel['information'] = $channelMergeBuff['channel-language']['default_item'];

//
/*
$videosParPage = 10;
$reqAllVideoChannel = __OmsySql->execute('SELECT * FROM enncore_player WHERE catalogue_id = ? ORDER BY saison, episode ASC', [$objId])->fetchAll();
$countChannelVideo = count($reqAllVideoChannel);
$pagesTotales = ceil($countChannelVideo/$videosParPage);
if(isset($_GET['pp']) AND !empty($_GET['pp']) AND $_GET['pp'] > 0 AND $_GET['pp'] <= $pagesTotales) {
	$_GET['pp'] = intval($_GET['pp']);
	$pageCourante = $_GET['pp'];
} else {
	$pageCourante = 1;
}
$depart = ($pageCourante-1)*$videosParPage;
$listEp = __OmsySql->execute('SELECT * FROM enncore_player WHERE catalogue_id = ? ORDER BY saison, episode ASC LIMIT '.$depart.','.$videosParPage, [$objId])->fetchAll();
*/

$listEp = CMS->Plugins->ennNode->sqlPagex('enncore_player', "WHERE catalogue_id = '$objId' ORDER BY saison, episode ASC");
//

include "interface/watchs.php";