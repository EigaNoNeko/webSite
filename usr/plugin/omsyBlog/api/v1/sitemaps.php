<?php
header("Content-type: text/xml");

$dbBlog = __OmsySql->execute('SELECT * FROM omsyblog_single')->fetchAll();
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
<?php foreach ($dbBlog as $key => $value): ?>
<url>
	<loc>https://www.eiganoneko.com/blog/<?= $value['blog_group'] ?>/<?= $value['blog_slug'] ?></loc>
	<changefreq>daily</changefreq>
	<priority>1</priority>
	<news:news>
		<news:publication>
			<news:name><?= __OmsyMain->GFunction->htmlDecode($value['blog_title']) ?></news:name>
			<news:language>fr</news:language>
		</news:publication>
		<news:publication_date><?= date_format(date_create($value['date_time'] ?? ''),"Y-m-d") ?></news:publication_date>
		<news:title><?= __OmsyMain->GFunction->htmlDecode($value['blog_title']) ?></news:title>
	</news:news>
</url>
<?php endforeach; ?>
</urlset>