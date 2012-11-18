<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionClassesPath = t3lib_extMgm::extPath('adx_less') . 'Classes/';

return array(
	'lessc' => $extensionClassesPath . 'LESSPHP/lessc.inc.php',
);

?>