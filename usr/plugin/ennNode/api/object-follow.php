<?php
if(!isset($_SESSION['uniqid'])) die(json_encode(['err' => 'not-login']));

$verifyFollow = __OmsySql->execute(
	'SELECT * FROM omsy_object WHERE object_type = "channel-follow" AND object_name = ? AND object_content = ?',
	[$_POST['channel'], $_SESSION['uniqid']]
)->fetchAll();

if(!isset($verifyFollow[0])) {
	__OmsySql->execute(
		'INSERT INTO omsy_object (object_type, object_name, object_content) VALUES(?, ?, ?)',
		['channel-follow', $_POST['channel'], $_SESSION['uniqid']]
	);

	die(json_encode(['follow' => 'add']));
} else {
	__OmsySql->execute(
		'DELETE FROM omsy_object WHERE object_type = ? AND object_name = ? AND object_content = ?',
		['channel-follow', $_POST['channel'], $_SESSION['uniqid']]
	);

	die(json_encode(['follow' => 'remove']));
}