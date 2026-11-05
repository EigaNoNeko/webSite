<?php
header('Content-Type: text/plain');
// L'URL de la page cible
$saison = $_POST['saison'];
$lang = $_POST['lang'];
$watch = $_POST['watch'];

$url = "https://anime-sama.tv/catalogue/$watch/$saison/$lang/episodes.js";

$javascriptAnimeSama = file_get_contents($url);

die($javascriptAnimeSama);