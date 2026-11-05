<?php
$getBlog = null;
if(isset($_GET['edit'])) {
	$getBlog = __OmsySql->execute('SELECT * FROM omsyblog_single WHERE ID = ?', [$_GET['edit']])->fetchAll();
	if(isset($getBlog[0])) $getBlog = $getBlog[0];
}

if(isset($_POST['submit_form'])) {
	if(
		!empty($_POST['name']) &&
		!empty($_POST['group']) &&
		!empty($_POST['tags']) &&
		!empty($_POST['content'])
	) {
		$name = $_POST['name'];
		$group = $_POST['group'];
		$tags = $_POST['tags'];
		$content = $_POST['content'];
		$min = $_POST['min'];

		$slugName = __OmsyMain->GFunction->slugify($name);

		__OmsySql->execute(
			'INSERT INTO omsyblog_single(blog_author, blog_group, blog_min, blog_title, blog_slug, blog_content, blog_tags, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, now())',
			[$_SESSION['uniqid'], $group, $min, $name, $slugName, $content, $tags]
		);
	}
}

include "interface/post.php";