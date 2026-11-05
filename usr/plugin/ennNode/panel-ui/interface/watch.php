<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
	<div class="head">
		<h1>Ajouter une video</h1>
	</div>
	<div class="content">
		<ul>
			<li><a class="active" href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Home</a></li>
		</ul>
	</div>
</section>
<section class="appkit-form-contenaire app-kit-main-padding">
	<form method="post" enctype="multipart/form-data">
		<input name="send_form" value="true" hidden>
		<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Détails</h2>
			</div>
			<div class="content">
				<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
					<div class="appKitUi-input">
						<label>Nom / Slug Name</label>
						<input type="text" name="default_name" value="<?= $playerInfo['title'] ?? '' ?>">
					</div>
					
					<div class="appKitUi-input">
						<label>Série</label>
						<select name="channel">
							<?php $channelList = __OmsySql->execute('SELECT * FROM enncore_catalogue'); ?>
							<?php foreach($channelList->fetchAll() as $value): ?>
							<option
								value="<?= $value['uniqid'] ?>"
									<?php 
										if(isset($playerInfo['uniqid']) && ($playerInfo['uniqid'] === $value['uniqid']) ||
											(isset($_GET['channel']) && $value['uniqid'] == $_GET['channel'])
										) echo 'selected'; 
									?>
								>
									<?= $value['title'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<?php if(isset($catalogueInfo)): ?>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Episode parent</label>
					<select name="uniqid_parent">
						<option value="null">Auccun</option>
						<?php $channls = __OmsySql->execute('SELECT * FROM enncore_player WHERE catalogue_id = ? ORDER BY saison, episode ASC', [$catalogueInfo['uniqid']]); ?>
						<?php foreach($channls->fetchAll() as $value): ?>
						<option
							value="<?= $value['uniqid'] ?>"
							<?php
							if(($gC['uniqid'] === $value['uniqid'])) echo 'selected';
							?>>
							Saison <?= $value['saison'] ?> / Episode <?= $value['episode'] ?> / <?= $value['lang_'] ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
					<div class="appKitUi-input">
						<label>Saison</label>
						<input type="number" name="saison" value="<?= $playerInfo['saison'] ?? $value['saison'] ?? 1 ?>">
					</div>

					<div class="appKitUi-input">
						<label>Episode</label>
						<input type="number" name="episode" value="<?= $playerInfo['episode'] ?? $value['episode'] ?? 1 ?>">
					</div>

					<div class="appKitUi-input">
						<label>Langue</label>
						<input type="text" name="lang" value="<?= $playerInfo['lang_'] ?? $value['lang_'] ?? 'vf' ?>">
					</div>
				</div>

				<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
					<div class="appKitUi-input" style="width: 128px;">
						<label>Type</label>
						<select name="iframe">
							<option value="iframe">Iframe</option>
							<option value="mp4">Mp4</option>
							<option value="m3u8">M3u8</option>
						</select>
					</div>
					<div class="appKitUi-input">
						<label>Url</label>
						<input type="text" name="video" value="<?= $playerInfo['src'] ?? '' ?>">
					</div>
				</div>

				<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
					<?php if(isset($playerInfo['thumbnail'])): ?>
					<div class="appKitUi-input appUiKit-marginTop" style="width: 128px;">
						<label>Thumbnail</label>
						<img src="<?= $playerInfo['thumbnail'][0] ?? $catalogueInfo['image']['thumbnail'] ?>" width="100%">
					</div>
					<?php endif; ?>
					<div class="appKitUi-input appUiKit-marginTop">
						<label>Thumbnail</label>
						<input type="file" name="thumbnail">
					</div>
				</div>
			</div>
		</section>
	</form>
</section>
<?= omsp_footer() ?>