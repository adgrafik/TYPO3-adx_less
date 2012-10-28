<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// register content object hook
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClassDefault']['media'] = 'EXT:adx_less/Classes/ContentObject/Less.php:Tx_AdxLess_ContentObject_Less';

?>