<?= omsp_header() ?>
<?php
HMain([
	"name" => "Ajouter une variable",
	"ul" => [
		[
			"id" => "channels",
			"name" => "Liste des chaine",
			"href" => "/panel/getter?plugin=ennNode&page=channels",
		]
	]
]);

$getVar = __OmsySql->execute('SELECT * FROM omsypage_var WHERE ID = ?', [$_GET['id'] ?? 0])->fetchAll();

if(isset($getVar[0])) {
	$type = $getVar[0]['type'];
	$name = $getVar[0]['name'];
	$content = $getVar[0]['content'];
}

if(isset($_POST['send_form_lang'])) {
	if(
		!empty($_POST['type']) &&
		!empty($_POST['name']) &&
		!empty($_POST['content'])
	) {
		$type = htmlentities($_POST['type']);
		$name = htmlentities($_POST['name']);
		$content = htmlentities($_POST['content']);

		if(!isset($getVar[0])) __OmsyPage->addvar($type, $name, $content);
		else __OmsyPage->updatevar($getVar[0]['ID'], $type, $name, $content);
	}
}
?>
<section class="appkit-form-contenaire">
	<form method="post" enctype="multipart/form-data">
		<input name="send_form_lang" value="true" hidden>
		<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop" id="about">
			<div class="head">
				<h2>Détails</h2>
			</div>
			<div class="content">
				<div class="multi-input appUiKit-double-input appUiKit-marginTop">
					<div class="appKitUi-input">
						<label>Type</label>
						<input type="text" name="type" value="<?= $type ?? '' ?>">
					</div>
					<div class="appKitUi-input">
						<label>Name</label>
						<input type="text" name="name" value="<?= $name ?? '' ?>">
					</div>
				</div>
				<div class="appKitUi-input appUiKit-marginTop">
					<label>Content</label>
					<input type="text" name="content" value="<?= $content ?? '' ?>">
				</div>
			</div>
		</section>
	</form>
</section>
<?= omsp_footer() ?>