<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$less = t3lib_div::makeInstance('Tx_AdxLess_Less');

// Include ext_tables.php of themes.
if ($less->isClientSide()) {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/LESSCSS/', 'ad: LESSCSS example');
} else {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Common/LESSPHP/', 'ad: LESSPHP common');
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/LESSPHP/', 'ad: LESSPHP example');
}

?>