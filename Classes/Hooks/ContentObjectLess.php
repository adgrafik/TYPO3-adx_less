<?php

class Tx_AdxLess_Hooks_ContentObjectLess {

	/**
	 * stdWrapPreProcess
	 *
	 * @param string $contentObjectName
	 * @param array $configuration
	 * @param string $TypoScriptKey
	 * @param tslib_cObj $contentObject
	 * @return string
	 */
	public function cObjGetSingleExt($contentObjectName, array $configuration, $TypoScriptKey, tslib_cObj $contentObject) {
		$content = Tx_AdxLess_Utility_LessCompiler::compile($configuration['compile'], $configuration, $contentObject);
		return $content;
	}

}

?>