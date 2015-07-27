<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_less']);
$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_less');
$version = (isset($extensionConfiguration['compilerVersion']) ? $extensionConfiguration['compilerVersion'] : '1.7.0.3');

return array(
	'Less_Autoloader' => $extensionPath . 'Classes/Libraries/oyejorge/lessphp/' . $version . '/lib/Less/Autoloader.php',
);

?>