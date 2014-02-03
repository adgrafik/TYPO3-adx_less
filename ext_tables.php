<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');

// Include ext_tables.php of themes.
if ($less->isClientSide()) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/LESSCSS/', 'ad: LESSCSS example');
} else {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Common/LESSPHP/', 'ad: LESSPHP common');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/LESSPHP/', 'ad: LESSPHP example');
}

?>