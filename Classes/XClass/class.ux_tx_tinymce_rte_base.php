<?php

class ux_tx_tinymce_rte_base extends tx_tinymce_rte_base {

	/**
	 * @param array
	 * @return string
	 */
	public function parseConfig($configuration) {

		if (!isset($configuration['content_css']) || !$configuration['content_css']) {
			return parent::parseConfig($configuration);
		}

		$settings = Tx_AdxLess_Utility_LessCompiler::getTypoScriptByPageUid($this->currentPage);
		$fileSuffixes = Tx_Extbase_Utility_Arrays::trimExplode(',', $settings['fileSuffixes']);
		$cssFiles = array();

		$files = Tx_Extbase_Utility_Arrays::trimExplode(',', $configuration['content_css']);

		foreach ($files as $fileName) {

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
			$content = Tx_AdxLess_Utility_LessCompiler::compile($source, $settings);

			// write file
			$cssFiles[] = TSpagegen::inline2TempFile($content, 'css');
		}

		if (count($cssFiles)) {
			$configuration['content_css'] = implode(',', $cssFiles);
		}

		return parent::parseConfig($configuration);
	}

}

?>