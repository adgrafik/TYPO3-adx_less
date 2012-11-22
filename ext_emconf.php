<?php

########################################################################
# Extension Manager/Repository config file for ext "adx_less".
#
# Auto generated 22-11-2012 15:00
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'ad: LESS',
	'description' => 'Contains a server- and client-side LESS compiler (http://leafo.net/lessphp/, http://lesscss.org). Supports a new cObject "LESS", a hook for t3lib_pagerenderer witch compiles LESS files for "includeCSS", hooks for rtehtmlarea for the property "RTE.default.contentCSS" and tinymce_rte for the property "RTE.default.init.content_css" and a ViewHelper for Fluid. Furthermore includes the Twitter Bootstrap grid system. Only the grid system and nothing more ;)',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Arno Dudek',
	'author_email' => 'webmaster@adgrafik.at',
	'author_company' => 'ad:grafik',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:92:{s:9:"ChangeLog";s:4:"1921";s:16:"ext_autoload.php";s:4:"7f11";s:12:"ext_icon.gif";s:4:"ef7e";s:17:"ext_localconf.php";s:4:"48e0";s:14:"ext_tables.php";s:4:"21af";s:35:"Classes/Hooks/ContentObjectLess.php";s:4:"723f";s:30:"Classes/Hooks/Pagerenderer.php";s:4:"360e";s:29:"Classes/LESSPHP/composer.json";s:4:"c6dd";s:29:"Classes/LESSPHP/lessc.inc.php";s:4:"2878";s:23:"Classes/LESSPHP/LICENSE";s:4:"2887";s:24:"Classes/LESSPHP/Makefile";s:4:"bf73";s:22:"Classes/LESSPHP/plessc";s:4:"7577";s:25:"Classes/LESSPHP/README.md";s:4:"0b7e";s:28:"Classes/LESSPHP/docs/docs.md";s:4:"938e";s:33:"Classes/LESSPHP/tests/ApiTest.php";s:4:"52df";s:34:"Classes/LESSPHP/tests/bootstrap.sh";s:4:"4c03";s:35:"Classes/LESSPHP/tests/InputTest.php";s:4:"141e";s:31:"Classes/LESSPHP/tests/README.md";s:4:"4571";s:30:"Classes/LESSPHP/tests/sort.php";s:4:"022e";s:51:"Classes/LESSPHP/tests/inputs/accessors.less.disable";s:4:"8282";s:39:"Classes/LESSPHP/tests/inputs/arity.less";s:4:"ad20";s:44:"Classes/LESSPHP/tests/inputs/attributes.less";s:4:"1cf5";s:42:"Classes/LESSPHP/tests/inputs/builtins.less";s:4:"4795";s:40:"Classes/LESSPHP/tests/inputs/colors.less";s:4:"ea89";s:50:"Classes/LESSPHP/tests/inputs/compile_on_mixin.less";s:4:"2f93";s:44:"Classes/LESSPHP/tests/inputs/directives.less";s:4:"6352";s:40:"Classes/LESSPHP/tests/inputs/escape.less";s:4:"1b77";s:45:"Classes/LESSPHP/tests/inputs/font_family.less";s:4:"9763";s:40:"Classes/LESSPHP/tests/inputs/guards.less";s:4:"e86a";s:39:"Classes/LESSPHP/tests/inputs/hacks.less";s:4:"4227";s:36:"Classes/LESSPHP/tests/inputs/hi.less";s:4:"2579";s:36:"Classes/LESSPHP/tests/inputs/ie.less";s:4:"4785";s:40:"Classes/LESSPHP/tests/inputs/import.less";s:4:"ca14";s:43:"Classes/LESSPHP/tests/inputs/keyframes.less";s:4:"843c";s:38:"Classes/LESSPHP/tests/inputs/math.less";s:4:"de57";s:39:"Classes/LESSPHP/tests/inputs/media.less";s:4:"365e";s:38:"Classes/LESSPHP/tests/inputs/misc.less";s:4:"7cb4";s:49:"Classes/LESSPHP/tests/inputs/mixin_functions.less";s:4:"f9dc";s:55:"Classes/LESSPHP/tests/inputs/mixin_merging.less.disable";s:4:"2a0b";s:40:"Classes/LESSPHP/tests/inputs/mixins.less";s:4:"ed57";s:40:"Classes/LESSPHP/tests/inputs/nested.less";s:4:"8472";s:50:"Classes/LESSPHP/tests/inputs/pattern_matching.less";s:4:"1158";s:40:"Classes/LESSPHP/tests/inputs/scopes.less";s:4:"7b89";s:54:"Classes/LESSPHP/tests/inputs/selector_expressions.less";s:4:"e012";s:44:"Classes/LESSPHP/tests/inputs/site_demos.less";s:4:"4699";s:43:"Classes/LESSPHP/tests/inputs/variables.less";s:4:"3fd7";s:48:"Classes/LESSPHP/tests/inputs/test-imports/a.less";s:4:"8c6f";s:48:"Classes/LESSPHP/tests/inputs/test-imports/b.less";s:4:"eb43";s:52:"Classes/LESSPHP/tests/inputs/test-imports/file1.less";s:4:"e904";s:52:"Classes/LESSPHP/tests/inputs/test-imports/file2.less";s:4:"cccf";s:52:"Classes/LESSPHP/tests/inputs/test-imports/file3.less";s:4:"936e";s:58:"Classes/LESSPHP/tests/inputs/test-imports/inner/file1.less";s:4:"55d8";s:58:"Classes/LESSPHP/tests/inputs/test-imports/inner/file2.less";s:4:"88fc";s:43:"Classes/LESSPHP/tests/outputs/accessors.css";s:4:"661e";s:39:"Classes/LESSPHP/tests/outputs/arity.css";s:4:"dfb1";s:44:"Classes/LESSPHP/tests/outputs/attributes.css";s:4:"4370";s:42:"Classes/LESSPHP/tests/outputs/builtins.css";s:4:"ab01";s:40:"Classes/LESSPHP/tests/outputs/colors.css";s:4:"c359";s:50:"Classes/LESSPHP/tests/outputs/compile_on_mixin.css";s:4:"2cdb";s:44:"Classes/LESSPHP/tests/outputs/directives.css";s:4:"66ba";s:40:"Classes/LESSPHP/tests/outputs/escape.css";s:4:"22c9";s:45:"Classes/LESSPHP/tests/outputs/font_family.css";s:4:"7a12";s:40:"Classes/LESSPHP/tests/outputs/guards.css";s:4:"9c36";s:39:"Classes/LESSPHP/tests/outputs/hacks.css";s:4:"313f";s:36:"Classes/LESSPHP/tests/outputs/hi.css";s:4:"b28c";s:36:"Classes/LESSPHP/tests/outputs/ie.css";s:4:"7c19";s:40:"Classes/LESSPHP/tests/outputs/import.css";s:4:"80d5";s:43:"Classes/LESSPHP/tests/outputs/keyframes.css";s:4:"72c2";s:38:"Classes/LESSPHP/tests/outputs/math.css";s:4:"9962";s:39:"Classes/LESSPHP/tests/outputs/media.css";s:4:"8736";s:38:"Classes/LESSPHP/tests/outputs/misc.css";s:4:"b407";s:49:"Classes/LESSPHP/tests/outputs/mixin_functions.css";s:4:"9604";s:47:"Classes/LESSPHP/tests/outputs/mixin_merging.css";s:4:"807f";s:40:"Classes/LESSPHP/tests/outputs/mixins.css";s:4:"c4c8";s:40:"Classes/LESSPHP/tests/outputs/nested.css";s:4:"8cef";s:41:"Classes/LESSPHP/tests/outputs/nesting.css";s:4:"7864";s:50:"Classes/LESSPHP/tests/outputs/pattern_matching.css";s:4:"9e87";s:40:"Classes/LESSPHP/tests/outputs/scopes.css";s:4:"9829";s:54:"Classes/LESSPHP/tests/outputs/selector_expressions.css";s:4:"18f1";s:44:"Classes/LESSPHP/tests/outputs/site_demos.css";s:4:"3143";s:43:"Classes/LESSPHP/tests/outputs/variables.css";s:4:"4757";s:35:"Classes/Utility/EidLessCompiler.php";s:4:"5c1c";s:32:"Classes/Utility/LessCompiler.php";s:4:"992c";s:41:"Classes/ViewHelpers/CompileViewHelper.php";s:4:"9f14";s:47:"Classes/XClass/class.ux_tx_rtehtmlarea_base.php";s:4:"23de";s:47:"Classes/XClass/class.ux_tx_tinymce_rte_base.php";s:4:"11c9";s:45:"Configuration/TypoScript/Common/constants.txt";s:4:"aea4";s:41:"Configuration/TypoScript/Common/setup.txt";s:4:"d934";s:42:"Configuration/TypoScript/Example/setup.txt";s:4:"345b";s:42:"Resources/Private/LESS/Example/Styles.less";s:4:"003c";s:45:"Resources/Private/Language/locallang_conf.xlf";s:4:"a11f";s:45:"Resources/Public/JavaScript/less-1.3.1.min.js";s:4:"3fe6";}',
	'suggests' => array(
	),
);

?>