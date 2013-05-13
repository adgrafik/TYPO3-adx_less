<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionClassesPath = t3lib_extMgm::extPath('adx_less') . 'Classes/';
$extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_less']);

return array(
	'tx_adxless_less' => $extensionClassesPath . 'Less.php',
	'lessc' => $extensionClassesPath . 'LESSPHP/' . (isset($extensionConfiguration['serverCompilerVersion']) ? $extensionConfiguration['serverCompilerVersion'] : '0.3.9') . '/lessc.inc.php',
	'ux_tx_tinymce_rte_base' => $extensionClassesPath . 'XClass/class.ux_tx_tinymce_rte_base.php',
	'ux_tx_rtehtmlarea_base' => $extensionClassesPath . 'XClass/class.ux_tx_rtehtmlarea_base.php',
);

?>