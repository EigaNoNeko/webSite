<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1><?= $data['name'] ?></h1>
    </div>
    <div class="content">
        <ul>
            <?php foreach ($data['ul'] as $key => $value): ?>
            <li><a href="<?= $value['href'] ?>" class="<?php if(1 === 1) echo 'active'; ?>" data-update="embed"><?= $value['name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>