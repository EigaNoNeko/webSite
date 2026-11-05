<?php include "include/head.php"; ?>
<section class="enn-main-catalogue enn-grid-catalogue">
	<section class="left">
		<section class="enn-kit enn-kit-block-background enn-kit-block-style enn-kit-mgb depot-form-contenaire">
			<div class="head">
				<div>
					<h2>Ajouter au catalogue</h2>
					<?php if(isset($_GET['checked'])): ?>
					<font color="greeen">Votre demande a été transmise</font>
					<?php endif; ?>
				</div>
			</div>
			<div class="content">
				<div class="">
					<p>Votre demande seras ajouter par un administrateur si elle n'ais pas dans le catalogue</p>
				</div>
				<form class="enn-kit-form" method="post">
					<div class="enn-kit-input enn-kit-input-margin">
						<label>Titre de l'ajout</label>
						<input type="text" name="name">
					</div>
					<div class="enn-kit-input-submit enn-kit-input-margin">
						<input type="submit" name="submit" value="Valider">
					</div>
				</form>
			</div>
		</section>
	</section>
</section>
<?php include "include/footer.php"; ?>