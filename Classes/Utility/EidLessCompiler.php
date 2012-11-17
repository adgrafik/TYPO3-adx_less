<?php

$file = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REDIRECT_URL'];

if (is_file($file)) {

	$content = Tx_AdxLess_Utility_LessCompiler::compile(file_get_contents($file));

	header('Content-type: text/css');
	echo $content;
}

?>