<?php 
include "include/head.php";

?>
<section class="article-blog-page app-header-margin-top">
	<div class="head">
		<h1 class="title"><?= __OmsyMain->GFunction->htmlDecode($getBlogSingle['blog_title']) ?></h1>
		<div class="blog-thumbnail">
			<img class="thumbnail" src="<?= __OmsyMain->GFunction->htmlDecode($getBlogSingle['blog_min']) ?>">
			<div class="userinfo">
				<span class="username"></span>
			</div>
		</div>
	</div>
	<div class="blog-content">
		<div class="blog-info">
			<span><b><?= $getBlogSingle['blog_group'] ?></b> • <?= __OmsyMain->GFunction->convertDate($getBlogSingle['date_time']) ?> par <?= $user['username'] ?></span>
			<ul class="tags-list">
				<?php foreach (explode(',', $getBlogSingle['blog_tags'] ?? '') as $key => $value): ?>
				<li class="item-tag"><?= __OmsyMain->GFunction->htmlDecode($value) ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="blog-contenaire">
			<article>
			<?php
				$text = __OmsyMain->GFunction->htmlDecode($getBlogSingle['blog_content']);
				echo $text;
				if (preg_match('/https?:\/\/youtu\.be\/([a-zA-Z0-9_-]+)(\?[^ ]*)?/', $text, $matches)) {
					$matches[2] = $matches[2] ?? '';
					//echo "<iframe src=\"{$matches[0]}\"></iframe>";
					echo "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/{$matches[1]}{$matches[2]}\" title=\"YouTube video player\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>";
				}
			?>
			</article>
		</div>
	</div>
</section>
<script>
function redimensionnerIframe() {
	const iframes = document.querySelectorAll('.blog-content iframe');
	iframes.forEach(iframe => {
		const parentWidth = iframe.parentNode.clientWidth;
		const ratio = 434 / 800; // Hauteur / Largeur
		iframe.style.height = (parentWidth * ratio) + 'px';
	});
}

// Ajuste au chargement et au redimensionnement de la fenêtre
window.addEventListener('load', redimensionnerIframe);
window.addEventListener('resize', redimensionnerIframe);
</script>
<?php include "include/footer.php"; ?>