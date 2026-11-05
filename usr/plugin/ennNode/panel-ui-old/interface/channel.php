<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Ajouter une chaine</h1>
    </div>
    <div class="content">
        <ul>
            <li><a href="/panel/getter?plugin=ennNode&page=channels" data-update="embed">Liste des chaine</a></li>
            <li><a class="active" href="/panel/getter?plugin=ennNode&page=channel" data-update="embed">Ajouter une chaine</a></li>

    		<li><a href="<?= $_SERVER['REQUEST_URI'] . '?' . $_SERVER['REDIRECT_QUERY_STRING'] ?>#about" data-update="scroll">Détails</a></li>
    		<li><a href="<?= $_SERVER['REQUEST_URI'] . '?' . $_SERVER['REDIRECT_QUERY_STRING'] ?>#image" data-update="scroll">Images</a></li>
    		<li><a href="<?= $_SERVER['REQUEST_URI'] . '?' . $_SERVER['REDIRECT_QUERY_STRING'] ?>#lang" data-update="scroll">Langues</a></li>
        </ul>
    </div>
</section>
<section class="appkit-form-contenaire">
	<form method="post" enctype="multipart/form-data">
		<input name="send_form_lang" value="true" hidden>
		<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Détails</h2>
			</div>
			<div class="content">
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Nom / Slug Name</label>
					<input type="text" name="default_name" value="<?= CMS->GFunction->htmlDecode($catalogueInfo['title'] ?? '') ?>">
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>categorie</label>
					<input type="text" name="default_categorie" value="<?= CMS->GFunction->htmlDecode($catalogueInfo['categorie'] ?? '') ?>">
				</div>
				<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
					<?php $channelList = CMS->DataBase->execute('SELECT * FROM enncore_catalogue'); ?>
					<div class="appKitUi-input appUiKit-marginTop">
						<label>Series parent</label>
						<select name="parent">
							<option value="null">Auccun</option>
							<?php foreach($channelList->fetchAll() as $value): ?>
							<option value="<?= $value['uniqid'] ?>"><?= $value['title'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php $playlistList = CMS->DataBase->execute('SELECT * FROM plug_playlist'); ?>
					<div class="appKitUi-input appUiKit-marginTop">
						<label>Playlist</label>
						<select name="playlist">
						<option value="null">Auccun</option>
						<?php foreach($playlistList->fetchAll() as $value): ?>
						<option value="<?= $value['uniqid'] ?>"><?= $value['name'] ?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</section>
		<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop" id="image">
			<div class="head">
				<h2>Images</h2>
			</div>
			<div class="content">
				<div class="upload-image-list appUiKit-double-input img4x appUiKit-marginTop">
					<?php foreach($defaultImages as $key => $value): ?>
					<div class="itemupload-image">
						<div class="left-rendering">
							<img src="<?= $value['file'] ?? '' ?>" width="100%" height="100%">
						</div>
						<div class="right-information">
							<div class="text">
								<span class="title"><?= $value['name'] ?></span>
								<p class="subtitle">orem ipsum dolor sit amet, consectetur adipiscing elit. Morbi volutpat eu diam sed consequat.</p>
							</div>
							<span class="button-upload">
								<span class="text">Upload une image</span>
								<input type="file" name="image[]" onchange="getImage(this)">
								<input type="text" name="type[]" value="<?= $key ?>" hidden>
							</span>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
			<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop" id="lang">
				<div class="head">
					<h2>Langue des information</h2>
				</div>
				<div class="content">
					<div class="list">
						<?php

						?>
						<?php foreach ($defaultLang as $key => $value): ?>
						<?php
							$desc = CMS->DataBase->execute('SELECT * FROM enncore_catalogue_obj WHERE channel_id = ? AND name = ? AND type = "channel-description" ORDER BY ID DESC', [$catalogueInfo['uniqid'] ?? 'aa', $key])->fetchAll();
							if(isset($desc[0])) $desc = $desc[0];
							$title = CMS->DataBase->execute('SELECT * FROM enncore_catalogue_obj WHERE channel_id = ? AND name = ? AND type = "channel-title" ORDER BY ID DESC', [$catalogueInfo['uniqid'] ?? 'aa', $key])->fetchAll();
							if(isset($title[0])) $title = $title[0];

							if(!$desc) continue;
						?>
						<div class="element appUiKit-marginTop">
							<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
								<div class="appKitUi-input">
									<label>Langue</label>
									<select name="arr_lang[]">
										<?php foreach ($defaultLang as $key => $value): ?>
										<option value="<?= $key ?>"><?= $value ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="appKitUi-input">
									<label>Nom</label>
									<input type="text" name="arr_name[]" value="<?= CMS->GFunction->htmlDecode($title['content']) ?>">
								</div>
							</div>
							<div class="appKitUi-input appUiKit-marginTop">
								<label>Description</label>
								<textarea name="arr_content[]"><?= CMS->GFunction->htmlDecode($desc['content']) ?></textarea>
							</div>
						</div>
						<?php endforeach; ?>
						<div class="element appUiKit-marginTop">
							<div class="upload-image-list appUiKit-double-input appUiKit-marginTop">
								<div class="appKitUi-input">
									<label>Langue</label>
									<select name="arr_lang[]">
										<?php foreach ($defaultLang as $key => $value): ?>
										<option value="<?= $key ?>"><?= $value ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="appKitUi-input">
									<label>Nom</label>
									<input type="text" name="arr_name[]">
								</div>
							</div>
							<div class="appKitUi-input appUiKit-marginTop">
								<label>Description</label>
								<textarea name="arr_content[]"></textarea>
							</div>
						</div>
					</div>
				</div>
			</section>
	</form>
</section>