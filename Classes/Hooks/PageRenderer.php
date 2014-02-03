<?php
namespace AdGrafik\AdxLess\Hooks;

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


class PageRenderer {

	/**
	 * Hook function for adding client library.
	 *
	 * @param array $configuration
	 * @param \TYPO3\CMS\Core\Page\PageRenderer $parentObject
	 * @return void
	 */
	public function preProcess($configuration, \TYPO3\CMS\Core\Page\PageRenderer $parentObject) {

		$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');

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
				\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($pathAndFilename),
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