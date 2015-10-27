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

		if (isset($params['cacheCmd']) === FALSE) {
			return;
		}

		if ($params['cacheCmd'] == 'all') {
			$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/adx_less/');
			GeneralUtility::rmdir($absoluteWritePath, TRUE);
		}
	}
}