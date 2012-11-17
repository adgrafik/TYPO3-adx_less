<?php

class Tx_AdxLess_Utility_LessCompiler {

	/**
	 * @param string $content
	 * @param array $configuration
	 * @param tslib_cObj $contentObject
	 * @return string
	 */
	public static function compile($content, array $configuration = NULL, tslib_cObj $contentObject = NULL) {

		$contentObject = $contentObject ? $contentObject : (isset($GLOBALS['TSFE']->cObj) ? $GLOBALS['TSFE']->cObj : NULL);
		$less = new lessc;

		if ($configuration) {

			if (isset($configuration['formatter'])) {
				$less->setFormatter($configuration['formatter']);
			}

			if (isset($configuration['preserveComments'])) {
				$less->setPreserveComments((boolean) $configuration['preserveComments']);
			}

			if (count((array) $configuration['variables.'])) {

				$variables = array();
				foreach ($configuration['variables.'] as $key => $value) {

					$variables[$key] = isset($configuration['variables.'][$key . '.'])
						? $contentObject->stdWrap($value, $configuration['variables.'][$key . '.'])
						: $value;
				}

				$less->setVariables($variables);
			}

			if (isset($configuration['importDirectories']) && $configuration['importDirectories']) {

				$importDirectories = array();
				$directories = Tx_Extbase_Utility_Arrays::trimExplode(',', $configuration['importDirectories']);

				foreach ($directories as $directory) {
					$importDirectories[] = t3lib_div::getFileAbsFileName($directory);
				}

				$less->setImportDir($importDirectories);
			}

			if (isset($configuration['compile.'])) {
				$content = $contentObject->cObjGetSingle($configuration['compile'], $configuration['compile.']);
			}
		}

		$content = $less->compile($content);

		return $content;
	}

	/**
	 * @param tslib_cObj $contentObject
	 * @return array
	 */
	public static function getTypoScriptByContentObject(tslib_cObj $contentObject = NULL) {

		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$configurationManager = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManager');
		$configurationManager->setContentObject($contentObject);
		$settings = $configurationManager->getConfiguration(
			Tx_Extbase_Configuration_ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);

		return isset($settings['plugin.']['tx_adxless.'])
			? $settings['plugin.']['tx_adxless.']
			: FALSE;
	}

	/**
	 * @param integer $pageUid
	 * @return array
	 */
	public static function getTypoScriptByPageUid($pageUid) {

		$pageSelect = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $pageSelect->getRootLine($pageUid);
		$tsParser = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$tsParser->tt_track = 0;
		$tsParser->init();
		$tsParser->runThroughTemplates($rootLine);
		$tsParser->generateConfig();

		return isset($tsParser->setup['plugin.']['tx_adxless.'])
			? $tsParser->setup['plugin.']['tx_adxless.']
			: FALSE;
	}

}

?>