<?php
//CMS->Accounts->connect();
$message = null;
if(isset($_POST['send'])):
	if(
		!empty($_POST['username'])
		&&
		!empty($_POST['password'])
	):
		$username = ($_POST['username']);
		$password = ($_POST['password']);

		$isConnect = __OmsyAccounts->create([
			'username' => $username,
			'password' => $password
		]);

		if($isConnect['err'] == null) {
			$message = '';
			if($isConnect['account']['role'] == 3) header('Location: /panel/');
			else header('Location: /');
		} else {
			$message = $isConnect['err'];
		}
		
	else:
		$message = 'Information incorrect.';
	endif;
endif;

//var_dump($_SESSION);

__OmsyInterface->setTitle('S\'inscrire sur Eiga No Neko');
include 'interface/signup.php';
?>