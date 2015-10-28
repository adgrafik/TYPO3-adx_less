<?php
namespace AdGrafik\AdxLess\Utility;

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

class LessUtility {

	/**
	 * @var array $extensionConfiguration
	 */
	protected static $extensionConfiguration;

	/**
	 * @var array $configuration
	 */
	protected static $configuration;

	/**
	 * Get the configuration of adx_less
	 * 
	 * @return array
	 */
	public static function getExtensionConfiguration() {
		if (self::$extensionConfiguration === NULL) {
			self::$extensionConfiguration = (array) @unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_less']);
		}
		return self::$extensionConfiguration;
	}

	/**
	 * @param mixed $contentObject Page ID or content object tslib_cObj
	 * @return array
	 */
	public static function getConfiguration($contentObject) {

		if (self::$configuration === NULL) {

			if (is_object($contentObject)) {

				$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
				$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
				$configurationManager->setContentObject($contentObject);
				self::$configuration = $configurationManager->getConfiguration(
					\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
				);

				self::$configuration = isset(self::$configuration['plugin.']['tx_adxless.'])
					? self::$configuration['plugin.']['tx_adxless.']
					: array();

			} else {

				$pageSelect = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
				$rootLine = $pageSelect->getRootLine($contentObject);
				$tsParser = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\ExtendedTemplateService');
				$tsParser->tt_track = 0;
				$tsParser->init();
				$tsParser->runThroughTemplates($rootLine);
				$tsParser->generateConfig();

				self::$configuration = isset($tsParser->setup['plugin.']['tx_adxless.'])
					? $tsParser->setup['plugin.']['tx_adxless.']
					: array();
			}
		}

		return self::$configuration;
	}

	/**
	 * Add LESS file to the HTML
	 * 
	 * @param string $content
	 * @param array $configuration
	 * @return string
	 */
	public static function includeCss($content, $configuration) {

		$less = GeneralUtility::makeInstance('AdGrafik\\AdxLess\\Less');

		$compilerSettings = isset($configuration['compilerSettings.']) ? $configuration['compilerSettings.'] : array();

		// returnUri can not be FALSE or "absolute".
		$compilerSettings['returnUri'] = isset($compilerSettings['returnUri']) ? $compilerSettings['returnUri'] : TRUE;
		$compilerSettings['returnUri'] = ($compilerSettings['returnUri'] === TRUE || $compilerSettings['returnUri'] === 'siteURL') ? $returnUri : TRUE;

		$includeCssSettings = isset($configuration['includeCssSettings.']) ? $configuration['includeCssSettings.'] : array();
		$includeCssSettings['media'] = isset($includeCssSettings['media']) ? $includeCssSettings['media'] : 'all';
		$includeCssSettings['title'] = isset($includeCssSettings['title']) ? $includeCssSettings['title'] : '';
		$includeCssSettings['compress'] = isset($includeCssSettings['compress']) ? (boolean) $includeCssSettings['compress'] : TRUE;
		$includeCssSettings['forceOnTop'] = isset($includeCssSettings['forceOnTop']) ? (boolean) $includeCssSettings['forceOnTop'] : FALSE;
		$includeCssSettings['allWrap'] = isset($includeCssSettings['allWrap']) ? $includeCssSettings['allWrap'] : '';
		$includeCssSettings['excludeFromConcatenation'] = isset($includeCssSettings['excludeFromConcatenation']) ? (boolean) $includeCssSettings['excludeFromConcatenation'] : FALSE;

		if (isset($configuration['file'])) {
			$compiledPathAndFilename = $less->compile($configuration['file'], $compilerSettings);
		}

		self::addCssFile($compiledPathAndFilename, $includeCssSettings);

		if (isset($configuration['data'])) {
			$compiledPathAndFilename = $less->compile($configuration['data'], $compilerSettings);
		}

		self::addCssFile($compiledPathAndFilename, $includeCssSettings);

		return $content;
	}

	/**
	 * @param string $pathAndFilename
	 * @param array $configuration
	 * @return void
	 */
	protected static function addCssFile($pathAndFilename, $includeCssSettings) {
		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		$pageRenderer->addCssFile(
			$pathAndFilename,
			'stylesheet',
			$includeCssSettings['media'],
			$includeCssSettings['title'],
			$includeCssSettings['compress'],
			$includeCssSettings['forceOnTop'],
			$includeCssSettings['allWrap'],
			$includeCssSettings['excludeFromConcatenation']
		);
	}

}

?>