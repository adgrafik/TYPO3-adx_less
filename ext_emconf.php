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
	'description' => 'Contains the LESS compiler http://lessphp.gpeasy.com/ which is compatible with Bootstrap 3.3.x. Supports a new function for USER-cObject, hooks for diffrent RTEs which compiles LESS files for "includeCSS", "content_css" or "contentCSS" and a ViewHelper for Fluid.',
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
	'author_email' => '2015 Arno Dudek <webmaster@adgrafik.at',
	'author_company' => 'AdGrafik',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.2.0-7.99.99',
			'extbase' => '6.2.0-7.99.99',
			'cms' => '',
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