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

use TYPO3\CMS\Backend\Form\FormEngine;
use TYPO3\CMS\Backend\Rte\AbstractRte;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class TinyMce4RteBase extends \SGalinski\Tinymce4Rte\Editors\RteBase {

	/**
	 * @param FormEngine $parentObject Reference to parent object, which is an instance of the TCEforms.
	 * @param string $table The table name
	 * @param string $field The field name
	 * @param array $row The current row from which field is being rendered
	 * @param array $PA Array of standard content for rendering form fields from TCEforms. See TCEforms for details on this. Includes for instance the value and the form field name, java script actions and more.
	 * @param array $specConf "special" configuration - what is found at position 4 in the types configuration of a field from record, parsed into an array.
	 * @param array $thisConfig Configuration for RTEs; A mix between TSconfig and otherwise. Contains configuration for display, which buttons are enabled, additional transformation information etc.
	 * @param string $RTEtypeVal Record "type" field value.
	 * @param string $RTErelPath Relative path for images/links in RTE; this is used when the RTE edits content from static files where the path of such media has to be transformed forth and back!
	 * @param integer $thePidValue PID value of record (true parent page id)
	 * @return string HTML code for RTE!
	 */
	public function drawRTE(FormEngine $parentObject, $table, $field, $row, $PA, $specConf, $thisConfig, $RTEtypeVal, $RTErelPath, $thePidValue) {

		// add the tinymce code and it's configuration
		if (!self::$coreLoaded) {
			/** @var PageRenderer $pageRenderer */
			$pageRenderer = $GLOBALS['SOBE']->doc->getPageRenderer();
			$userOrPageProperties = BackendUtility::getModTSconfig($thePidValue, 'RTE');

			if (!isset($userOrPageProperties['properties']['default.']['contentCSS']) || !$userOrPageProperties['properties']['default.']['contentCSS'] || !strrpos($userOrPageProperties['properties']['default.']['contentCSS'], '.less')) {
				return parent::drawRTE($parentObject, $table, $field, $row, $PA, $specConf, $thisConfig, $RTEtypeVal, $RTErelPath, $thePidValue);
			}

			self::$coreLoaded = TRUE;

			/** @var \tinyMCE $tinyMCE */
			require_once(ExtensionManagementUtility::extPath('tinymce') . 'class.tinymce.php');
			$tinyMCE = GeneralUtility::makeInstance('tinyMCE');
			$tinyMCE->loadConfiguration($userOrPageProperties['properties']['default.']['tinymceConfiguration']);

			$cssFiles = array();
			$less = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
			$files = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $userOrPageProperties['properties']['default.']['contentCSS']);
			foreach ($files as $pathAndFilename) {

				$result = preg_match('/^(.*\.less)(?:\?+.*(?:lessCompilerContext=([^&]*)))?.*$/', $pathAndFilename, $matches);

				// If not a LESS file, nothing else to do.
				if ($result === 0) {
					$cssFiles[] = $pathAndFilename;
					continue;
				}

				// Get compiler context if set.
				$context = isset($matches[2]) ? $matches[2] : NULL;
				$absolutePathAndFilename = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($matches[1]);
				$configuration = \AdGrafik\AdxLess\Utility\LessUtility::getConfiguration($thePidValue, $context);

				$cssFiles[] = $less->compile($absolutePathAndFilename, $configuration);
			}

			if (count($cssFiles)) {
				$tinyMCE->addConfigurationOption('content_css', implode(',', $cssFiles));
			}

			$tinyMCE->loadJsViaPageRenderer($pageRenderer, TRUE);

			$imageModule = BackendUtility::getModuleUrl('rtehtmlarea_wizard_select_image');
			$linkModule = BackendUtility::getModuleUrl('rtehtmlarea_wizard_element_browser');
			$pageRenderer->addJsInlineCode(
				'RTEbasic',
				'window.RTE = window.RTE || {};
				window.RTE.linkToImageModule = ' . GeneralUtility::quoteJSvalue($imageModule) . ';
				window.RTE.linkToLinkModule = ' . GeneralUtility::quoteJSvalue($linkModule) . ';'
			);
		}

		return parent::drawRTE($parentObject, $table, $field, $row, $PA, $specConf, $thisConfig, $RTEtypeVal, $RTErelPath, $thePidValue);
	}

}

?>