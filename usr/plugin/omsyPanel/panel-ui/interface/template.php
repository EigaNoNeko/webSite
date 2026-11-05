<?= omsp_header() ?>
<section class="kit-head">
	<div class="kit-grid-contenaire">
		<div class="information">
			<h2>Changer le thème</h2>
		</div>
	</div>
	<div class="kit-grid-contenaire">
		<ul></ul>
	</div>
</section>
<section class="kit-contenaire">
<?php
SQLX_table([
	"head" => [
		"Name",
	],
	"body" => $buff
]);
?>
</section>
<?= omsp_footer() ?>