<?php
$allPlaylist = __OmsySql->execute('SELECT * FROM plug_playlist')->fetchAll();

include "interface/playlists.php";