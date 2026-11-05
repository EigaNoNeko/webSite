<?= omsp_header() ?>
<section class="appUiKit-user-top-menu">
	<div class="head">
		<h1>Liste des playlist</h1>
	</div>
	<div class="content">
		<ul>
			<li><a class="active" href="/panel/getter?plugin=omsyPanel&page=/template" data-update="embed">Home</a></li>
		</ul>
	</div>
</section>
<section class="appUiKit-table app-kit-main-padding">
	<div class="head">
		<h2>Liste</h2>
	</div>
	<div class="content">
	    <table>
	        <tr class="">
				<td></td>
	            <td><b>Name</b></td>
	            <td><b>Autogen</b></td>
	            <td><b>Date</b></td>
	        </tr>
	        <?php foreach ($allPlaylist as $key => $value): ?>
			<tr>
	            <td>
	                <input type="checkbox" name="">
	            </td>
				<td class="text large">
					<a href="/panel/getter?plugin=ennNode&page=playlist&ID=<?= $value['ID'] ?>">
						<b><?= $value['name'] ?></b>
					</a>
				</td>
				<td class="text large"><?php if($value['autogen'] == 1) { echo 'oui'; } else { echo 'non'; } ?></td>
				<td class="text large"><?= __OmsyMain->GFunction->convertDate($value['date_register']) ?></td>
			</tr>
	        <?php endforeach; ?>
	    </table>
	</div>
</section>
<?= omsp_footer() ?>