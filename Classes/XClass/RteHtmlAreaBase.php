<?php
namespace AdGrafik\AdxLess\XClass;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Arno Dudek <webmaster@adgrafik.at>
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


class RteHtmlAreaBase extends \TYPO3\CMS\Rtehtmlarea\RteHtmlAreaBase {

	/**
	 * @return string
	 */
	public function getContentCssFileName() {

		if (!isset($this->thisConfig['contentCSS']) || !$this->thisConfig['contentCSS'] || !strrpos($this->thisConfig['contentCSS'], '.less')) {
			return parent::getContentCssFileName();
		}

		$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$this->thisConfig['contentCSS'] = $less->compileLessAndWriteTempFile(
			\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->thisConfig['contentCSS']),
			$this->currentPage
		);

		return parent::getContentCssFileName();
	}

}

?>