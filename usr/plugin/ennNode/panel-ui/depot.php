<?php
if(isset($_GET['rm'])) {
    __OmsySql->execute('DELETE FROM enncore_tmp_catalogue WHERE ID = ?', [$_GET['rm']]);
}
$allDepot = __OmsySql->execute('SELECT * FROM enncore_tmp_catalogue')->fetchAll();
$bff = [];
foreach ($allDepot as $value) {
    if(isset($bff[$value['name']]['tot'])) $bff[$value['name']]['tot']++;
    
    $bff[$value['name']] = $value;
    if(!isset($bff[$value['name']]['tot'])) $bff[$value['name']]['tot'] = 1;
}
?>
<?= omsp_header() ?>
<section class="appUiKit-table app-kit-main-padding">
    <table>
        <tr class="">
            <td></td>
            <td><b>Repeat</b></td>
             <td><b>Name</b></td>
            <td><b>Date</b></td>
        </tr>
        <?php foreach ($bff as $key => $value): ?>
        <tr>
            <td></td>
            <td>
                <?= $value['tot'] ?>
            </td>
            <td>
                <?= __OmsyString->htmlDecode($value['name']); ?>
            </td>
            <td>
                <?= __OmsyMain->GFunction->convertDate($value['date_']) ?>
            </td>
            <td>
               <a href="?plugin=ennNode&page=depot&rm=<?= $value['ID'] ?>">RM</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
<?= omsp_footer() ?>