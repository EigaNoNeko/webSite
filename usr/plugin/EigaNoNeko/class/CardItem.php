<?php
/**
 * 
 */
class CardItem {
	function __construct() {
		define('__EnnCardItem', $this);
		//CMS->addModule('OmsyStat');
	}
/* <li>
	<a href="/catalogue/<?= $value['slug_'] ?? '' ?>">
		<div class="card-catalogue <?php if($key == "newEpisodesAdded") echo "lite"; ?>">
			<?php if($key != "newEpisodesAdded"): ?>
			<div class="thumbnail">
				<img src="<?= $value['image'] ?>" height="144" onerror="Core.imageErr(this, 'poster');">
			</div>
			<?php endif; ?>
			<div class="info">
				<span class="title"><?= __OmsyString->htmlDecode(__OmsyString->subStr($value['title'], 45)) ?></span>
				<?php if($key == "newEpisodesAdded"): ?>
				<span class="subtext"><?= __OmsyString->convertDate($value['last_']) ?></span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</li> */
	public function poster($obj) {
?>
<li>
	<a href="<?= $obj['href'] ?>">
		<div class="card-catalogue <?php if(!isset($obj['image'])) echo "lite"; ?>">
			<?php if(isset($obj['image'])): ?>
			<div class="thumbnail">
				<img src="<?= $obj['image'] ?>" height="144" onerror="Core.imageErr(this, 'poster');">
			</div>
			<?php endif; ?>
			<div class="info">
				<span class="title">
					<?php
					echo __OmsyString->htmlDecode(
						__OmsyString->subStr(
							$obj['title'],
							45
						)
					);
					?>
				</span>
				<?php if(isset($obj['sub_title'])): ?>
				<span class="subtext">
					<?php
					echo __OmsyString->htmlDecode(
						__OmsyString->subStr(
							$obj['sub_title'],
							45
						)
					);
					?>
				</span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</li>
<?php
	}

	public function article($obj) {
?>
<li>
	<a href="<?= $obj['href'] ?>">
		<div class="card-catalogue <?php if(!isset($obj['image'])) echo "lite"; ?>">
			<?php if(isset($obj['image'])): ?>
			<div class="thumbnail">
				<img src="<?= $obj['image'] ?>" height="144" onerror="Core.imageErr(this, 'poster');">
			</div>
			<?php endif; ?>
			<div class="info">
				<span class="title">
					<?php
					echo __OmsyString->htmlDecode(
						__OmsyString->subStr(
							$obj['title'],
							45
						)
					);
					?>
				</span>
				<?php if(isset($obj['sub_title'])): ?>
				<span class="subtext">
					<?php
					echo __OmsyString->htmlDecode(
						__OmsyString->subStr(
							$obj['sub_title'],
							45
						)
					);
					?>
				</span>
				<?php endif; ?>
			</div>
		</div>
	</a>
</li>
<?php
	}

	public function people($obj) {
?>
<li>
	<div class="card-people">
		<div class="thumbnail people">
			<a href="<?= $obj['people']['href'] ?>">
				<img src="<?= $obj['people']['image'] ?>" height="82px" onerror="Core.imageErr(this, 'poster');">
			</a>
		</div>
		<div class="info people">
			<span class="title">
				<a href="<?= $obj['people']['href'] ?>">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
							$obj['people']['title'],
							45
						)
				);
				?>
				</a>
			</span>
			<?php if(isset($obj['people']['sub_title'])): ?>
			<span class="subtext">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
							$obj['people']['sub_title'],
							45
						)
				);
				?>
			</span>
			<?php endif; ?>
		</div>
		<?php if(isset($obj['character'])): ?>
		<div class="info character">
			<span class="title">
				<a href="<?= $obj['character']['href'] ?>">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
							$obj['character']['title'],
							45
						)
				);
				?>
				</a>
			</span>
			<?php if(isset($obj['character']['sub_title'])): ?>
			<span class="subtext">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
							$obj['character']['sub_title'],
							45
						)
				);
				?>
			</span>
			<?php endif; ?>
		</div>
		<div class="thumbnail character">
			<a href="<?= $obj['character']['href'] ?>">
				<img src="<?= $obj['character']['image'] ?>" height="82px" onerror="Core.imageErr(this, 'poster');">
			</a>
		</div>
		<?php endif; ?>
	</div>
</li>
<?php
	}

	public function playlist($obj) {
?>
<li>
	<div class="card-playlist">
		<div class="thumbnail people">
			<a href="<?= $obj['people']['href'] ?>">
				<img src="<?= $obj['people']['image'] ?>" height="82px" onerror="Core.imageErr(this, 'poster');">
			</a>
		</div>
		<div class="info people">
			<span class="title">
				<a href="<?= $obj['people']['href'] ?>">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
							$obj['people']['title'],
							45
						)
				);
				?>
				</a>
			</span>
			<?php if(isset($obj['people']['sub_title'])): ?>
			<span class="subtext">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
						$obj['people']['sub_title'],
						45
					)
				);
				?>
			</span>
			<?php endif; ?>
		</div>
	</div>
</li>
<?php
	}


	public function user($obj) {
?>
<li>
	<div class="card-playlist">
		<div class="thumbnail people">
			<a href="<?= $obj['people']['href'] ?>">
				<img src="<?= $obj['people']['image'] ?>" height="82px" onerror="Core.imageErr(this, 'poster');">
			</a>
		</div>
		<div class="info people">
			<span class="title">
				<a href="<?= $obj['people']['href'] ?>">
				<?php
				echo __OmsyString->subStr(
						$obj['people']['title'],
						45
					);
				?>
				</a>
			</span>
			<?php if(isset($obj['people']['sub_title'])): ?>
			<span class="subtext">
				<?php
				echo __OmsyString->htmlDecode(
					__OmsyString->subStr(
						$obj['people']['sub_title'],
						45
					)
				);
				?>
			</span>
			<?php endif; ?>
		</div>
	</div>
</li>
<?php
	}
}