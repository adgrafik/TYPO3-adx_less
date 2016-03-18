<?php
namespace AdGrafik\AdxLess\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use AdGrafik\AdxLess\Utility\LessUtility;

class T3libTceMainHook {

	/**
	 * @param array $parameters
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject
	 * @return void
	 */
	function clearCachePostProc($parameters, DataHandler $parentObject) {
		if (isset($parameters['cacheCmd']) === FALSE || $parameters['cacheCmd'] != 'system') {
			return;
		}
        if ($parentObject->admin || $parentObject->BE_USER->getTSConfigVal('options.clearCache.system')
            || ((boolean) $GLOBALS['TYPO3_CONF_VARS']['SYS']['clearCacheSystem'] === TRUE && $parentObject->admin)) {
			$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/tx_adxless/');
			GeneralUtility::rmdir($absoluteWritePath, TRUE);
        }
	}
}