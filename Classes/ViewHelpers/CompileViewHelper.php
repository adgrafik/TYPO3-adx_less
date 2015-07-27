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


class CompileViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param string $data
	 * @param string $formatter
	 * @param boolean $preserveComments
	 * @param array $variables
	 * @param string $importDirectories
	 * @return string
	 * @api
	 */
	public function render($data = NULL, $context = NULL, $variables = NULL, $importDirectories = NULL) {

		if ($data === NULL) {
			$data = $this->renderChildren();
			if ($data === NULL) {
				return '';
			}
		}

		$configuration = array();

		$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$configuration = \AdGrafik\AdxLess\Utility\LessUtility::getConfiguration($this->contentObject, $context);

		if (count($variables)) {
			$configuration['variables'] = array_replace($configuration['variables'], $variables);
		}

		if (count($importDirectories)) {
			$configuration['importDirectories'] = array_replace($configuration['importDirectories'], $importDirectories);
		}

		$content = $less->compile($data, $configuration);

		return $content;
	}
}
?>