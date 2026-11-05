<?= omsp_header() ?>
<section class="appUiKit-table app-kit-main-padding">
    <table>
        <tr class="">
            <td><b>Name</b></td>
            <td><b>Description</b></td>
            <td></td>
        </tr>
        <?php foreach (__OmsySql->execute('SELECT * FROM enncore_guild_post')->fetchAll() as $key => $value): ?>
        <tr>
            <td></td>
            <td>
                <?= $value['user_id'] ?>
            </td>
            <td>
                <?= $value['content'] ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
<?= omsp_footer() ?>