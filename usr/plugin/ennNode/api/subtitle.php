<?php
header('Content-Type: text/plain');
//echo file_get_contents($_GET['u']);
// Get file paths
$srtFile = $_GET['u'];

$getContent = __OmsySql->execute('SELECT * FROM pvs_subtitle WHERE uniqid = ?', [$srtFile])->fetchAll();
if(!isset($getContent[0])) die('404');

$getContent = $getContent[0];
$file = $getContent['file'];
$regex = '/\b(?:https?|ftp):\/\/(?:www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(?:\/[^\s]*)?\b/';
if (preg_match($regex, $getContent['file'])) {
    $file = file_get_contents($getContent['file']);
    __OmsySql->execute('UPDATE pvs_subtitle SET file = ? WHERE uniqid = ?', [$file, $getContent['uniqid']]);
}
/*
// Read the srt file content into an array of lines
$fileHandle = fopen($srtFile, 'r');

if ($fileHandle) {
    // Assume that every line has maximum 8192 length
    // If you don't care about line length then you can omit the 8192 param
    $lines = array();
    while (($line = fgets($fileHandle, 8192)) !== false) $lines[] = $line;

    if (!feof($fileHandle)) exit ("Error: unexpected fgets() fail\n");
    else ($fileHandle);
}

// Convert all timestamp lines
// The first timestamp line is 1
$length = count($lines);

for ($index = 1; $index < $length; $index++) {
    // A line is a timestamp line if the second line above it is an empty line
    if ($index === 1 || trim($lines[$index - 2]) === '') {
        $lines[$index] = str_replace(',', '.', $lines[$index]);
    }
}
// Insert VTT header and concatenate all lines in the new vtt file
$header = "WEBVTT\n\n";
if($lines[0] == "WEBVTT\n") unset($lines[0]);
*/
echo CMS->Function->htmlDecode($file);