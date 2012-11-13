<?php

class Tx_AdxLess_Hooks_StdWrap implements tslib_content_stdWrapHook {

	/**
	 * @var array
	 */
	protected $frontEndResourceTypes = array(
		'headerData.',
		'footerData.',
		'includeCSS.',
		'cssInline.',
		'includeJSlibs.',
		'includeJSFooterlibs.',
		'includeJS.',
		'includeJSFooter.',
		'jsInline.',
		'jsFooterInline.',
	);

	/**
	 * @var array
	 */
	protected $frontEndResourceCArrayTypes = array(
		'headerData.',
		'footerData.',
		'cssInline.',
		'jsInline.',
		'jsFooterInline.',
	);

	/**
	 * stdWrapPreProcess
	 *
	 * @param string $content
	 * @param array $configuration
	 * @param tslib_cObj $contentObject
	 * @return string
	 */
	function stdWrapPreProcess($content, array $configuration, tslib_cObj &$contentObject) {
		return $content;
	}

	/**
	 * stdWrapOverride
	 *
	 * @param string $content
	 * @param array $configuration
	 * @param tslib_cObj $contentObject
	 * @return string
	 */
	function stdWrapOverride($content, array $configuration, tslib_cObj &$contentObject) {
		return $content;
	}

	/**
	 * stdWrapProcess
	 *
	 * @param string $content
	 * @param array $configuration
	 * @param tslib_cObj $contentObject
	 * @return string
	 */
	function stdWrapProcess($content, array $configuration, tslib_cObj &$contentObject) {
		return $content;
	}

	/**
	 * stdWrapPostProcess
	 *
	 * @param string $content
	 * @param array $configuration
	 * @param tslib_cObj $contentObject
	 * @return string
	 */
	function stdWrapPostProcess($content, array $configuration, tslib_cObj &$contentObject) {

		if (isset($configuration['includeFrontEndResources.'])) {
			foreach ($configuration['includeFrontEndResources.'] as $type => $contentObjectConfiguration) {
				// Check if valid.
				if (in_array($type, $this->frontEndResourceTypes)) {
					// Create array if it's not.
					if (!is_array($GLOBALS['TSFE']->pSetup[$type])) {
						$GLOBALS['TSFE']->pSetup[$type] = array();
					}

					// If is inline type, parse it and create a HTML cObject.
					if (in_array($type, $this->frontEndResourceCArrayTypes)) {
						foreach ($contentObjectConfiguration as $key => $configuration) {
							// Render only if forceParse is set.
							if (is_array($configuration) && isset($configuration['forceParse'])) {
								unset($configuration['forceParse']);
								// override cObject configuration
								$contentObjectConfiguration = array(
									substr($key, 0, -1) => 'TEXT',
									$key => array(
										'value' => $contentObject->cObjGet($contentObjectConfiguration, $key),
									),
								);
							}
						}
					}

					$GLOBALS['TSFE']->pSetup[$type] = Tx_Extbase_Utility_Arrays::arrayMergeRecursiveOverrule($GLOBALS['TSFE']->pSetup[$type], $contentObjectConfiguration);
				}
			}
		}

		return $content;
	}

}

?>