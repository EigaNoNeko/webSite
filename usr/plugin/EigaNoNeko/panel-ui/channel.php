<?php
// Configuration des paramètres
$uploadDir = '/share/uploads/';
$outputDir = 'share/content-delivry/image/';

if(isset($_GET['edit'])) $catalogueInfo = __EnnCatalogues->get($_GET['edit'], 'uniqid', [
	'added_date' => true,
	'title' => true,
	'description' => true,
	'genres' => true,
	'object' => true
]);

$defaultImages = [
	'thumbnail' => [
		'name' => "Miniature",
		"file" => $catalogueInfo['image']['thumbnail'] ?? '/use/img/thumbnail.png'
	],
	'poster' => [
		'name' => "Affiche",
		"file" => $catalogueInfo['image']['poster'] ?? '/use/img/poster.png'
	],
	'hero' => [
		'name' => "Bannière",
		"file" => $catalogueInfo['image']['hero'] ?? '/use/img/thumbnail.png'
	],
	'logo' => [
		'name' => "Logo",
		"file" => $catalogueInfo['image']['logo'] ?? '/use/img/logo.png'
	]
];
$defaultLang = [
	'english' => 'Anglais',
	'french' => 'Français',
	'japanase' => 'Japonais'
];

if(isset($_POST['send_form_lang'])):
	if(isset($catalogueInfo['uniqid'])) {
		$uniqid = $catalogueInfo['uniqid'];
		$slugify = $catalogueInfo['slug'];
	}
	else $uniqid = uniqid();

	$slugify = null;


	if(
		isset($_POST['arr_lang'])
		&&
		isset($_POST['arr_name'])
		&&
		isset($_POST['arr_content'])
	):
		if(is_array($_POST['arr_lang']) && is_array($_POST['arr_name']) && is_array($_POST['arr_content'])):
			//if(isset($slugify)):
			$arr_name = $_POST['arr_name'];
			$arr_content = $_POST['arr_content'];

			__EnnCatalogues->rmObj("channel-title", $uniqid);
			__EnnCatalogues->rmObj("channel-description", $uniqid);

			foreach ($_POST['arr_lang'] as $key => $value) {
				if(!isset($slugify)) $slugify = __OmsyString->slugify($arr_name[$key]);

				__EnnCatalogues->addObj($uniqid, "channel-title", $value, $arr_name[$key]);
				__EnnCatalogues->addObj($uniqid, "channel-description", $value, $arr_content[$key]);
			}
			//endif;
		endif;
	endif;

	var_dump($slugify, !empty($_POST['default_categorie']));

	if(
		isset($slugify)
		&&
		!empty($_POST['default_categorie'])
	):
		$categorie = htmlentities($_POST['default_categorie']);
		$parent = htmlentities($_POST['parent']);

		if(isset($catalogueInfo['uniqid'])) {
			__EnnCatalogues->updateSlug($slugify, $catalogueInfo['uniqid']);
			__EnnCatalogues->updateTags($categorie, $catalogueInfo['uniqid']);
			__EnnCatalogues->updateParent($parent, $catalogueInfo['uniqid']);
		} else {
			$channelExist = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$slugify])->fetchAll();
			if(count($channelExist) > 0) {
				$getChannelExist = $channelExist[0];
				die(header('Location: /panel/watchs.php?channel='.$getChannelExist['uniqid']));
			}

			__EnnCatalogues->create($uniqid, $slugify, $parent, $categorie);

			echo 'dddd';
			die();
		}
	endif;
endif;

include "use/channel.php";






// Fonction pour gérer l'upload du fichier
function handleFileUpload($uploadedFile, $uploadDir) {
    if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Erreur lors de l'upload du fichier : " . $uploadedFile['error']);
    }

    $filePath = __root__ . $uploadDir . basename($uploadedFile['name']);
    if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
        throw new Exception("Échec du déplacement du fichier.");
    }

    return $filePath;
}

// Fonction pour obtenir les dimensions et créer la ressource image
function createImageResource($filePath) {
	var_dump($filePath);
    $imageInfo = getimagesize($filePath);
    if (!$imageInfo) {
        throw new Exception("Impossible de lire les dimensions de l'image.");
    }

    list($width, $height, $type) = $imageInfo;

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($filePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($filePath);
            break;
        default:
            throw new Exception("Format de fichier non pris en charge.");
    }

    if (!$image) {
        throw new Exception("Erreur lors de la création de la ressource d'image.");
    }

    return [$image, $width, $height];
}

// Fonction pour redimensionner l'image avec transparence
function resizeImage($sourceImage, $originalWidth, $originalHeight, $targetWidth, $targetHeight) {
    $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
    if (!$resizedImage) {
        throw new Exception("Erreur lors de la création de l'image redimensionnée.");
    }

    // 🔹 Préservation de la transparence (PNG & GIF)
    imagealphablending($resizedImage, false);
    imagesavealpha($resizedImage, true);
    $transparentColor = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
    imagefilledrectangle($resizedImage, 0, 0, $targetWidth, $targetHeight, $transparentColor);

    // 🔹 Copie et redimensionnement de l'image source
    if (!imagecopyresampled(
        $resizedImage,
        $sourceImage,
        0, 0, 0, 0,
        $targetWidth,
        $targetHeight,
        $originalWidth,
        $originalHeight
    )) {
        throw new Exception("Erreur lors du redimensionnement.");
    }

    return $resizedImage;
}


// Fonction pour déterminer les dimensions cibles
function getTargetDimensions($typeUpload, $originalWidth, $originalHeight) {
    switch ($typeUpload) {
        case 'thumbnail':
            return [500, 280];
        case 'hero':
            return [1920, 1080];
        case 'home':
        case 'logo':
            return [$originalWidth, $originalHeight];
        case 'poster':
        case 'cover':
            return [720, 1080];
        default:
            throw new Exception("Type d'upload non pris en charge.");
    }
}

// Fonction pour sauvegarder l'image au format WebP
function saveAsWebP($resizedImage, $outputDir, $typeUpload) {
    $outputFilePath = $outputDir . $typeUpload . '-' . uniqid() . '.webp';
    if (!imagewebp($resizedImage, __root__ . $outputFilePath)) {
        throw new Exception("Erreur lors de la sauvegarde de l'image WebP.");
    }
    return $outputFilePath;
}