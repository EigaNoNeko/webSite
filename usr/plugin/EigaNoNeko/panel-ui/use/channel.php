<?= omsp_header() ?>
<?php
?>
<section class="kit-head">
	<div class="kit-grid-contenaire">
		<div class="back-button">
			<a href="/panel/getter?plugin=EigaNoNeko&page=channels">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m294.92-450 227.85 227.85L480-180 180-480l300-300 42.77 42.15L294.92-510H780v60H294.92Z"/></svg>
			</a>
		</div>
		<div class="information">
			<h2>Créer un catalogue</h2>
		</div>
	</div>
	<div class="kit-grid-contenaire">
		<ul>
			<?php if(isset($catalogueInfo['slug'])): ?>
			<li>
				<a href="/catalogue/<?= $catalogueInfo['slug'] ?>" target="_blank">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M583.65-396.53q42.5-42.68 42.5-103.65 0-60.97-42.68-103.47t-103.65-42.5q-60.97 0-103.47 42.68t-42.5 103.65q0 60.97 42.68 103.47t103.65 42.5q60.97 0 103.47-42.68ZM403.5-423.5Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM243.23-310.73Q136.31-381.46 83.08-500q53.23-118.54 160.04-189.27T479.89-760q129.96 0 236.88 70.73Q823.69-618.54 876.92-500q-53.23 118.54-160.04 189.27T480.11-240q-129.96 0-236.88-70.73ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z"/></svg>
					</span>
					<span class="name">Aperçu</span>
				</a>
			</li>
			<?php endif; ?>
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
			<input name="arr_lang[]" value="french" hidden>
			<div class="kit-input">
				<label>Nom</label>
				<input type="text" name="arr_name[]" value="<?= __OmsyString->htmlDecode($catalogueInfo['title'] ?? '') ?>">
			</div>
			<div class="kit-input">
				<label>Description</label>
				<textarea name="arr_content[]" style="height: 240px;"><?= __OmsyString->htmlDecode($catalogueInfo['description'] ?? '') ?></textarea>
			</div>
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
				<label>Tags</label>
				<input type="text" name="default_categorie" placeholder="Ajouter des tag..." value="<?= __OmsyString->htmlDecode(implode(',', $catalogueInfo['genres'] ?? [])) ?>">
				<span class="sub-input">Séparez les tags par des virgules</span>
			</div>
			<?php $channelList = __OmsySql->execute('SELECT * FROM enncore_catalogues'); ?>
			<div class="kit-input">
				<label>Series parent</label>
				<select name="parent">
					<option value="null">Auccun</option>
					<?php foreach($channelList->fetchAll() as $value): ?>
					<option value="<?= $value['uniqid'] ?>"><?= $value['slug_'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="kit-input">
				<label>Date de publication</label>
				<div class="kit-input-space">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M224.62-120q-27.62 0-46.12-18.5Q160-157 160-184.62v-510.76q0-27.62 18.5-46.12Q197-760 224.62-760h70.76v-89.23h43.08V-760h286.16v-89.23h40V-760h70.76q27.62 0 46.12 18.5Q800-723 800-695.38v510.76q0 27.62-18.5 46.12Q763-120 735.38-120H224.62Zm0-40h510.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93v-350.76H200v350.76q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69ZM200-575.39h560v-119.99q0-9.24-7.69-16.93-7.69-7.69-16.93-7.69H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93v119.99Zm0 0V-720-575.39Zm280 181.54q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.57 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.19 9.19 21.57 0 12.39-9.19 21.58-9.2 9.19-21.58 9.19Zm-181.58-9.19q-9.19-9.19-9.19-21.58 0-12.38 9.19-21.57 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.19 9.19 21.57 0 12.39-9.19 21.58-9.2 9.19-21.58 9.19-12.38 0-21.58-9.19ZM640-393.85q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.57 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.19 9.19 21.57 0 12.39-9.19 21.58-9.2 9.19-21.58 9.19ZM480-240q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.58 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.2 9.19 21.58 0 12.39-9.19 21.58Q492.38-240 480-240Zm-181.58-9.19q-9.19-9.19-9.19-21.58 0-12.38 9.19-21.58 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.2 9.19 21.58 0 12.39-9.19 21.58Q332.38-240 320-240q-12.38 0-21.58-9.19ZM640-240q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.58 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.2 9.19 21.58 0 12.39-9.19 21.58Q652.38-240 640-240Z"/></svg>
					<input type="datetime-local" name="datetime" placeholder="Ajouter des tag..." value="<?= __OmsyMain->GFunction->htmlDecode($catalogueInfo['added_date'] ?? '') ?>">
				</div>
			</div>
		</section>
	</section>
	<div class="omsyblog-grid-contenaire upload-image-list appUiKit-double-input img4x appUiKit-marginTop kit-flex-input kit-d4-input">
		<?php foreach($defaultImages as $key => $value): ?>
		<div class="kit-input" style="margin-top: 8px;">
			<label><?= $value['name'] ?></label>
			<div class="itemupload-image">
				<div class="left-rendering">
					<img src="<?= __EnnMain->cdnImageUrl . $key ?>/<?= $catalogueInfo['slug'] ?? '' ?>.jpg" height="auto" width="100%" style="object-fit: contain; height: 150px;">
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>
</form>
<!-- <section class="appkit-form-contenaire app-kit-main-padding">
	<form method="post" enctype="multipart/form-data">
		<input name="send_form_lang" value="true" hidden>
		<section class="scroll-section kit-block kit-input-block appUiKit-form-contenaire-color appUiKit-marginTop" id="image">
			<div class="head">
				<h2>Images</h2>
			</div>
			<div class="content">
				<div class="upload-image-list appUiKit-double-input img4x appUiKit-marginTop kit-flex-input kit-d4-input">
					<?php foreach($defaultImages as $key => $value): ?>
					<div class="itemupload-image kit-input">
						<div class="left-rendering">
							<img src="<?= __EnnMain->cdnImageUrl . $key ?>/<?= $catalogueInfo['slug'] ?? '' ?>.jpg" height="200px" width="100%" style="object-fit: contain;">
						</div>
						<!-- <div class="right-information">
							<div class="text">
								<span class="title"><?= $value['name'] ?></span>
								<p class="subtitle">Upload image</p>
							</div>
							<span class="button-upload">
								<span class="text">
									
								</span>
								<input type="file" name="image[]" onchange="getImage(this, this.parentNode.parentNode.parentNode.querySelector('img'))">
								<input type="text" name="type[]" value="<?= $key ?>" hidden style="display: none;">
							</span>
						</div> --
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<section class="scroll-section kit-block kit-input-block appUiKit-form-contenaire-color appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Détails</h2>
			</div>
			<div class="content">
				<div class="kit-flex-input kit-d2-input kit-input-mt multi-input upload-image-list appUiKit-double-input appUiKit-marginTop">
					<?php $channelList = __OmsySql->execute('SELECT * FROM enncore_catalogue'); ?>
					<div class="appKitUi-input kit-input appUiKit-marginTop">
						<label>Series parent</label>
						<select name="parent">
							<option value="null">Auccun</option>
							<?php foreach($channelList->fetchAll() as $value): ?>
							<option value="<?= $value['uniqid'] ?>"><?= $value['title'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="appKitUi-input kit-input appUiKit-marginTop">
						<label>Tags</label>
						<input type="text" name="default_categorie" value="<?= __OmsyString->htmlDecode(implode(',', $catalogueInfo['genres'] ?? [])) ?>">
					</div>
					<!-- <div class="appKitUi-input kit-input appUiKit-marginTop">
						<label>Plateformes</label>
						<input type="text" name="default_categorie" value="<?= __OmsyString->htmlDecode($catalogueInfo['categorie'] ?? '') ?>">
					</div> --
				</div>
				<!-- <div class="appKitUi-input kit-input appUiKit-marginTop">
					<label>Nom / Slug Name</label>
					<input type="text" name="default_name" value="<?= $catalogueInfo['title'] ?? '' ?>" disabled>
				</div> --
			</div>
		</section>
			<section class="scroll-section kit-block kit-input-block appUiKit-form-contenaire-color appUiKit-marginTop" id="lang">
				<div class="head">
					<h2>Langue des information</h2>
				</div>
				<div class="content">
					<div class="list">
						<?php

						?>
						<?php foreach ($defaultLang as $key => $value): ?>
						<?php
							$desc = __OmsySql->execute('SELECT * FROM enncore_catalogue_obj WHERE channel_id = ? AND name = ? AND type = "channel-description" AND content != "" ORDER BY ID DESC', [$catalogueInfo['uniqid'] ?? 'aa', $key])->fetchAll();
							if(isset($desc[0])) $desc = $desc[0];
							$title = __OmsySql->execute('SELECT * FROM enncore_catalogue_obj WHERE channel_id = ? AND name = ? AND type = "channel-title" AND content != "" ORDER BY ID DESC', [$catalogueInfo['uniqid'] ?? 'aa', $key])->fetchAll();
							if(isset($title[0])) $title = $title[0];

							if(!$desc) continue;
						?>
						<div class="element appUiKit-marginTop">
							<div class="kit-flex-input kit-d2-input multi-input appUiKit-double-input appUiKit-marginTop">
								<div class="appKitUi-input kit-input">
									<label>Langue</label>
									<select name="arr_lang[]">
										<?php foreach ($defaultLang as $key => $value): ?>
										<option value="<?= $key ?>"><?= $value ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="appKitUi-input kit-input">
									<label>Nom</label>
									<input type="text" name="arr_name[]" value="<?= __OmsyString->htmlDecode($title['content']) ?>">
								</div>
							</div>
							<div class="kit-input">
								<label>Description</label>
								<textarea name="arr_content[]"><?= __OmsyString->htmlDecode($desc['content']) ?></textarea>
							</div>
						</div>
						<?php endforeach; ?>
						<div class="element appUiKit-marginTop">
							<div class="kit-flex-input kit-d2-input multi-input appUiKit-double-input appUiKit-marginTop">
								<div class="appKitUi-input kit-input">
									<label>Langue</label>
									<select name="arr_lang[]">
										<?php foreach ($defaultLang as $key => $value): ?>
										<option value="<?= $key ?>"><?= $value ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="appKitUi-input kit-input">
									<label>Nom</label>
									<input type="text" name="arr_name[]">
								</div>
							</div>
							<div class="kit-input kit-input-mt appKitUi-input appUiKit-marginTop">
								<label>Description</label>
								<textarea name="arr_content[]"></textarea>
							</div>
						</div>
					</div>
				</div>
			</section>
	</form>
</section> -->
<?= omsp_footer() ?>