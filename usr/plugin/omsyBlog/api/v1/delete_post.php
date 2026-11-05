<?php
if(!__OmsyPermission->can(__OmsyPermission->DELETE, "00000007")) return __OmsyMain->api('DELETE', ['msg' => 'Vous n\'avait pas la permission pour cette action.']);

$getBlog = __OmsySql->execute('SELECT * FROM omsyblog_single WHERE ID = ?', [$_POST['id']])->fetchAll();
if(!isset($getBlog[0])) return __OmsyMain->api('DELETE', ['msg' => 'Article inconnue']);

__OmsySql->execute('DELETE FROM omsyblog_single WHERE ID = ?', [$_POST['id']]);
return __OmsyMain->api('DELETE', null, []);