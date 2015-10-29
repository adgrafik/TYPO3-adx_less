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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use AdGrafik\AdxLess\Utility\LessUtility;

class CompileAndIncludeCssViewHelper extends AbstractViewHelper {

	/**
	 * @param string $data LESS data or path and filename to the LESS file.
	 * @param array $variables Array of variables which schould be included to the compiler. Set the variable name as key and without "@".
	 * @param mixed $importDirectories Comma seperated string and/or array of directories where should be look at @import. @see http://lessphp.gpeasy.com/
	 * @param string $targetFilename If set the compiler will save the file with this name.
	 * @param mixed $returnUri If the keyword "absolute" is set, the compiler returns the absolute path to the file. If set to "siteURL" it returns the complete URL with TYPO3_SITE_URL. If TRUE the returned value is the relative path, else if FALSE it will return the parsed content.
	 * @param boolean $compress Set to TRUE if compiled CSS should be compressed.
	 * @param boolean $relativeUrls Whether to adjust URL's to be relative.
	 * @param boolean $strictUnits Whether units need to evaluate correctly.
	 * @param boolean $strictMath Whether math has to be within parenthesis.
	 * @param array $includeCssSettings Same as TYPO3 property "page.includeCss" but without "stdWrap".
	 * @return string
	 * @api
	 */
	public function render($data = NULL, array $variables = NULL, $importDirectories = NULL, $targetFilename = NULL, $returnUri = TRUE, $compress = NULL, $relativeUrls = NULL, $strictUnits = NULL, $strictMath = NULL, array $includeCssSettings = array()) {

		if ($data === NULL) {
			$data = $this->renderChildren();
			if ($data === NULL) {
				return '';
			}
		}

		$configuration = array(
			'variables' => $variables,
			'importDirectories' => $importDirectories,
			'targetFilename' => $targetFilename,
			// returnUri can not be FALSE or "absolute".
			'returnUri' => ($returnUri === TRUE || $returnUri === 'siteURL') ? $returnUri : TRUE,
			'compress' => $compress,
			'relativeUrls' => $relativeUrls,
			'strictUnits' => $strictUnits,
			'strictMath' => $strictMath,
		);

		$less = GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$content = $less->compile($data, $configuration);

		LessUtility::addCssFile($content, $includeCssSettings);

		return '';
	}
}

?>