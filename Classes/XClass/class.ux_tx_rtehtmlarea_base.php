<?php

class ux_tx_rtehtmlarea_base extends tx_rtehtmlarea_base {

	/**
	 * @return string
	 */
	public function getContentCssFileName() {

		if (!isset($this->thisConfig['contentCSS']) || !$this->thisConfig['contentCSS'] || !strrpos($this->thisConfig['contentCSS'], '.less')) {
			return parent::getContentCssFileName();
		}

		$less = t3lib_div::makeInstance('Tx_AdxLess_Less');
		$this->thisConfig['contentCSS'] = $less->compileLessAndWriteTempFile(
			t3lib_div::getFileAbsFileName($this->thisConfig['contentCSS']),
			$this->currentPage
		);

		return parent::getContentCssFileName();
	}

}

?>