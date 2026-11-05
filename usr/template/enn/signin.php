<?php
$message = null;
if(isset($_POST['send'])):
	if(
		!empty($_POST['username'])
		&&
		!empty($_POST['password'])
	):
		$username = ($_POST['username']);
		$password = ($_POST['password']);

		$isConnect = __OmsyAccounts->connect([
			'username' => $username,
			'password' => $password
		]);

		if($isConnect['err'] == null) {
			die(header('Location: /'));
			// header('Location: /switchAccount');
		} else {
			$message = $isConnect['err'];
		}
	else:
		$message = 'Information incorrect.';
	endif;
endif;

//var_dump($_SESSION);

__OmsyInterface->setTitle('Ce connecter à Eiga No Neko');

include 'interface/signin.php';
?>