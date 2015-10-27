<?php

namespace AdGrafik\AdxLess\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use AdGrafik\AdxLess\Utility\LessUtility;

class T3libTceMainHook {

	/**
	 * @param array $parameters
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject
	 * @return void
	 */
	function clearCachePostProc($parameters, \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject) {

		if (isset($parameters['cacheCmd']) === FALSE) {
			return;
		}

		if ($parameters['cacheCmd'] == 'all') {
			$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/tx_adxless/');
			GeneralUtility::rmdir($absoluteWritePath, TRUE);
		}
	}
}