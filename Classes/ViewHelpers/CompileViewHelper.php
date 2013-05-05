<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @package Fluid
 * @subpackage ViewHelpers
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class Tx_AdxLess_ViewHelpers_CompileViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param string $data
	 * @param string $formatter
	 * @param boolean $preserveComments
	 * @param array $variables
	 * @param string $importDirectories
	 * @return string
	 * @api
	 */
	public function render($data = NULL, $formatter = NULL, $preserveComments = NULL, $variables = NULL, $importDirectories = NULL) {

		if ($data === NULL) {
			$data = $this->renderChildren();
			if ($data === NULL) {
				return '';
			}
		}

		$configuration = array();

		if ($formatter !== NULL) {
			$configuration['formatter'] = $formatter;
		}

		if ($preserveComments !== NULL) {
			$configuration['preserveComments'] = $preserveComments;
		}

		if (count($variables)) {
			$configuration['variables'] = $variables;
		}

		if (count($importDirectories)) {
			$configuration['importDirectories'] = $importDirectories;
		}

		$less = t3lib_div::makeInstance('Tx_AdxLess_Less');
		$content = $less->compileLess($data, $this->contentObject);

		return $content;
	}
}
?>