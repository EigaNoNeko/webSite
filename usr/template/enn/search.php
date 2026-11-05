<?php
$search = $_GET['query_search'] ?? null;
$order = '';

$data = [];
if($search) {
	$querySearch = '%'.htmlentities($search).'%';
	$querySearchNotSpace = preg_replace('/\s+/', '-', $querySearch);

	$allCatalogue = 'Recherche pour "'.htmlentities($search).'"';

	$data = __EnnCatalogues->research_($querySearch, 35);
}
?>
<?php include "interface/include/head.php"; ?>
<section class="enn-main-home window-width">
	<section class="enn-main-home list-subject-watchs" id="dynalist-section">
		<section class="item-subject-watch">
			<div class="head">
				<div class="name">
					<h2><?= $allCatalogue ?></h2>
				</div>
				<div class="option">
					<a href="#">Voir plus</a>
				</div>
			</div>
			<div class="content">
				<ul class="wrap">
					<?php foreach($data['data'] as $catalogue): ?>
					<?php
					?>
					<li>
						<a href="/catalogue/<?= $catalogue['slug'] ?>" class="not-style serie-card" data-update="popup">
							<div class="min">
								<img src="<?= __EnnMain->cdnImageUrl ?>poster/<?= $catalogue['slug'] ?>.jpg" onerror="this.src = Core.notLoadImage('poster')">
							</div>
							<div class="content">
								<span class="title"><?= $catalogue['title'] ?? '' ?></span>
								<span class="sub_title"></span>
								<div>
									<?php if(isset($catalogue['langues'])): ?>
									<span style="margin-top: 8px;">Langues</span>
									<span class="sub_title" style="margin-top: 0px;"><?= $catalogue['langues'] ?></span>
									<?php endif; ?>
									<?php if(isset($catalogue['description'])): ?>
									<div class="hover">
										<span style="margin-top: 8px;">Synopsis</span>
										<span class="sub_title" style="margin-top: 0px;"><?= __OmsyString->substr(__OmsyString->htmlDecode($catalogue['description'] ?? ''), 120) ?></span>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	</section>
</section>
<?php include "interface/include/footer.php"; ?>