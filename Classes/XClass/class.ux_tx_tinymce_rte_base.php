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

		$cssFiles = array();
		$less = t3lib_div::makeInstance('Tx_AdxLess_Less');
		$files = Tx_Extbase_Utility_Arrays::trimExplode(',', $configuration['content_css']);
		foreach ($files as $filename) {

			// If not a less file, nothing else to do.
			if (!strrpos($filename, '.less')) {
				continue;
			}

			$cssFiles[] = $less->compileLessAndWriteTempFile(
				t3lib_div::getFileAbsFileName($filename),
				$this->currentPage
			);
		}

		if (count($cssFiles)) {
			$configuration['content_css'] = implode(',', $cssFiles);
		}

		return parent::parseConfig($configuration);
	}

}

?>