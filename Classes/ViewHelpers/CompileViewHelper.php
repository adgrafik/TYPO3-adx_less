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

include_once('../LESSPHP/lessc.inc.php');

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
	 * @param array $importDirectories
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

		$less = new lessc;

		if ($formatter !== NULL) {
			$less->setFormatter($formatter);
		}

		if ($preserveComments !== NULL) {
			$less->setPreserveComments((boolean) $preserveComments);
		}

		if (count($variables)) {
			$less->setVariables($variables);
		}

		if (count($importDirectories)) {
			$less->setImportDir($importDirectories);
		}

		$content = $less->compile($data);

		return $content;
	}
}
?>