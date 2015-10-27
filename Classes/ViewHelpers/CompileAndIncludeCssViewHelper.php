<?php
namespace AdGrafik\AdxLess\ViewHelpers;

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


class CompileAndIncludeCssViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param string $data
	 * @param array $variables
	 * @param mixed $importDirectories Comma seperated string or array with path => directory. @see http://lessphp.gpeasy.com/
	 * @param string $targetFilename
	 * @param mixed $returnUri
	 * @param boolean $compress
	 * @param boolean $relativeUrls Disable relativeUrls and set resource URLs relative to the cache directory to prevent unwanted side effects.
	 * @param boolean $strictUnits
	 * @param boolean $strictMath
	 * @param array $includeCssSettings
	 * @return string
	 * @api
	 */
	public function render($data = NULL, array $variables = NULL, $importDirectories = NULL, $targetFilename = NULL, $returnUri = TRUE, $compress = NULL, $relativeUrls = NULL, $strictUnits = NULL, $strictMath = NULL, array $includeCssSettings = NULL) {

		if ($data === NULL) {
			$data = $this->renderChildren();
			if ($data === NULL) {
				return '';
			}
		}

		$configuration = array(
			'compress' => $compress,
			'variables' => $variables,
			'importDirectories' => $importDirectories,
			'relativeUrls' => $relativeUrls,
			'strictUnits' => $strictUnits,
			'strictMath' => $strictMath,
			'targetFilename' => $targetFilename,
			// returnUri can not be FALSE or "absolute".
			'returnUri' => ($returnUri === TRUE || $returnUri === 'siteURL') ? $returnUri : TRUE,
		);

		$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$content = $less->compile($data, $configuration);

		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		$pageRenderer->addCssFile(
			$content,
			'stylesheet',
			$includeCssSettings['media'] ? $includeCssSettings['media'] : 'all',
			$includeCssSettings['title'] ? $includeCssSettings['title'] : '',
			$includeCssSettings['compress'] ? $includeCssSettings['compress'] : TRUE,
			$includeCssSettings['forceOnTop'] ? $configuration['forceOnTop'] : FALSE,
			$includeCssSettings['allWrap'] ? $includeCssSettings['allWrap'] : '',
			$includeCssSettings['excludeFromConcatenation'] ? $includeCssSettings['excludeFromConcatenation'] : FALSE
		);

		return '';
	}
}

?>