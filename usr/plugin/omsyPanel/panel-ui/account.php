<?php
if(!__OmsyPermission->can(__OmsyPermission->OPEN, '00000007')) die(); //__OmsyMain->OHeader->DynamicHeaderError(400);
if(isset($_GET['edit'])) $user=__OmsyAccounts->getAccount($_GET['edit']);
?>
<?= omsp_header() ?>
<?php
HMain([
	"name" => "Modifier un compte",
	"ul" => [
		[
			"id" => "accounts",
			"name" => "Liste des comptes",
			"href" => "/panel/getter?plugin=omsypanel&page=accounts",
		]
	]
]);
?>
<section class="appkit-form-contenaire app-kit-main-padding">
	<form method="post" enctype="multipart/form-data">
		<input name="send_form_lang" value="true" hidden>
		<section class="scroll-section kit-block kit-input-block appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Détails</h2>
			</div>
			<div class="content">
				<div class="upload-image-list appUiKit-double-input img4x appUiKit-marginTop kit-flex-input kit-d4-input">
					<div class="itemupload-image kit-input" style="width: 200px; height: 200px;">
						<div class="left-rendering">
							<img src="<?= $user['user_avatar'] ?? '/panel/interface/image/avatar/haha.png' ?>" height="200px" width="100%">
						</div>
						<div class="right-information">
							<div class="text">
								<span class="title">Avatar</span>
								<p class="subtitle"></p>
							</div>
							<span class="button-upload">
								<span class="text">
									
								</span>
								<input type="file" name="avatar" onchange="getImage(this, this.parentNode.parentNode.parentNode.querySelector('img'))">
							</span>
						</div>
					</div>
				</div>
				<div class="kit-flex-input kit-d2-input multi-input">
					<div class="kit-input appUiKit-marginTop">
						<label>Username</label>
						<input type="text" name="default_name" value="<?= $user['username'] ?? '' ?>">
					</div>
					<div class="kit-input appUiKit-marginTop">
						<label>E-mail</label>
						<input type="text" name="default_categorie" value="<?= $user['user_mail'] ?? '' ?>">
					</div>
					<div class="kit-input appUiKit-marginTop">
						<label>Permission</label>
						<select name="parent">
							<?php foreach (__OmsyPermission->getPermissions() as $key => $value): ?>
							<option value="<?= $key ?>" <?php if($key == intval($user['role'] ?? 0)) echo 'selected'; ?>><?= $value ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</section>
		<section class="scroll-section kit-block kit-input-block appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Objects</h2>
			</div>
			<div class="content">
				<div class="kit-flex-input kit-d2-input multi-input">
					<div class="kit-input appUiKit-marginTop">
						<label>Object name</label>
						<input type="text" name="object_name[]">
					</div>
					<div class="kit-input appUiKit-marginTop">
						<label>Object content</label>
						<input type="text" name="object_content[]">
					</div>
				</div>
				<?php
				foreach($user ?? [] as $key => $value):
					if(in_array($key, ['ID', 'uniqid', 'password', 'username', 'role', 'register_date', 'user_mail'])) continue;
				?>
				<div class="kit-flex-input kit-d2-input multi-input">
					<div class="kit-input appUiKit-marginTop">
						<label>Object name</label>
						<input type="text" name="object_name[]" value="<?= $key ?? '' ?>">
					</div>
					<div class="kit-input appUiKit-marginTop">
						<label>Object content</label>
						<input type="text" name="object_content[]" value="<?= $value ?? '' ?>">
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</section>
	</form>
</section>
<?= omsp_footer() ?>