<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// Include ext_tables.php of themes.
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Common/', 'ad: LESSPHP common');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/', 'ad: LESSPHP example');

?>