<?php

class Tx_AdxLess_Hooks_Pagerenderer {

	/**
	 * stdWrapPreProcess
	 *
	 * @param array $configuration
	 * @param t3lib_PageRenderer $parentObject
	 * @return void
	 */
	public function preProcess($configuration, t3lib_PageRenderer $parentObject) {

		// nothing to do in backend
		if (TYPO3_MODE == 'BE') {
			return;
		}

		$settings = Tx_AdxLess_Utility_LessCompiler::getTypoScriptByContentObject($GLOBALS['TSFE']->cObj);
		$fileSuffixes = Tx_Extbase_Utility_Arrays::trimExplode(',', $settings['fileSuffixes']);

		foreach ($configuration['cssFiles'] as $fileName => &$fileConfiguration) {

			$found = FALSE;
			foreach ($fileSuffixes as $fileSuffix) {
				if (strpos($fileName, $fileSuffix) !== FALSE) {
					$found = TRUE;
					break;
				}
			}
			if (!$found) {
				continue;
			}

			// get source
			$sourceFilePathAndName = t3lib_div::getFileAbsFileName($fileName);
			$source = t3lib_div::getUrl($sourceFilePathAndName);
			$content = Tx_AdxLess_Utility_LessCompiler::compile($source, $settings['lessphp.']);

			// write file
			$fileConfiguration['file'] = TSpagegen::inline2TempFile($content, 'css');
		}
	}

}

?>