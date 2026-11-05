<?php
__OmsyInterface->setTitle('Modifier mon profile Eiga No Neko');

function updateUsername() {
	if(!isset($_POST['username'])) return null;
	if($_POST['username'] === __OmsyAccounts->username) return null;
	if(__OmsyAccounts->usernameVirify($_POST['username'])) {
		__OmsySql->execute(
			'UPDATE omsy_account SET username = ? WHERE uniqid = ?',
			[$_POST['username'], __OmsyAccounts->uniqid]
		);
	}
	return true;
}

function updatePassword() {
	if(!isset($_POST['password'])) return null;
	if(!isset($_POST['conf_password'])) return null;
	if(!isset($_POST['old_password'])) return null;
	__OmsyAccounts->updatePassword_($_POST['old_password'], $_POST['password'], $_POST['conf_password']);
	die(header('Location: /signout'));

	return true;
}

if(isset($_POST['submit'])) {
	//var_dump($_POST);
	if(!empty($_POST['username'])) $err = updateUsername();
	if(!empty($_POST['password'])) $err = updatePassword();

	if(isset($err) && $err === true) die(header('Location: /account?checked=true'));
}
?>
<?php include "interface/include/head.php"; ?>
<form method="post">
	<section class="enn-main-catalogue enn-grid-catalogue">
	<section class="left">
		<section class="enn-kit enn-kit-block-background enn-kit-block-style enn-kit-mgb depot-form-contenaire">
			<div class="head">
				<div>
					<h2>Modifier mon profile</h2>
					<?php if(isset($_GET['checked'])): ?>
					<font color="greeen">Vos information de profile on bien etait mise à jour</font>
					<?php endif; ?>
				</div>
			</div>
			<div class="content">
				<form class="enn-kit-form" method="post">
					<div class="enn-kit-input">
						<label>Username</label>
						<input type="text" name="username" value="<?= __OmsyAccounts->username ?>">
					</div>
					<div class="enn-kit-input enn-kit-input-margin">
						<label>Ancien mot de passe</label>
						<input type="text" name="old_password">
					</div>
					<div class="enn-kit-input enn-kit-input-margin">
						<label>Nouveau mot de passe</label>
						<input type="text" name="password">
					</div>
					<div class="enn-kit-input enn-kit-input-margin">
						<label>Confirmer le nouveau mot de passe</label>
						<input type="text" name="conf_password">
					</div>
					<div class="enn-kit-input-submit enn-kit-input-margin">
						<input type="submit" name="submit" value="Sauvegarder">
					</div>
					<div class="enn-kit-input-submit enn-kit-input-margin">
						<a href="/signout">Me deconnecter</a>
					</div>
				</form>
			</div>
		</section>
	</section>
</section>
<?php include "interface/include/footer.php"; ?>