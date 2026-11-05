<?php
if(!isset($_SESSION['string_token'])) header('Location: /signin');

if(isset($_GET['set'])):
	$user = $_GET['set'];
	$userToken = htmlentities($_SESSION['string_token']);
	
	$reqUser = CMS->DataBase->execute(
		'SELECT * FROM account WHERE uniqid = ?',
		[$user]
	)->fetchAll();

	if(!$reqUser) return null;

	$reqUser = $reqUser[0];
	$_SESSION = $reqUser;
	$_SESSION['Role'] = intval($reqUser['role']);
	$_SESSION['string_token'] = $userToken;

	die(header('Location: /'));
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "include/item_head.php"; ?>
</head>
<body>
	<img class="image-switcher-logo" src="https://static.eiganoneko.com/content-delivry/channel/gogoanime.webp" height="32px">
	<section class="profil-switcher-contenaire">
		<h1>Changer de compte</h1>
		<ul>
			<?php foreach (CMS->Session->getAllSessionAccount() as $key => $value): ?>
			<li class="item-profil-switcher">
				<a href="/switchAccount?set=<?= $value['uniqid'] ?>">
					<div class="avatar">
						<img src="https://m.media-amazon.com/images/I/61UWQRbF5UL.jpg">
					</div>
					<div class="title">
						<h2><?= $value['username'] ?></h2>
					</div>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</section>
</body>
</html>