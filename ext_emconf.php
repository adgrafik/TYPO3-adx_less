<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "adx_less".
 *
 * Auto generated 03-02-2014 16:12
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'ad: LESS',
	'description' => 'Contains a server- and client-side LESS compiler (http://leafo.net/lessphp/, http://lesscss.org). Supports a new cObject "LESS", a hook for t3lib_pagerenderer witch compiles LESS files for "includeCSS", hooks for rtehtmlarea for the property "RTE.default.contentCSS" and tinymce_rte for the property "RTE.default.init.content_css" and a ViewHelper for Fluid. Furthermore includes the Twitter Bootstrap grid system. Only the grid system and nothing more ;)',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.1.0',
	'dependencies' => 'cms,version',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Arno Dudek',
	'author_email' => 'webmaster@adgrafik.at',
	'author_company' => 'ad:grafik',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'cms' => '',
			'version' => '',
			'php' => '5.3.3-0.0.0',
			'typo3' => '6.0.0-6.1.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'suggests' => 
	array (
	),
);

?>