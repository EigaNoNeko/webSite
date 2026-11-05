<?php

if(isset($_POST['submit']) && !empty($_POST['name'])) {
	__OmsySql->execute(
		"INSERT INTO enncore_tmp_catalogue (name, agent, date_) VALUES(?, ?, now())",
		[$_POST['name'], $_SERVER['HTTP_USER_AGENT']]
	);

	header('Location: /depot?checked=true');
}

include 'interface/depot.php';