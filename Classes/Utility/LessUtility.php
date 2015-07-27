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
	public static function getConfiguration($contentObject, $key = NULL) {

		if ($key === NULL) {
			$key = 'lessphp';
		}

		if (self::$configuration === NULL) {

			if (is_object($contentObject)) {

				$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
				$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
				$configurationManager->setContentObject($contentObject);
				self::$configuration = $configurationManager->getConfiguration(
					\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
				);

				self::$configuration = isset(self::$configuration['plugin.']['tx_adxless.'])
					? self::$configuration['plugin.']['tx_adxless.']
					: array();

			} else {

				$pageSelect = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
				$rootLine = $pageSelect->getRootLine($contentObject);
				$tsParser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\ExtendedTemplateService');
				$tsParser->tt_track = 0;
				$tsParser->init();
				$tsParser->runThroughTemplates($rootLine);
				$tsParser->generateConfig();

				self::$configuration = isset($tsParser->setup['plugin.']['tx_adxless.'])
					? $tsParser->setup['plugin.']['tx_adxless.']
					: array();
			}
		}

		return $key ? self::$configuration[$key . '.'] : self::$configuration;
	}

	/**
	 * Add LESS file to the HTML
	 * 
	 * @param string $file
	 * @param array $configuration
	 * @return void
	 */
	public static function addLessFile($file, $configuration = array()) {

		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		$pageRenderer->addCssFile(
			$file,
			'stylesheet',
			$configuration['media'] ? $configuration['media'] : 'all',
			$configuration['title'] ? $configuration['title'] : '',
			$configuration['compress'] ? $configuration['compress'] : TRUE,
			$configuration['forceOnTop'] ? $configuration['forceOnTop'] : FALSE,
			$configuration['allWrap'] ? $configuration['allWrap'] : '',
			$excludeFromConcatenation = $configuration['excludeFromConcatenation'] ? $configuration['excludeFromConcatenation'] : FALSE
		);
	}

}

?>