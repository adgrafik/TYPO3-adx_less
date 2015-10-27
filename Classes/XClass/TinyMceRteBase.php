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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use AdGrafik\AdxLess\Utility\LessUtility;

class TinyMceRteBase extends \tx_tinymce_rte_base {

	/**
	 * @param array
	 * @return string
	 */
	public function parseConfig($parameters) {

		if (!isset($parameters['content_css']) || !$parameters['content_css']) {
			return parent::parseConfig($parameters);
		}

		$cssFiles = array();
		$less = GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$files = GeneralUtility::trimExplode(',', $parameters['content_css']);
		foreach ($files as $pathAndFilename) {

			// If not a LESS file, nothing else to do.
			if (pathinfo($pathAndFilename,  PATHINFO_EXTENSION) !== 'less') {
				$cssFiles[] = $pathAndFilename;
				continue;
			}

			$configuration = LessUtility::getConfiguration($this->currentPage);
			$cssFiles[] = $less->compile($pathAndFilename, $configuration);
		}

		if (count($cssFiles)) {
			$parameters['content_css'] = implode(',', $cssFiles);
		}

		return parent::parseConfig($parameters);
	}

}

?>