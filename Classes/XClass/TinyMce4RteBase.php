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

use SGalinski\Tinymce\Loader;
use TYPO3\CMS\Backend\Form\FormEngine;
use TYPO3\CMS\Backend\Rte\AbstractRte;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class TinyMce4RteBase extends \SGalinski\Tinymce4Rte\Editors\RteBase {

	/**
	 * Draws the RTE as a form field or whatever is needed (inserts JavaApplet, creates iframe, renders ....)
	 * Default is to output the transformed content in a plain textarea field. This mode is great for debugging transformations!
	 *
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
	public function drawRTE(\TYPO3\CMS\Backend\Form\FormEngine $parentObject, $table, $field, $row, $PA, $specConf, $thisConfig, $RTEtypeVal, $RTErelPath, $thePidValue) {
		/** @var PageRenderer $pageRenderer */
		$pageRenderer = $GLOBALS['SOBE']->doc->getPageRenderer();

		// render the tinymce textarea
		$value = $this->transformContent(
			'rte', $PA['itemFormElValue'], $table, $field, $row, $specConf, $thisConfig, $RTErelPath, $thePidValue
		);

		// render RTE field
		$editorId = uniqid();
		$width = (GeneralUtility::_GP('M') === 'wizard_rte' ? '100%' : '650px');
		$code = $this->triggerField($PA['itemFormElName']);
		$code .= '<div style="width: ' . $width . '"><textarea id="editor' . $editorId . '" class="tinymce4_rte"
			name="' . htmlspecialchars($PA['itemFormElName']) . '"
			rows="20" cols="100">' . GeneralUtility::formatForTextarea($value) . '</textarea></div>';

		// add the tinymce code and it's configuration
		if (!self::$coreLoaded) {
			self::$coreLoaded = TRUE;
			$userOrPageProperties = BackendUtility::getModTSconfig($thePidValue, 'RTE');

			/** @var Loader $tinyMCE */
			$tinyMCE = GeneralUtility::makeInstance('\SGalinski\Tinymce\Loader');
			$tinyMCE->loadConfiguration($userOrPageProperties['properties']['default.']['tinymceConfiguration']);
			$contentCSS = array();
			if (isset($userOrPageProperties['properties']['default.']['contentCSS.'])) {
				$contentCSS = $userOrPageProperties['properties']['default.']['contentCSS.'];
			}
			if (isset($userOrPageProperties['properties']['default.']['contentCSS'])) {
				$contentCSS = array_merge($contentCSS, (array) GeneralUtility::trimExplode(',', $userOrPageProperties['properties']['default.']['contentCSS']));
			}

			$less = GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
			$cssFiles = array();
			foreach ($contentCSS as $key => $pathAndFilename) {

				// Skip on external resources.
				if (GeneralUtility::isValidUrl($pathAndFilename)) {
					$cssFiles[] = $pathAndFilename;
					continue;
				}

				$cssFile = GeneralUtility::getFileAbsFileName($pathAndFilename);

				$result = preg_match('/^(.*\.less)(?:\?+.*(?:lessCompilerContext=([^&]*)))?.*$/', $pathAndFilename, $matches);

				// If not a LESS file, nothing else to do.
				if ($result === 0) {
					if (is_file($cssFile)) {
						$cssFiles[] = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . PathUtility::stripPathSitePrefix($cssFile) . '?' . filemtime($cssFile);
					} else if (GeneralUtility::isValidUrl($cssFile)) {
						$cssFiles[] = $cssFile . '?' . filemtime($cssFile);
					}
					continue;
				}

				// Get compiler context if set.
				$context = isset($matches[2]) ? $matches[2] : NULL;
				$absolutePathAndFilename = GeneralUtility::getFileAbsFileName($matches[1]);
				$configuration = \AdGrafik\AdxLess\Utility\LessUtility::getConfiguration($thePidValue, $context);

				$cssFiles[] = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $less->compile($absolutePathAndFilename, $configuration) . '?' . filemtime($cssFile);
			}

			if (count($cssFiles)) {
				$tinyMCE->addConfigurationOption('content_css', implode(',', $cssFiles));
			}

			$tinyMCE->addConfigurationOption(
				'changeMethod', 'function() {
					var TBE_EDITOR = window.TBE_EDITOR || null;
					if (TBE_EDITOR && TBE_EDITOR.fieldChanged && typeof TBE_EDITOR.fieldChanged === \'function\') {
						TBE_EDITOR.fieldChanged();
					}
				}'
			);
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

		// calculate the dedicated RTE configuration
		$elementParts = preg_replace('/^(TSFE_EDIT\\[data\\]\\[|data\\[)/', '', $PA['itemFormElName']);
		$elementParts = preg_replace('/\\]$/', '', $elementParts);
		$elementParts = explode('][', $elementParts);
		list($typoscriptConfigurationPid, $pid) = BackendUtility::getTSCpid(
			trim($elementParts[0]), trim($elementParts[1]), $thePidValue
		);
		$rteConfiguration = rawurlencode(
			$this->getRteConfiguration($specConf, $RTEtypeVal, $pid, $typoscriptConfigurationPid, $elementParts)
		);

		// add RTE specific configuration data
		$languageId = max($row['sys_language_uid'], 0);
		$language = ($GLOBALS['LANG']->lang === '' ? 'default' : $GLOBALS['LANG']->lang);
		$pageRenderer->addJsInlineCode(
			'RTE' . $editorId,
			'window.RTE = window.RTE || {};
			window.RTE["editor' . $editorId . '"] = {};
			window.RTE["editor' . $editorId . '"].rteConfiguration = "' . $rteConfiguration . '";
			window.RTE["editor' . $editorId . '"].typo3ContentLanguage = "' . $language . '";
			window.RTE["editor' . $editorId . '"].sys_language_content = parseInt(' . $languageId . ');
		'
		);

		return $code;
	}

}

?>