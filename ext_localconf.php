<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// Set cache configuration.
$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['adx_less'] = array(
	'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend',
	'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
);

// Register page renderer hook.
if (TYPO3_MODE == 'FE') {
	$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess']['adx_less'] = 'EXT:adx_less/Classes/Hooks/PageRenderer.php:&AdGrafik\AdxLess\Hooks\PageRenderer->preProcess';
}

// Add XCLASS to rtehtmlarea and tinymce_rte.
$TYPO3_CONF_VARS['SYS']['Objects']['TYPO3\\CMS\\Rtehtmlarea\\RteHtmlAreaBase'] = array(
	'className' => 'AdGrafik\\AdxLess\\XClass\\RteHtmlAreaBase',
);
$TYPO3_CONF_VARS['SYS']['Objects']['tx_tinymce_rte_base'] = array(
	'className' => 'AdGrafik\\AdxLess\\XClass\\TinyMceRteBase',
);

?>