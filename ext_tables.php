<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_less']);

// Include ext_tables.php of themes.
if ($extensionConfiguration['compiler'] == 'lesscss') {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/LESSCSS/', 'ad: LESSCSS example');
} else {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Common/LESSPHP/', 'ad: LESSPHP common');
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/LESSPHP/', 'ad: LESSPHP example');
}

?>
