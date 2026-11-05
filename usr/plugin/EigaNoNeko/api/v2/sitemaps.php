<?php
header("Content-type: text/xml");

$db = __OmsySql->execute('SELECT * FROM enncore_catalogues')->fetchAll();
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($db as $key => $value): ?>
<url>
	<loc>https://www.eiganoneko.com/catalogue/<?= $value['slug_'] ?></loc>
	<lastmod><?= date_format(date_create($value['last_'] ?? ''),"Y-m-d") ?></lastmod>
	<changefreq>daily</changefreq>
	<priority>1</priority>
</url>
<?php endforeach; ?>
</urlset>