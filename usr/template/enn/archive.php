<?php
$lang = __OmsySecurity->getArgs()[1];
$group = __OmsySecurity->getArgs()[2];

$listBlogSingle = __omsySql->execute('SELECT * FROM blog_single WHERE blog_group = ? ORDER BY ID DESC', [$group])->fetchAll();
?>
<?php include "include/head.php"; ?>
<section class="marge-for-header marge-mobile-header archivepage">
	<div class="head">
		<h1>Eiga No Neko</h1>
		<h2>Newsroom</h2>
	</div>
	<div class="list-blog-single">
		<ul>
			<?php foreach ($listBlogSingle as $key => $value): ?>
			<li>
				<a href="/fr/<?= $value['blog_group'] ?>/<?= $value['blog_slug'] ?>" class="min">
					<img src="<?= $value['blog_min'] ?>" width="800px">
				</a>
				<a href="/fr/<?= $value['blog_group'] ?>/<?= $value['blog_slug'] ?>">
					<h3><?= __OmsyString->substr(__OmsyString->htmlDecode($value['blog_title']), 55) ?></h3>
				</a>
				<span class="date"><?= __OmsyMain->Plugins->MinBlog->convertirDateSQL($value['date_time']) ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php include "include/footer.php"; ?>