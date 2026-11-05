<?php
if(isset($_GET['edit'])) {
    $data = __OmsySql->execute('SELECT * FROM enncore_players WHERE uniqid = ?', [$_GET['edit']])->fetchAll()[0] ?? [];
}

$b = json_decode(__OmsyMain->GFunction->htmlDecode($data['src']), true);
?>
<?= omsp_header() ?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Ajouter une vidéos</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul>
            <li>
                <a href="/">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M800-663.08v438.46q0 27.62-18.5 46.12Q763-160 735.38-160H224.62q-27.62 0-46.12-18.5Q160-197 160-224.62v-510.76q0-27.62 18.5-46.12Q197-800 224.62-800h438.46L800-663.08ZM760-646 646-760H224.62q-10.77 0-17.7 6.92-6.92 6.93-6.92 17.7v510.76q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h510.76q10.77 0 17.7-6.92 6.92-6.93 6.92-17.7V-646ZM536.54-321.92Q560-345.38 560-378.46T536.54-435q-23.46-23.46-56.54-23.46T423.46-435Q400-411.54 400-378.46t23.46 56.54q23.46 23.46 56.54 23.46t56.54-23.46ZM270.77-569.23h296.92v-120H270.77v120ZM200-646v446-560 114Z"/></svg>
                    </span>
                    <span class="name">Enregistrer le bouillon</span>
                </a>
            </li>
        </ul>
    </div>
</section>
<form method="post" enctype="multipart/form-data">
<input name="send_form_lang" value="true" hidden>
<section class="omsyblog-contenaire post">
    <section class="omsyblog-grid">
        <section class="omsyblog-grid-contenaire">
            <?php foreach ($b as $value): ?>
            <div style="margin-top: 16px; display: flex;">
                <div class="kit-input" style="width: 100%; margin-right: 8px;">
                    <label>Source</label>
                    <input type="text" name="source[]" value="<?= $value['src'] ?? '' ?>">
                </div>
                <div class="kit-input" style="width: 100px; margin: 0;">
                    <label>Lang</label>
                    <input type="text" name="lang[]" value="<?= $value['lang'] ?? '' ?>">
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        <section class="omsyblog-grid-contenaire">
            <div class="kit-input">
                <label>Statut</label>
                <select>
                    <option value="Draft">Brouillon</option>
                    <option value="Published">Publier</option>
                    <option value="Archived">Archiver</option>
                </select>
            </div>
            <div class="kit-input">
                <label>Saison</label>
                <input type="number" name="saison" value="<?= $data['saison'] ?? 1 ?>">
            </div>
            <div class="kit-input">
                <label>Episode</label>
                <input type="number" name="episode" value="<?= $data['episode'] ?? 1 ?>">
            </div>
        </section>
    </section>
</section>
</form>
<?= omsp_footer() ?>