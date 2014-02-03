<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

if (TYPO3_MODE == 'FE') {
	// Register page renderer hook.
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess']['adx_less'] = 'EXT:adx_less/Classes/Hooks/PageRenderer.php:&AdGrafik\AdxLess\Hooks\PageRenderer->preProcess';
}

// Add XCLASS to rtehtmlarea and tinymce_rte.
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Rtehtmlarea\\RteHtmlAreaBase'] = array(
	'className' => 'AdGrafik\\AdxLess\\XClass\\RteHtmlAreaBase',
);
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tx_tinymce_rte_base'] = array(
	'className' => 'AdGrafik\\AdxLess\\XClass\\TinyMceRteBase',
);

?>