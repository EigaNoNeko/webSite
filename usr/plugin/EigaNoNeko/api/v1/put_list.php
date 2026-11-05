<?php
$username = __OmsyAccounts->username ?? null;
if(!isset($username)) die(json_encode(['err' => 'login']));
$name=$_POST['name'];
$catalogue=$_POST['catalogue'];

$like = __EnnPlaylists->getItemListUser('like', $catalogue);
$unlike = __EnnPlaylists->getItemListUser('unlike', $catalogue);
//__EnnPlaylists->insertListUser($_POST['name'], $_POST['catalogue']);

if($name == 'like') {
	if($like) {
		__EnnPlaylists->deleteItemListUser('like', $catalogue);
		die(json_encode(['err' => null, 'msg' => 'like removed']));
	}
	else {
		__EnnPlaylists->insertListUser('like', $catalogue);
		if($unlike) {
			__EnnPlaylists->deleteItemListUser('unlike', $catalogue);
			die(json_encode(['err' => null, 'msg' => 'like Added && unlike removed']));
		}
		die(json_encode(['err' => null, 'msg' => 'like Added']));
	}
} else if($name == 'unlike') {
	if($unlike) {
		__EnnPlaylists->deleteItemListUser('unlike', $catalogue);
		die(json_encode(['err' => null, 'msg' => 'unlike removed']));
	}
	else {
		__EnnPlaylists->insertListUser('unlike', $catalogue);
		if($like) {
			__EnnPlaylists->deleteItemListUser('like', $catalogue);
			die(json_encode(['err' => null, 'msg' => 'Unlike Added && like removed']));
		}
		die(json_encode(['err' => null, 'msg' => 'Unlike Added']));
	}
} else {
	if(__EnnPlaylists->getItemListUser($name, $catalogue)) {
		__EnnPlaylists->deleteItemListUser($name, $catalogue);
		die(json_encode(['err' => null, 'msg' => 'Removed']));
	}
	else {
		__EnnPlaylists->insertListUser($name, $catalogue);
		die(json_encode(['err' => null, 'msg' => 'Added']));
	}
}

die(json_encode(['err' => 'Inconnue']));