<?php

class Tx_AdxLess_Hooks_Pagerenderer {

	/**
	 * stdWrapPreProcess
	 *
	 * @param array $configuration
	 * @param t3lib_PageRenderer $parentObject
	 * @return void
	 */
	public function preProcess($configuration, t3lib_PageRenderer $parentObject) {

		foreach ($configuration['cssFiles'] as $fileName => &$fileConfiguration) {

			if (strpos($fileName, '.adxless.css') === FALSE) {
				continue;
			}

			// get plugin settings
			$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
			$configurationManager = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManager');
			$configurationManager->setContentObject($GLOBALS['TSFE']->cObj);
			$settings = $configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
			);
			$settings = $settings['plugin.']['tx_adxless.'];

			// get source
			$sourceFilePathAndName = t3lib_div::getFileAbsFileName($fileName);
			$source = t3lib_div::getUrl($sourceFilePathAndName);
			$content = Tx_AdxLess_Utility_LessCompiler::compile($source, $settings);

			// write file
			$compiledFilePathAndName = TSpagegen::inline2TempFile($content, 'css');
			$fileConfiguration['file'] = $compiledFilePathAndName;
		}
	}

}

?>