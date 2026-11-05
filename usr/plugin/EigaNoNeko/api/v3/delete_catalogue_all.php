<?php
if(!__OmsyPermission->can(__OmsyPermission->DELETE, "00000007")) return __OmsyMain->api('DELETE', ['msg' => 'Vous n\'avait pas la permission pour cette action.']);

$getBlog = __OmsySql->execute('SELECT * FROM enncore_catalogues WHERE uniqid = ?', [$_POST['id']])->fetchAll();
if(!isset($getBlog[0])) return __OmsyMain->api('DELETE', ['msg' => 'Catalogue inconnue']);

__OmsySql->execute('DELETE FROM enncore_catalogues WHERE uniqid = ?', [$_POST['id']]);
__OmsySql->execute('DELETE FROM enncore_catalogue_obj WHERE channel_id = ?', [$_POST['id']]);
__OmsySql->execute('DELETE FROM enncore_players WHERE catalogue_id = ?', [$_POST['id']]);

return __OmsyMain->api('DELETE', null, []);