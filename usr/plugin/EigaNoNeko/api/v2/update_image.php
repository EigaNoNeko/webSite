<?php
$existes = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE slug_ = ?', [$_POST['catalogue']])->fetchAll();
if(isset($existes[0])) {
	$poster = 'share/content-delivry/image/poster-'.uniqid().'.jpg';
	$thumbnail = 'share/content-delivry/image/thumbnail-'.uniqid().'.jpg';
	$hero = 'share/content-delivry/image/hero-'.uniqid().'.jpg';

	$imageData = file_get_contents($_POST['url']);
	$thumbnailAndHero = file_get_contents("https://raw.githubusercontent.com/Anime-Sama/IMG/img/contenu/{$_POST['catalogue']}.jpg");
	if ($imageData !== false) {
		file_put_contents(__Root . $poster, $imageData);
		
		file_put_contents(__Root . $thumbnail, $thumbnailAndHero);
		file_put_contents(__Root . $hero, $thumbnailAndHero);

		__EnnCatalogues->addObj($existes[0]['uniqid'], "img", 'poster', '/' . $poster);
		__EnnCatalogues->addObj($existes[0]['uniqid'], "img", 'thumbnail', '/' . $thumbnail);
		__EnnCatalogues->addObj($existes[0]['uniqid'], "img", 'hero', '/' . $hero);
	}

	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false]);
}