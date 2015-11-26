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
use SGalinski\Tinymce4Rte\Form\Element\RichTextElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use AdGrafik\AdxLess\Utility\LessUtility;

class TinyMce4RichTextElementHook extends RichTextElement {

	/**
	 * @return array
	 */
	protected function enableTinyMce() {

		/** @var Loader $tinyMCE */
		$tinyMCE = GeneralUtility::makeInstance(Loader::class);
		$tinyMCE->loadConfiguration($this->vanillaRteTsConfig['properties']['default.']['tinymceConfiguration']);
		$less = GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');
		$configuration = LessUtility::getConfiguration($this->pidOfPageRecord);
		$configuration['returnUri'] = 'siteURL';

		// Get multiply sets of files.
		$cssFiles = isset($this->vanillaRteTsConfig['properties']['default.']['contentCSS.'])
			? $this->vanillaRteTsConfig['properties']['default.']['contentCSS.']
			: array();
		// Put single file on begining.
		if (isset($this->vanillaRteTsConfig['properties']['default.']['contentCSS']) && empty($this->vanillaRteTsConfig['properties']['default.']['contentCSS']) !== FALSE) {
			array_unshift($cssFiles, $this->vanillaRteTsConfig['properties']['default.']['contentCSS']);
		}
		foreach ($cssFiles as $key => $cssFile) {
			if (LessUtility::isValidFile($cssFile)) {
				$cssFiles[$key] = $less->compile($cssFile, $configuration);
			} else {
				$contentCssFile = GeneralUtility::getFileAbsFileName($cssFile);
				if (is_file($contentCssFile)) {
					$cssFiles[$key] = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . PathUtility::stripPathSitePrefix($contentCssFile) . '?' . filemtime($contentCssFile);
				}
			}
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

		/** @var PageRenderer $pageRenderer */
		$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
		$tinyMCE->loadJsViaPageRenderer($pageRenderer);
	}

}

?>