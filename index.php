<?php
define('omsyExecutionTime', microtime(true));
function getExcutionTime() { return microtime(true) - omsyExecutionTime; }
date_default_timezone_set('Europe/Paris');

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
define('__Root', dirname(__FILE__).'/');
define('__root__', __Root);


$configCoreFile = simplexml_load_file('./Core.xml');
if ($configCoreFile === false) die('Erreur de chargement du fichier XML.');


include __Root . "sys/class/OmsyMain.php";
new OmsyMain(
	"Core.xml",
	$configCoreFile
);