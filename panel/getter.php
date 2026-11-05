<?php
include __Root . "share/lib/floagg_panel/main.php";
$OmsyPanel = new FloaggPanel();
$OmsyPanel->setMap(__OmsyPanel->getMenu2());
$OmsyPanel->setAvatar(__OmsySession['user_avatar'] ?? $OmsyPanel->getDefaultAvatar());

$GLOBALS['OmsyPanel'] = $OmsyPanel;
$r = __OmsyPanel->loadAdmin();

function omsp_header() { $GLOBALS['OmsyPanel']->omsp_header(); }
function omsp_footer() { $GLOBALS['OmsyPanel']->omsp_footer(); }

function HMain($data) { __OmsyPanel->HMain($data); }
function SQLX_pagetab($data) { __OmsyPanel->SQLX_pagetab($data); }
function SQLX_table($table = []) { __OmsyPanel->SQLX_table($table); }

function defaultUi() {
	$OmsyPanel->omsp_header();
	echo "<style>main { display: flex; align-items: center; justify-content: center; } main ul li { margin-bottom: 4px; } </style><div style=\"text-align: center;\"><h1 style=\"opacity: 0.1; margin: 0 auto; margin-bottom: 32px; font-size: 82px; user-select: none; pointer-events: none;\">< / ></h1></div>";
	$OmsyPanel->omsp_footer();
}

if(!isset($r['path'])) defaultUi();
else include __Root . $r['path'];

// die();
// define('__domaine__', 'https://127.0.0.1/');
// if(empty($_GET['plugin'])) exit("500");
// if(empty($_GET['page'])) exit("500");


// $plugin = $_GET['plugin'] ?? '';
// $page = $_GET['page'] ?? '';
// $page = explode('/', $page);
// foreach ($page as $key => $value) {
// 	if(empty($value)) unset($page[$key]);
// }
// $page = implode('/', $page);

// define('__GETTER_PAGE__', $page);
// define('__ACTIF_PLUGIN__', $plugin);

// $path = "usr/plugin/$plugin/panel-ui/$page.php";
// $pathConf = "usr/plugin/$plugin/panel-ui/prm.json";
// if(!file_exists(__Root . $pathConf)) exit("404");

// $conf = json_decode(file_get_contents(__Root . $pathConf));
// define('__GETTER_PRM__', $conf->$page->prm);

// if(!__OmsyPermission->can(__OmsyPermission->OPEN, __GETTER_PRM__)) exit("403");

// $EmbedPanel = true;


// if(file_exists(__Root . $path)) include __Root . $path;
// else include __Root . "panel/index.php";