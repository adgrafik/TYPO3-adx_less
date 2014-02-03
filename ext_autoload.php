<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionClassesPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_less') . 'Classes/';
$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_less']);

return array(
	'lessc' => $extensionClassesPath . 'LESSPHP/' . (isset($extensionConfiguration['serverCompilerVersion']) ? $extensionConfiguration['serverCompilerVersion'] : '0.3.9') . '/lessc.inc.php',
);

?>