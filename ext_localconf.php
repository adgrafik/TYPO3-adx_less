<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// Register content object hook.
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array('LESS', 'EXT:adx_less/Classes/Hooks/ContentObjectLess.php:&Tx_AdxLess_Hooks_ContentObjectLess');
// Register stdWrap hook for includeFrontEndResources. Using "adx*" key to prevent colliding of other adx-extensions using the same hook.
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap']['adxIncludeFrontEndResources'] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/StdWrap.php:&Tx_AdxLess_Hooks_StdWrap';
// Register page renderer hook.
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = 'EXT:adx_less/Classes/Hooks/Pagerenderer.php:Tx_AdxLess_Hooks_Pagerenderer->preProcess';
// add XCLASS to RTE rtehtmlarea
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rtehtmlarea/class.tx_rtehtmlarea_base.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/XClass/class.ux_tx_rtehtmlarea_base.php';
// add XCLASS to RTE tinymce_rte
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce_rte/class.tx_tinymce_rte_base.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/XClass/class.ux_tx_tinymce_rte_base.php';
// Register eID.
$TYPO3_CONF_VARS['FE']['eID_include']['adxless'] = 'EXT:adx_less/Classes/Utility/EidLessCompiler.php';

?>