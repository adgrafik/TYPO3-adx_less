<?php
namespace AdGrafik\AdxLess\XClass;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Arno Dudek <webmaster@adgrafik.at>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


class RichTextElement extends \TYPO3\CMS\Rtehtmlarea\Form\Element\RichTextElement {

	/**
	 * @return string
	 */
	public function render() {

		return parent::render();
	}

	/**
	 * @return string
	 */
	protected function getContentCssFileNames() {

		$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$configuration = \AdGrafik\AdxLess\Utility\LessUtility::getConfiguration($this->pidOfPageRecord);

		if (isset($this->processedRteConfiguration['contentCSS'])) {
			$this->processedRteConfiguration['contentCSS'] = $less->compile($this->processedRteConfiguration['contentCSS'], $configuration);
		}

		if (is_array($this->processedRteConfiguration['contentCSS.'])) {
			foreach ($this->processedRteConfiguration['contentCSS.'] as $key => $cssFile) {
				// If not a LESS file, nothing else to do.
				if (pathinfo($cssFile,  PATHINFO_EXTENSION) !== 'less') {
					continue;
				}
				$this->processedRteConfiguration['contentCSS.'][$key] = $less->compile($cssFile, $configuration);
			}
		}

		return parent::getContentCssFileNames();
	}

}

?>