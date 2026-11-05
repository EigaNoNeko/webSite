<?php
define('__domaine__', 'https://www.eiganoneko.com/');
// Configuration des paramètres
$uploadDir = '/share/uploads/';
$outputDir = 'share/content-delivry/image/';

$defaultImages = [
	'thumbnail' => [
		'name' => "Petite miniature serie",
		"file" => $buffImg['thumbnail'] ?? null
	],
	'poster' => [
		'name' => "Grande miniature serie",
		"file" => $buffImg['poster'] ?? null
	],
	'hero' => [
		'name' => "Bannière page détail",
		"file" => $buffImg['hero'] ?? null
	],
	'home' => [
		'name' => "Bannière page d'acceuil",
		"file" => $buffImg['home'] ?? null
	]
];
$defaultLang = [
	'english' => 'Anglais',
	'french' => 'Français',
	'japanase' => 'Japonais'
];


if(isset($_GET['channel'])) {
	$catalogueInfo = CMS->Plugins->ennNode->catalogueInfo($_GET['channel']);
}

if(isset($_POST['send_form_lang'])):
	if(isset($catalogueInfo['uniqid'])) {
		$uniqid = $catalogueInfo['uniqid'];
		$slugify = $catalogueInfo['slug_'];
	}
	else
		$uniqid = uniqid();

	if(
		!empty($_POST['default_name'])
		&&
		!empty($_POST['default_categorie'])
	):
		$name = htmlentities($_POST['default_name']);
		$slugify = CMS->GFunction->slugify($name);

		$categorie = htmlentities($_POST['default_categorie']);
		$parent = htmlentities($_POST['parent']);

		if(isset($catalogueInfo['uniqid'])) {
			/* CMS->DataBase->execute(
				'UPDATE pvs_channel SET uniqid = ?, title = ?, categorie = ?, parent_ = ? WHERE uniqid = ?',
				[$slugify, $name, $categorie, $parent, $catalogueInfo['uniqid']]
			); */
			CMS->DataBase->execute(
				'UPDATE enncore_catalogue SET slug_ = ?, title = ?, categorie = ?, parent_ = ? WHERE uniqid = ?',
				[$slugify, $name, $categorie, $parent, $catalogueInfo['uniqid']]
			);
		} else {
			$channelExist = CMS->DataBase->execute('SELECT * FROM enncore_catalogue WHERE slug_ = ?', [$slugify])->fetchAll();
			if(count($channelExist) > 0) {
				$getChannelExist = $channelExist[0];
				die(header('Location: /panel/watchs.php?channel='.$getChannelExist['uniqid']));
			}

			CMS->DataBase->execute(
				"INSERT INTO
					enncore_catalogue (uniqid, slug_, parent_, title, categorie, view, episode, saison, date_, last_)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), now())
				",
				[
					$uniqid,
					$slugify,
					$parent,
					$name,
					$categorie,
					0,
					1,
					1
				]
			);
		}
	endif;

	if(
		isset($_POST['arr_lang'])
		&&
		isset($_POST['arr_name'])
		&&
		isset($_POST['arr_content'])
	):
		if(is_array($_POST['arr_lang']) && is_array($_POST['arr_name']) && is_array($_POST['arr_content'])):
			if(isset($slugify)):
				$arr_name = $_POST['arr_name'];
				$arr_content = $_POST['arr_content'];

				CMS->DataBase->execute('DELETE FROM enncore_catalogue_obj WHERE type = "channel-title" AND channel_id = ?', [$uniqid]);
				CMS->DataBase->execute('DELETE FROM enncore_catalogue_obj WHERE type = "channel-description" AND channel_id = ?', [$uniqid]);

				foreach ($_POST['arr_lang'] as $key => $value) {
					CMS->DataBase->execute('INSERT INTO enncore_catalogue_obj (channel_id, type, name, content) VALUES(?, "channel-title", ?, ?)', [$uniqid, $value, htmlentities($arr_name[$key])]);
					CMS->DataBase->execute('INSERT INTO enncore_catalogue_obj (channel_id, type, name, content) VALUES(?, "channel-description", ?, ?)', [$uniqid, $value, htmlentities($arr_content[$key])]);
				}
			endif;
		endif;
	endif;

	if(
		!empty($_FILES['image']['tmp_name'][0])
		||
		!empty($_FILES['image']['tmp_name'][1])
		||
		!empty($_FILES['image']['tmp_name'][2])
		||
		!empty($_FILES['image']['tmp_name'][3])
	):
		$filesImag = $_FILES['image'];
		$typeImag = $_POST['type'];
		var_dump($filesImag);
		foreach ($filesImag['tmp_name'] as $key => $value) {
			if(empty($value)) continue;

			$tmp_files = $value;
			$tmp_name = $value;
			$tmp_type = $_POST['type'][$key];


			//$filePath = handleFileUpload($uploadedFile, $uploadDir);
			list($sourceImage, $originalWidth, $originalHeight) = createImageResource($filesImag['tmp_name'][$key]);
			list($targetWidth, $targetHeight) = getTargetDimensions($tmp_type, $originalWidth, $originalHeight);

			$resizedImage = resizeImage($sourceImage, $originalWidth, $originalHeight, $targetWidth, $targetHeight);
			$outputFilePath = saveAsWebP($resizedImage, $outputDir, $tmp_type);

			//var_dump($tmp_name, $tmp_type);
			//echo "Image convertie et redimensionnée avec succès : <a href='$outputFilePath'>$outputFilePath</a>";

			$chackFile = CMS->DataBase->execute('INSERT INTO enncore_catalogue_obj (channel_id, type, name, content) VALUES(?, "img", ?, ?)',
			[
				$uniqid,
				$tmp_type,
				__domaine__ . $outputFilePath
		    ]);

		    imagedestroy($sourceImage);
    		imagedestroy($resizedImage);
		}
	endif;
endif;

include "interface/channel.php";






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

// Fonction pour redimensionner l'image
function resizeImage($sourceImage, $originalWidth, $originalHeight, $targetWidth, $targetHeight) {
    $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
    if (!$resizedImage) {
        throw new Exception("Erreur lors de la création de l'image redimensionnée.");
    }

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