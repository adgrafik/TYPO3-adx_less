
# TYPO3-adx_less

## Overview

This TYPO3 extension contains the LESS compiler http://lessphp.gpeasy.com/ which is compatible with Bootstrap 3.3.x.

- Supports a new function for USER-cObject
- A hook for \TYPO3\CMS\Core\Page\PageRenderer which compiles LESS files for `includeCSS`
- A hooks for rtehtmlarea for the property `RTE.default.contentCSS` and tinymce_rte for the property `RTE.default.init.content_css`
- and a ViewHelper for Fluid.


## Configuration

With the property context it's possible to render LESS files in different context and configuration. The default context is `lessphp` for backwards compatibility.

	plugin.tx_adxless {
	
		lessphp {
			variables {
				nice-blue = #5B83AD
				light-blue = @nice-blue + #111
			}
	
			# Comma seperated string or array with path => directory.
			# @see http://lessphp.gpeasy.com/
			importDirectories = 
		}
	
		render-in-another-context < .lessphp
		render-in-another-context {
			variables {
				light-blue = @nice-blue + #444
			}
		}
	}


### TypoScript properties

property | type | default
-------- | ---- | -------
variables | `array` | `NULL`
compress | `boolean` | `TRUE`
relativeUrls | `boolean` | `TRUE`
strictUnits | `boolean` | `FALSE`
strictMath | `boolean` | `FALSE`
importDirectories | `array | string` | `NULL`
cacheDirectory | `string` | typo3temp/lesscache/
targetFile | `string` | `NULL`


## Hooks

### USER-cObject

    page.headerData.1367742474 = COA
    page.headerData.1367742474 {

      # Set the USER content object where you want. The function addLess will generate the CSS file and append it with the PageRenderer.
      10 = USER
      10.userFunc = EXT:adx_less/Classes/Less.php:AdGrafik\AdxLess\Less->addLess
      10.less.configuration =< plugin.tx_adxless.default
      10.less.file = path/to/my/style-file.less
      10.less.data (
    body {
      border: 1px solid @nice-blue;
    }
      )
    }


### ViewHelper

Returns parsed LESS file as CSS string.

    <ad:compile data="[string]" context="[string]" variables="[object]" importDirectories="[string]" />


### PageRenderer

    page.includeCSS.styles = path/to/my/style-file.less


### Extension-hook tinymce_rte

    RTE.default.init.content_css = path/to/my/style-file.less

or append multiply files

    RTE.default.init.content_css = path/to/my/style-file-1.less,path/to/my/style-file-2.less,path/to/my/style-file-3.less


### Extension-hook rtehtmlarea

    RTE.default.init.contentCSS = path/to/my/style-file.less


### Additional configurations for PageRenderer

If another context than the default context `lessphp` is needed prepend the context name as a query:

    page.includeCSS.styles = path/to/my/style-file.less?lessCompilerContext=render-in-another-context