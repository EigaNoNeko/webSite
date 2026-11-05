<?php
session_start();
$_SESSION = Null;
session_destroy();
header('Location: /');