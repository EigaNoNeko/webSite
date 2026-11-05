<?php
if(!isset($_SESSION['uniqid'])) die(json_encode(['err' => 'not-login']));
$type = $_POST['type'];

$verifyLike = __OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-like" AND object_name = ? AND object_content = ?',
	[$_POST['channel'], $_SESSION['uniqid']]
)->fetchAll();

$verifyUnLike = __OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-unlike" AND object_name = ? AND object_content = ?',
	[$_POST['channel'], $_SESSION['uniqid']]
)->fetchAll();

if($type == 'unlike') {
	if(!isset($verifyUnLike[0])) {
		__OmsySql->execute(
			'INSERT INTO omsy_object (object_type, object_name, object_content) VALUES(?, ?, ?)',
			['channel-unlike', $_POST['channel'], $_SESSION['uniqid']]
		);

		if(isset($verifyLike[0])) {
			__OmsySql->execute(
				'DELETE FROM omsy_object WHERE object_type = ? AND object_name = ? AND object_content = ?',
				['channel-like', $_POST['channel'], $_SESSION['uniqid']]
			);

			die(json_encode(['unlike' => 'add', 'like' => 'remove']));
		}

		die(json_encode(['unlike' => 'add', 'like' => null]));
	} else {
		__OmsySql->execute(
			'DELETE FROM omsy_object WHERE object_type = ? AND object_name = ? AND object_content = ?',
			['channel-unlike', $_POST['channel'], $_SESSION['uniqid']]
		);

		die(json_encode(['unlike' => 'remove', 'like' => null]));
	}
	die(json_encode(['unlike' => 'add', 'like' => 'remove']));
}

if($type == 'like') {
	if(!isset($verifyLike[0])) {
		__OmsySql->execute(
			'INSERT INTO omsy_object (object_type, object_name, object_content) VALUES(?, ?, ?)',
			['channel-like', $_POST['channel'], $_SESSION['uniqid']]
		);

		if(isset($verifyUnLike[0])) {
			__OmsySql->execute(
				'DELETE FROM omsy_object WHERE object_type = ? AND object_name = ? AND object_content = ?',
				['channel-unlike', $_POST['channel'], $_SESSION['uniqid']]
			);

			die(json_encode(['unlike' => 'remove', 'like' => 'add']));
		}

		die(json_encode(['unlike' => null, 'like' => 'add']));
	} else {
		__OmsySql->execute(
			'DELETE FROM omsy_object WHERE object_type = ? AND object_name = ? AND object_content = ?',
			['channel-like', $_POST['channel'], $_SESSION['uniqid']]
		);

		die(json_encode(['unlike' => null, 'like' => 'remove']));
	}
	die(json_encode(['unlike' => 'remove', 'like' => 'add']));
}