<?php
$items_max = intval($_GET['q_limit'] ?? 1);
?>
<section class="kit-main-padding kit-block kit-pagination">
    <div class="left">
        <ul>
            <!--<li>
                <form method="get" action="/panel/getter" data-sub="omsy.button.research">
                    <input type="text" name="plugin" value="<?= __ACTIF_PLUGIN__ ?>" hidden>
                    <input type="text" name="page" value="<?= __GETTER_PAGE__ ?>" hidden>
                    <input type="text" name="q_search_name" value="<?= $_GET['q_search_name'] ?? '' ?>">
                    <input type="text" name="q_search" value="<?= $_GET['q_search'] ?? '' ?>">
                </form>
            </li>-->
            <li>
                <span><?= $data['page'] * $data['max'] - $data['max'] +1 ?> à <?= $data['page'] * $data['max'] ?> sur <?= $data['totaleItems'] ?></span>
            </li>
            <li>
                <select onchange="document.location = '/panel/getter?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= __GETTER_PAGE__ ?>&pp=1&q_limit='+this.value">
                    <?php for($i = 1; $i < 6; $i++): ?>
                    <option <?php if($items_max == ($i * 8)) echo 'selected'; ?>><?= ($i * 8) ?></option>
                    <?php endfor; ?>
                </select>
            </li>
        </ul>
    </div>
    <div class="right">
        <ul>
            <li>
                <a href="/panel/getter?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= __GETTER_PAGE__ ?>&pp=1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M269.23-295.38v-369.24h40v369.24h-40Zm421.54 0L413.85-480l276.92-184.62v369.24Zm-40-184.62Zm0 110v-220L485.54-480l165.23 110Z"/></svg>
                </a>
            </li>
            <!-- <?php for ($i=1; $i < $data['totalePage']+1; $i++): ?>
            <li><a href="/panel/getter?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= __GETTER_PAGE__ ?>&pp=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>-->
            <li>
                <a href="/panel/getter?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= __GETTER_PAGE__ ?>&pp=<?= $data['page']-1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M560-267.69 347.69-480 560-692.31 588.31-664l-184 184 184 184L560-267.69Z"/></svg>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= __GETTER_PAGE__ ?>&pp=<?= $data['page']+1 ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m531.69-480-184-184L376-692.31 588.31-480 376-267.69 347.69-296l184-184Z"/></svg>
                </a>
            </li>
            <li>
                <a href="/panel/getter?plugin=<?= __ACTIF_PLUGIN__ ?>&page=<?= __GETTER_PAGE__ ?>&pp=<?= $data['totalePage'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M650.77-295.38v-369.24h40v369.24h-40Zm-381.54 0v-369.24L546.15-480 269.23-295.38Zm40-184.62Zm0 110 165.23-110-165.23-110v220Z"/></svg>
                </a>
            </li>
        </ul>
    </div>
</section>