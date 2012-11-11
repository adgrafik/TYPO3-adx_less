<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionPath = t3lib_extMgm::extPath('adx_less');
$extensionClassesPath = $extensionPath . 'Classes/';

return array(
	'lessc' => $extensionClassesPath . 'LESSPHP/lessc.inc.php',
);

?>