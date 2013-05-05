<?php

class Tx_AdxLess_Hooks_Pagerenderer {

	/**
	 * Hook function for adding client library.
	 *
	 * @param array $configuration
	 * @return void
	 */
	public function preProcess($configuration, t3lib_PageRenderer $parentObject) {
		$less = t3lib_div::makeInstance('Tx_AdxLess_Less');

		if ($less->isAlwaysIntegrate()) {
			$this->addClientCompilerLibrary();
		}
	}

}

?>