<?php
include __Root . "share/lib/floagg_panel/main.php";
$OmsyPanel = new FloaggPanel();
$OmsyPanel->setMap(__OmsyPanel->getMenu2());
$OmsyPanel->setAvatar(__OmsySession['user_avatar'] ?? $OmsyPanel->getDefaultAvatar());

$r = __OmsyPanel->loadAdmin();

$OmsyPanel->omsp_header();
echo "<style>main { display: flex; align-items: center; justify-content: center; } main ul li { margin-bottom: 4px; } </style><div style=\"text-align: center;\"><h1 style=\"opacity: 0.1; margin: 0 auto; margin-bottom: 32px; font-size: 82px; user-select: none; pointer-events: none;\">< / ></h1></div>";
$OmsyPanel->omsp_footer();