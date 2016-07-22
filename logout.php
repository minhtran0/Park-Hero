<?php

require_once "global.php";

$facebook->destroySession();
$_SESSION = array();
session_destroy();

header("Location: index.php");

?>