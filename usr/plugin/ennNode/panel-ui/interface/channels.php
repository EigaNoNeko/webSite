<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Liste des chaine</h1>
    </div>
    <div class="content">
        <ul>
            <li><a class="active" href="/panel/getter?plugin=ennNode&page=channels" data-update="embed">Liste des chaine</a></li>
            <li><a href="/panel/getter?plugin=ennNode&page=channel" data-update="embed">Ajouter une chaine</a></li>
            <li><a href="/panel/getter?plugin=ennNode&page=watch_api" data-update="embed">Ajouter une liste de lien</a></li>
        </ul>
    </div>
</section>

<section class="app-kit-main-padding appUiKit-block pagination ennKit-pagination">
    <div class="content">
        <span><?= $allCatalogue['page'] * $allCatalogue['max'] - $allCatalogue['max'] +1 ?> à <?= $allCatalogue['page'] * $allCatalogue['max'] ?> sur <?= $allCatalogue['totaleItems'] ?></span>
        <ul>
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M269.23-295.38v-369.24h40v369.24h-40Zm421.54 0L413.85-480l276.92-184.62v369.24Zm-40-184.62Zm0 110v-220L485.54-480l165.23 110Z"/></svg>
                </a>
            </li>
            <!-- <?php for ($i=1; $i < $allCatalogue['totalePage']+1; $i++): ?>
            <li><a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>-->
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $allCatalogue['page']-1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M560-267.69 347.69-480 560-692.31 588.31-664l-184 184 184 184L560-267.69Z"/></svg>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $allCatalogue['page']+1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m531.69-480-184-184L376-692.31 588.31-480 376-267.69 347.69-296l184-184Z"/></svg>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $allCatalogue['totalePage'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M650.77-295.38v-369.24h40v369.24h-40Zm-381.54 0v-369.24L546.15-480 269.23-295.38Zm40-184.62Zm0 110 165.23-110-165.23-110v220Z"/></svg>
                </a>
            </li>
        </ul>
    </div>
</section>
<section class="appUiKit-table app-kit-main-padding">
    <table>
        <tr class="">
            <td></td>
            <td width="35%"><b>Name</b></td>
            <td width="25%"><b>Description</b></td>
            <td><b>Saison</b></td>
            <td><b>Episode</b></td>
            <td><b>Like</b></td>
            <td><b>Unlike</b></td>
            <td><b>Follow</b></td>
            <td></td>
        </tr>
        <?php
        foreach ($allCatalogue['items'] as $key => $value):
        ?>
        <tr>
            <td>
                <input type="checkbox" name="">
            </td>
            <td width="35%">
                <b>
                    <a data-update="embed" href="/panel/getter?plugin=ennNode&page=watchs&channel=<?= $value['uniqid'] ?>"><?= __OmsyString->htmlDecode($value['information']['title'] ?? 'Data is ot found') ?></a>
                </b>
            </td>
            <td width="25%"><?= __OmsyString->substr(__OmsyString->htmlDecode($value['information']['description'] ?? 'Data is ot found'), 55) ?></td>
            <td><?= $value['saison'] ?></td>
            <td><?= $value['episode'] ?></td>
            <td><?= $value['like'] ?></td>
            <td><?= $value['unlike'] ?></td>
            <td><?= $value['follow'] ?></td>
            <td>
                <ul>
                    <li>
                        <a data-update="embed" class="button-ui-svg" href="/panel/getter?plugin=ennNode&page=channel&channel=<?= $value['uniqid'] ?>" class="title-stream">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M255.38-175.38q-23.65 0-46.79-8.16-23.13-8.15-40.9-22.61 19.85-13.08 33.77-35.12 13.92-22.04 13.92-54.11 0-33.85 23.08-56.93 23.08-23.07 56.92-23.07 33.85 0 56.93 23.07 23.07 23.08 23.07 56.93 0 49.5-35.25 84.75t-84.75 35.25Zm0-40q33 0 56.5-23.5t23.5-56.5q0-17-11.5-28.5t-28.5-11.5q-17 0-28.5 11.5t-11.5 28.5q0 23-5.5 42t-14.5 36q5 2 10 2h10ZM450-380l-68.46-68.46 327.23-327.23q11-11 27.5-11.5t28.5 11.5l12.46 12.46q12 12 12 28t-12 28L450-380Zm-154.62 84.62Z"/></svg>
                        </a>
                    </li>
                </ul>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
<section class="app-kit-main-padding appUiKit-block pagination ennKit-pagination">
    <div class="content">
        <span><?= $allCatalogue['page'] * $allCatalogue['max'] - $allCatalogue['max'] +1 ?> à <?= $allCatalogue['page'] * $allCatalogue['max'] ?> sur <?= $allCatalogue['totaleItems'] ?></span>
        <ul>
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M269.23-295.38v-369.24h40v369.24h-40Zm421.54 0L413.85-480l276.92-184.62v369.24Zm-40-184.62Zm0 110v-220L485.54-480l165.23 110Z"/></svg>
                </a>
            </li>
            <!-- <?php for ($i=1; $i < $allCatalogue['totalePage']+1; $i++): ?>
            <li><a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>-->
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $allCatalogue['page']-1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M560-267.69 347.69-480 560-692.31 588.31-664l-184 184 184 184L560-267.69Z"/></svg>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $allCatalogue['page']+1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m531.69-480-184-184L376-692.31 588.31-480 376-267.69 347.69-296l184-184Z"/></svg>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=ennNode&page=channels&pp=<?= $allCatalogue['totalePage'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M650.77-295.38v-369.24h40v369.24h-40Zm-381.54 0v-369.24L546.15-480 269.23-295.38Zm40-184.62Zm0 110 165.23-110-165.23-110v220Z"/></svg>
                </a>
            </li>
        </ul>
    </div>
</section>
<?= omsp_footer() ?>