<?php
for ($i=0; $i < 5; $i++) { 
	sleep(2);
}

header('Content-Type: text/json');
echo json_encode(["message" => 'Teste executer avec succée']);