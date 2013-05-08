<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

if (TYPO3_MODE == 'FE') {
	// Register page renderer hook.
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess']['adx_less'] = 'EXT:adx_less/Classes/Hooks/Pagerenderer.php:&Tx_AdxLess_Hooks_PageRenderer->preProcess';
} else {
	// add XCLASS to RTE rtehtmlarea
	$GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/rtehtmlarea/class.tx_rtehtmlarea_base.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/XClass/class.ux_tx_rtehtmlarea_base.php';
	// add XCLASS to RTE tinymce_rte
	$GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tinymce_rte/class.tx_tinymce_rte_base.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/XClass/class.ux_tx_tinymce_rte_base.php';
}

?>