<?php
$gC = [
	'uniqid' => null
];
$getChannelInfo = ['uniqid' => null];
$getChannelInfo['channel_id'] = '';

$isParent = false;
if(isset($_GET['parent'])) $isParent = true;

if(isset($_GET['watch'])) {
	$watchGetData = htmlentities($_GET['watch']);
}
if(isset($_GET['channel'])) $gC['channel_id'] = htmlentities($_GET['channel']);


if(isset($watchGetData)) {
	$playerInfo = CMS->Plugins->ennNode->playerInfo($watchGetData);
	$catalogueInfo = CMS->Plugins->ennNode->catalogueInfo($playerInfo['catalogue_id']);
}

if(isset($_POST['send_form'])) {
	if(!empty($_POST['channel'])) $channel = htmlentities($_POST['channel']);
	else $channel = $catalogueInfo['channel_id'];

	$thumbnail = htmlentities(json_encode([])); // DefaultData
	if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['tmp_name'])) $thumbnail = uploadImage($_FILES['thumbnail']);
	else if($isParent == true) $thumbnail = htmlentities(json_encode($gC['thumbnail']));

	$updaetSql = [
		"uniqid"		=> $_POST['uniqid_parent'] ?? uniqid(),
		"catalogue_id"	=> $channel,
		"thumbnail"		=> $thumbnail,
		"title"			=> "Episode ".intval($_POST['episode']),
		"episode"		=> intval($_POST['episode']) ?? 1,
		"saison"		=> intval($_POST['saison']) ?? 1,
		"src"			=> htmlentities($_POST['video']) ?? '',
		"mime"			=> htmlentities(json_encode([$_POST['iframe']])) ?? '["iframe"]',
		"quality"		=> htmlentities(json_encode([])),
		"lang_"			=> htmlentities($_POST['lang']) ?? 'vf'
	];

	//
	if(isset($playerInfo['uniqid']) && $isParent == false) {
		$uniqid = $gC['uniqid'];
		CMS->DataBase->execute('DELETE FROM enncore_player WHERE ID = ?', [$playerInfo['ID']]);
		/* CMS->DataBase->execute(
			'DELETE FROM pvs_subtitle WHERE watch_id = ?',
			[$uniqid]
		); */
	}

	// Create nouvelle données
	CMS->DataBase->execute(
		"INSERT INTO
			enncore_player (uniqid, catalogue_id, thumbnail, title, episode, saison, src, mime, quality, lang_)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		",
		[
			$updaetSql['uniqid'],
			$updaetSql['catalogue_id'],
			$updaetSql['thumbnail'],
			$updaetSql['title'],
			$updaetSql['episode'],
			$updaetSql['saison'],
			$updaetSql['src'],
			$updaetSql['mime'],
			$updaetSql['quality'],
			$updaetSql['lang_'],
		]
	);

	// Dernier date de mise à jour de la playlists
	CMS->DataBase->execute("UPDATE enncore_catalogue SET last_ = Now() WHERE uniqid = ?", [$updaetSql['catalogue_id']]);
}

include "interface/watch.php";



function uploadImage($PostedContent) {
	$uploadedFile = $PostedContent;

	$targetWidth = 500;
	$targetHeight = 280;

	$typeUpload = "thumbnail";

	// Obtenir les dimensions de l'image
	$imageInfo = getimagesize($uploadedFile['tmp_name']);
	list($originalWidth, $originalHeight, $imageType) = $imageInfo;

	// Créer une ressource d'image en fonction du type
	switch ($imageType) {
		case IMAGETYPE_JPEG:
			$sourceImage = imagecreatefromjpeg($uploadedFile['tmp_name']);
			break;
		case IMAGETYPE_PNG:
			$sourceImage = imagecreatefrompng($uploadedFile['tmp_name']);
			break;
		case IMAGETYPE_GIF:
			$sourceImage = imagecreatefromgif($uploadedFile['tmp_name']);
			break;
		default:
			echo "Format de fichier non pris en charge.";
			exit;
	}

	if (!$sourceImage) {
		echo "Erreur lors de la création de la ressource d'image.";
		exit;
	}

	// Créer une image vide aux dimensions cibles
	$resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
	if (!$resizedImage) {
		echo "Erreur lors de la création de l'image redimensionnée.";
		exit;
	}

		    // Redimensionner l'image source vers l'image cible
	if (!imagecopyresampled(
		$resizedImage,
		$sourceImage,
		0, 0, 0, 0,
		$targetWidth,
		$targetHeight,
		$originalWidth,
		$originalHeight
		)) {
		echo "Erreur lors du redimensionnement.";
		exit;
	}

		    
	// Chemin de sortie pour le fichier WebP
	$outputFilePath = 'share/content-delivry/image/' . $typeUpload . '-' . uniqid() . '.webp';
	var_dump($outputFilePath);
	// Enregistrer l'image au format WebP
	if (imagewebp($resizedImage, __root__.$outputFilePath)) {
		$thumbnail = htmlentities(json_encode([__domaine__.$outputFilePath]));
	}

	// Libérer la mémoire
	imagedestroy($sourceImage);
	imagedestroy($resizedImage);

	return $thumbnail;
}