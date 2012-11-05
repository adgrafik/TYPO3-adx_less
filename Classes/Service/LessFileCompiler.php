<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include_once('../LESSPHP/lessc.inc.php');

$file = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_URL'];

if (is_file($file)) {
	$less = new lessc;
	$content = file_get_contents($file);

	header('Content-type: text/css');
	echo $less->compile($content);
}

?>