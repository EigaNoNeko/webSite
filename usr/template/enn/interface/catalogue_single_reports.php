<?php
$reportAdd = false;
if(isset($_GET['report'])):
	$verify = __OmsySql->execute(
		'SELECT * FROM omsyreport_report WHERE user = ? AND page = ? AND tag = ?',
		[$_SESSION['uniqid']??__OmsyMain->GFunction->getIp(), "/catalogue/".$catalogueInfo['slug'], $_GET['report']]
	)->fetchAll();
	if(count($verify) == 0) {
		__OmsySql->execute(
			'INSERT INTO omsyreport_report(uniqid, user, page, tag, date_) VALUES(?, ?, ?, ?, now())',
			[uniqid(), $_SESSION['uniqid']??__OmsyMain->GFunction->getIp(), "/catalogue/".$catalogueInfo['slug'], $_GET['report']]
		);
		$reportAdd = true;
	}	
endif;
?>
<?php if($reportAdd == true): ?>
<section class="enn-kit enn-kit-block-style enn-kit-mgb catalogue-news-reports">
	<div class="head">
		<p>Contenue signler avec succées</p>
	</div>
</section>
<script>
document.location = '/catalogue/<?= $catalogueInfo['slug'] ?>?report-success=true'
</script>
<?php endif; ?>
<section class="enn-kit enn-kit-block-style enn-kit-mgb catalogue-reports">
	<div class="head">
		<h2>Signaler un problèmes</h2>
	</div>
	<div class="content">
		<ul>
			<li>
				<div class="cont">
					<div class="button" onclick="openSubMenu(event, this)">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M162.31-130v-60h460v60h-460Zm232.15-206.31L182.31-548.46l70.15-72.15 214.15 212.15-72.15 72.15Zm237.08-237.08L419.39-787.54l72.15-70.15 212.15 212.15-72.15 72.15ZM824-173.85 315.85-682 358-724.15 866.15-216 824-173.85Z"/></svg>
						</span>
						<div class="text">
							<span class="name">Suprimer les episodes</span>
							<span class="sub_name">Vous souhaiter arrété la diffusion des/certain episodes de cette fiche.</span>
						</div>
					</div>
					<div class="information sub_content">
						<p>Veuillez noter que le contenu vidéo en question n’est pas hébergé sur Eiga No Neko. Notre site est une plateforme d'information dédiée aux séries, proposant des actualités, des fiches, et du contenu éditorial. Nous ne proposons aucun hébergement de contenus vidéo : les vidéos disponibles sur notre site proviennent de plateformes tierces ou de partenaires. Pour toute réclamation ou question concernant un contenu spécifique, nous vous invitons à contacter directement la plateforme qui en assure l’hébergement. Merci de votre compréhension.</p>
					</div>
				</div>
			</li>
			<li>
				<div class="cont">
					<div class="button" onclick="openSubMenu(event, this)">
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M550-369.23q14.69 0 25.65-10.96 10.96-10.96 10.96-25.66 0-14.69-10.96-25.65-10.96-10.96-25.65-10.96-14.69 0-25.65 10.96-10.97 10.96-10.97 25.65 0 14.7 10.97 25.66 10.96 10.96 25.65 10.96Zm-23.85-120.31h47.69q.77-27.08 6.97-40.19 6.19-13.12 29.34-35.5 27.69-26.54 37.69-44.85 10-18.3 10-42.53 0-42.7-30.15-70.43-30.15-27.73-77.69-27.73-37.15 0-65.35 20.5-28.19 20.5-40.96 55.04l43.23 18.15q10.16-24.23 26.04-36.34 15.89-12.12 37.04-12.12 26.31 0 43.23 15.23 16.92 15.23 16.92 40.16 0 15.15-8.57 28.42-8.58 13.27-29.73 32.65-29.16 25.54-37.43 43-8.27 17.47-8.27 56.54ZM322.31-260Q292-260 271-281q-21-21-21-51.31v-455.38Q250-818 271-839q21-21 51.31-21h455.38Q808-860 829-839q21 21 21 51.31v455.38Q850-302 829-281q-21 21-51.31 21H322.31Zm0-60h455.38q4.62 0 8.46-3.85 3.85-3.84 3.85-8.46v-455.38q0-4.62-3.85-8.46-3.84-3.85-8.46-3.85H322.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46v455.38q0 4.62 3.85 8.46 3.84 3.85 8.46 3.85Zm-140 200Q152-120 131-141q-21-21-21-51.31v-515.38h60v515.38q0 4.62 3.85 8.46 3.84 3.85 8.46 3.85h515.38v60H182.31ZM310-800v480-480Z"/></svg>
						</span>
						<div class="text">
							<span class="name">Information incompletes</span>
							<span class="sub_name">Signaler des informations manquante</span>
						</div>
					</div>
					<div class="sub_content">
						<p class="information">Selectionner une des option suivante pour votre signalement.</p>
						<ul>
							<li>
								<a href="/catalogue/<?= $catalogueInfo['slug'] ?>/reports?report=details-for-description">
									<span>Description</span>
								</a>
							</li>
							<li>
								<a href="/catalogue/<?= $catalogueInfo['slug'] ?>/reports?report=details-for-image">
									<span>Images</span>
								</a>
							</li>
							<li>
								<a href="/catalogue/<?= $catalogueInfo['slug'] ?>/reports?report=episodes-for-avis">
									<span>Episodes</span>
								</a>
							</li>
							<li>
								<a href="/catalogue/<?= $catalogueInfo['slug'] ?>/reports?report=bug">
									<span>Disfonctionnement</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</li>
		</ul>
	</div>
</section>
<script type="text/javascript">
function openSubMenu(event, his) {
	console.log(event, his)

	his.parentNode.classList.toggle('open')
}
</script>