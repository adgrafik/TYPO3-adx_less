<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_less');

return array(
	'Less_Autoloader' => $extensionPath . 'Vendor/oyejorge/lessphp/lib/Less/Autoloader.php',
);

?>