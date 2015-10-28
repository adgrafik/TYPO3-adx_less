
# TYPO3-adx_less

## Overview

This TYPO3 extension contains the LESS compiler http://lessphp.gpeasy.com/ which is compatible with Bootstrap 3.3.x.

- Supports a hook for \TYPO3\CMS\Core\Page\PageRenderer which compiles LESS files for `includeCSS`
- a function for USER-cObject
- hooks for rtehtmlarea, tinymce_rte and tinymce4_rte
- and a ViewHelper for Fluid.

To delete or regenerate compiled files proceed the "Flush general caches".


## TypoScript

### TypoScript properties

property | description | type | default
-------- | ----------- | ---- | -------
variables | Array of variables which schould be included to the compiler. Set the variable name as key and without `@`. | `array` | `NULL`
importDirectories | Comma seperated string and/or array of directories where should be look at `@import`. | `array|string` | `NULL`
targetFilename | If set the compiler will save the file with this name. | `string` | `NULL`
returnUri | If the keyword `absolute` is set, the compiler returns the absolute path to the file. If set to `siteURL` it returns the complete URL with `TYPO3_SITE_URL`. If `TRUE` the returned value is the relative path, else if `FALSE` it will return the parsed content. | `boolean|string` | `NULL`
compress | Set to `TRUE` if compiled CSS should be compressed. | `boolean` | `TRUE`
relativeUrls | Whether to adjust URL's to be relative. | `boolean` | `TRUE`
strictUnits | Whether units need to evaluate correctly. | `boolean` | `FALSE`
strictMath | Whether math has to be within parenthesis. | `boolean` | `FALSE`


### Configuration

	plugin.tx_adxless {
	
		variables {
			nice-blue = #5B83AD
			light-blue = @nice-blue + #111
		}

		# Comma seperated string or array with path => directory.
		# @see http://lessphp.gpeasy.com/
		importDirectories = 
	}

**Important!** Property `lessphp` has been removed since v1.1.1. Set `plugin.tx_adxless < plugin.tx_adxless.lessphp` in the TypoScript for backwards compatibility.


### USER-cObject

	page.headerData.1367742474 = COA
	page.headerData.1367742474 {
	
		# Set the USER content object where you want. The function includeCss will generate the CSS file and append it with the PageRenderer.
		10 = USER
		10.userFunc = AdGrafik\AdxLess\Utility\LessUtility->includeCss
		10.compilerSettings =< plugin.tx_adxless
		10.includeCssSettings {
			media = print
		}
		10.file = EXT:adx_less/Resources/Private/LESS/Example/Styles.less
		10.data (
	body {
	  border: 1px solid @nice-blue;
	}
	)
	}


## ViewHelper

Returns parsed LESS as CSS.

	{namespace less=AdGrafik\AdxLess\ViewHelpers}
	<less:compile data="[string]" variables="[array]" />
	<less:compileAndInclude data="[string]" variables="[array]" />


### ViewHelper properties

#### for less:compile

property | description | type | default
-------- | ----------- | ---- | -------
data | LESS data or path and filename to the LESS file. | `string` | `NULL`
variables | Array of variables which schould be included to the compiler. Set the variable name as key and without `@`. | `array` | `NULL`
importDirectories | Comma seperated string and/or array of directories where should be look at `@import`. | `array|string` | `NULL`
targetFilename | If set the compiler will save the file with this name. | `string` | `NULL`
returnUri | If the keyword `absolute` is set, the compiler returns the absolute path to the file. If set to `siteURL` it returns the complete URL with `TYPO3_SITE_URL`. If `TRUE` the returned value is the relative path, else if `FALSE` it will return the parsed content. | `boolean|string` | `NULL`
compress | Set to `TRUE` if compiled CSS should be compressed. | `boolean` | `TRUE`
relativeUrls | Whether to adjust URL's to be relative. | `boolean` | `TRUE`
strictUnits | Whether units need to evaluate correctly. | `boolean` | `FALSE`
strictMath | Whether math has to be within parenthesis. | `boolean` | `FALSE`


#### additinally for less:compileAndInclude

property | description | type | default
-------- | ----------- | ---- | -------
includeCssSettings | Same as TYPO3 property `page.includeCss` but without `stdWrap`. | `array` | `NULL`


## Hooks

### PageRenderer

    page.includeCSS.styles = path/to/my/style-file.less


### tinymce_rte

    RTE.default.init.content_css = path/to/my/style-file.less

or append multiply files

    RTE.default.init.content_css = path/to/my/style-file-1.less,path/to/my/style-file-2.less,path/to/my/style-file-3.less


### tinymce4_rte

    RTE.default.contentCSS = path/to/my/style-file.less

or append multiply files by comma seperated string

    RTE.default.contentCSS = path/to/my/style-file-1.less,path/to/my/style-file-2.less,path/to/my/style-file-3.less

or append multiply files by key

    RTE.default.contentCSS {
    	file1 = path/to/my/style-file-1.less
    	file2 = path/to/my/style-file-2.less
    	file3 = path/to/my/style-file-3.less
    }


### rtehtmlarea

    RTE.default.contentCSS = path/to/my/style-file.less


## Using in extensions

	// Create object
	$less = GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
	// Fetch extension configuration. Allowed parameters are cObject or PID.
	$configuration = AdGrafik\AdxLess\Utility\LessUtility::getConfiguration($GLOBALS['TSFE']->cObj);
	// Compile the LESS file. Will return the filepath of the parsed LESS file.
	$pathAndFilename = 'path/to/my/style-file.less';
	$compiledPathAndFilename = $less->compile($pathAndFilename, $configuration);