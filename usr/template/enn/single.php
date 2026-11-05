<?php
$Args = __OmsySecurity->getArgs();
$getBlogSingle = __OmsySql->execute('SELECT * FROM omsyblog_single WHERE blog_slug = ? AND blog_group = ?', [$Args[2],$Args[1]])->fetchAll();

if(!isset($getBlogSingle[0])) return __OmsyInterface->err(404);
$getBlogSingle = $getBlogSingle[0];

$user=__OmsyAccounts->getInfo($getBlogSingle['blog_author']);

include "interface/single.php";