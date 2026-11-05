<?php
$buff = [];

$max = 50;
$count = 0;

$file = new SplFileObject(__Root . 'share/_omsy/logs/lastet.log');
$file->seek(PHP_INT_MAX); // va à la fin
$lastLine = $file->key();

for ($i = max(0, $lastLine - $max); $i <= $lastLine; $i++) {
    $file->seek($i);
    $line = trim($file->current());

    if ($line === '') continue;

    if (!preg_match(
        '/^(\d{2}:\d{2}:\d{2}) \| \[(.*?)\] \| ([A-Z]+): (.*)$/',
        $line,
        $matches
    )) continue;

    $buff[] = [
        "module" => $matches[2],
        "type" => $matches[3],
        "message" => $matches[4],
        "heure" => $matches[1],
        "omsyPanel.table.options" => []
    ];
}
omsp_header();
$buff = array_reverse($buff);
?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Journal</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul></ul>
    </div>
</section>
<section class="kit-contenaire">
<?php
SQLX_table([
    "head" => [
        "Module",
        "Type",
        "Détails",
        "Date",
    ],
    "body" => $buff
]);
?>
</section>
<?php
omsp_footer();
?>