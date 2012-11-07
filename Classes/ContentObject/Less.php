<?php

include_once(t3lib_extMgm::extPath('adx_less') . 'Classes/LESSPHP/lessc.inc.php');

class Tx_AdxLess_ContentObject_Less implements tslib_content_cObjGetSingleHook {

	/**
	 * stdWrapPreProcess
	 *
	 * @param string $contentObjectName
	 * @param array $configuration
	 * @param string $TypoScriptKey
	 * @param tslib_cObj $parentObject
	 * @return string
	 */
	public function getSingleContentObject($contentObjectName, array $configuration, $TypoScriptKey, tslib_cObj &$parentObject) {

		$less = new lessc;

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
					? $parentObject->stdWrap($value, $configuration['variables.'][$key . '.'])
					: $value;
			}

			$less->setVariables($variables);
		}

		if (count((array) $configuration['importDirectories.'])) {

			$importDirectories = array();
			foreach ($configuration['importDirectories.'] as $key => $value) {

				$importDirectories[] = isset($configuration['importDirectories.'][$key . '.'])
					? $parentObject->stdWrap($value, $configuration['importDirectories.'][$key . '.'])
					: $value;
			}

			$less->setImportDir($importDirectories);
		}

		$content = $parentObject->cObjGetSingle($configuration['compile'], $configuration['compile.'], $TypoScriptKey);
		$content = $less->compile($content);

		return $content;
	}

}

?>