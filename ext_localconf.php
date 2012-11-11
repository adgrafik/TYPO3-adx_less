<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// register content object hook
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass'][] = array('LESS', 'EXT:adx_less/Classes/Hooks/ContentObjectLess.php:&Tx_AdxLess_Hooks_ContentObjectLess');
// register page renderer hook
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = 'EXT:adx_less/Classes/Hooks/Pagerenderer.php:Tx_AdxLess_Hooks_Pagerenderer->preProcess';
// register eID
$TYPO3_CONF_VARS['FE']['eID_include']['adxless'] = 'EXT:adx_less/Classes/Utility/EidLessCompiler.php';

?>