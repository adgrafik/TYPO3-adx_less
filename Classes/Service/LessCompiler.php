<?php

class Tx_AdxLess_Utility_LessCompiler {

	/**
	 * @param string $content
	 * @param array $configuration
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

			if (count((array) $configuration['importDirectories.'])) {

				$importDirectories = array();
				foreach ($configuration['importDirectories.'] as $key => $value) {

					$importDirectories[] = isset($configuration['importDirectories.'][$key . '.'])
						? $contentObject->stdWrap($value, $configuration['importDirectories.'][$key . '.'])
						: $value;
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

}

?>