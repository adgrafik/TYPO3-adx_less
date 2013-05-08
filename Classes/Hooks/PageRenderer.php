<?php

class Tx_AdxLess_Hooks_PageRenderer {

	/**
	 * Hook function for adding client library.
	 *
	 * @param array $configuration
	 * @return void
	 */
	public function preProcess($configuration, t3lib_PageRenderer $parentObject) {

		$less = t3lib_div::makeInstance('Tx_AdxLess_Less');

		// Include LESS library
		if ($less->isAlwaysIntegrate()) {
			$this->addClientCompilerLibrary();
		}

		$cssFiles = array();
		foreach ($configuration['cssFiles'] as $pathAndFilename => $cssConfiguration) {

			// If not a LESS file, nothing else to do.
			if (!strrpos($pathAndFilename, '.less')) {
				$cssFiles[$pathAndFilename] = $cssConfiguration;
				continue;
			}

			$compiledPathAndFilename = $less->compileLessAndWriteTempFile(
				t3lib_div::getFileAbsFileName($pathAndFilename),
				$GLOBALS['TSFE']->cObj
			);

			$cssConfiguration['file'] = $compiledPathAndFilename;

			if ($less->isClientSide()) {
				$cssConfiguration['rel'] = 'stylesheet/less';
			}

			$cssFiles[$compiledPathAndFilename] = $cssConfiguration;
		}

		$configuration['cssFiles'] = $cssFiles;
	}

}

?>