<?php
namespace AdGrafik\AdxLess;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

class Less implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var \Less_Parser $less
	 */
	protected $less;

	/**
	 * @var \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend $cache
	 */
	protected $cache;

	/**
	 * @return void
	 */
	public function __construct() {
		\Less_Autoloader::register();
		$this->less = new \Less_Parser();
		$this->cache = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('adx_less');
	}

	/**
	 * Compiles a LESS file or string.
	 *
	 * @param string $content		TYPO3 path and filename or a string.
	 * @param array $configuration
	 * @return string
	 */
	public function compile($content, array $configuration) {

		$returnUri = isset($configuration['returnUri']) ? $configuration['returnUri'] : TRUE;

		$cacheIdentifier = sha1($content);
		if ($cached = $this->getCachedContent($cacheIdentifier)) {
			if ($returnUri === 'absolute') {
				return $cached;
			} else if ($returnUri === 'siteURL') {
				return str_replace(PATH_site, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), $cached);
			} else if ($returnUri) {
				return str_replace(PATH_site, '', $cached);
			} else if ($returnUri == FALSE) {
				return file_get_contents($cached);
			}
		}

		$absoluteContentPathAndFilename = GeneralUtility::getFileAbsFileName($content);
		$contentIsFile = @is_file($absoluteContentPathAndFilename);

		// Default cache directory is "typo3temp/".
		$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/tx_adxless/');
		GeneralUtility::mkdir($absoluteWritePath);
		$absoluteCachePath = $absoluteWritePath . 'lesscache/';

		// Get the target filename. If set the file will be written in the cacheDirectory with this filename appended with hash and suffix ".less.css".
		// If is FALSE and the given $content is a file, the filename of $content will be used. If $content is not a file and targetFilename is not set, the filename will be "compliled.sha1.less.css".
		$targetFilename = isset($configuration['targetFilename'])
			? $configuration['targetFilename']
			: NULL;
		if ($targetFilename) {
			$targetFilename = $targetFilename . '.' . $cacheIdentifier . '.less.css';
		} else if ($contentIsFile) {
			$targetFilename = pathinfo($absoluteContentPathAndFilename, PATHINFO_FILENAME) . '.' . $cacheIdentifier . '.less.css';
		} else {
			$targetFilename = 'compliled.' . $cacheIdentifier . '.less.css';
		}

		// If "importDirectories" is not an array, split it to an array. Check arrays for PHP and TypoScript.
		$importDirectories = array();
		if (isset($configuration['importDirectories'])) {
			if (is_array($configuration['importDirectories'])) {
				$importDirectories = $configuration['importDirectories'];
			} else {
				$importDirectories = (array) GeneralUtility::trimExplode(',', $configuration['importDirectories']);
			}
		}
		if (isset($configuration['importDirectories.']) && is_array($configuration['importDirectories.'])) {
			$importDirectories = array_merge($importDirectories, $configuration['importDirectories.']);
		}
		foreach ($importDirectories as $path => $directory) {
			if (is_integer($path)) {
				$absolutePath = GeneralUtility::getFileAbsFileName($directory);
			} else {
				$absolutePath = GeneralUtility::getFileAbsFileName($path);
			}
			$directories[$absolutePath] = str_replace(PATH_site, '', $absolutePath);
		}
		$importDirectories = (array) $directories;

		$options = array(
			'compress' => (isset($configuration['compress']) ? (boolean) $configuration['compress'] : TRUE),
			'relativeUrls' => (isset($configuration['relativeUrls']) ? (boolean) $configuration['relativeUrls'] : TRUE),
			'strictUnits' => (isset($configuration['strictUnits']) ? (boolean) $configuration['strictUnits'] : FALSE),
			'strictMath' => (isset($configuration['strictMath']) ? (boolean) $configuration['strictMath'] : FALSE),
			'import_dirs' => $importDirectories,
			'cache_dir' => $absoluteCachePath,
		);

		// Using Reset instead of SetOptions because SetOptions will throw an exception if more then one LESS file is parsed and one of them needs an import.
		// For example adx_twitter_bootstrap loads "bootstrap.less" first and afterwards "datepicker3.less" which needs an import of "mixins/buttons.less" of bootstrap. This will fail without reset.
		$this->less->Reset($options);

		// At least parse LESS.
		if ($contentIsFile) {
			$siteUrl = dirname(str_replace(PATH_site, '', $absoluteContentPathAndFilename));
			$this->less->parseFile($absoluteContentPathAndFilename, $siteUrl);
		} else {
			$this->less->parse($content);
		}

		// Set variables. Check arrays for PHP and TypoScript.
		$variables = (isset($configuration['variables']) && is_array($configuration['variables']))
			? $configuration['variables']
			: (isset($configuration['variables.']) && is_array($configuration['variables.']))
				? $configuration['variables.']
				: NULL;
		if ($variables) {
			$this->less->modifyVars($variables);
		}

		$css = $this->less->getCss();

		// If $returnUri set to "absolute" the full path with filename will be returned, if TRUE then the relative path, else the CSS string will be returned.
		$absoluteWritePathAndFilename = $absoluteWritePath . $targetFilename;
		if ($returnUri === 'absolute') {
			$result = $absoluteWritePathAndFilename;
		} else if ($returnUri === 'siteURL') {
			$result = str_replace(PATH_site, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), $absoluteWritePathAndFilename);
		} else if ($returnUri) {
			$result = str_replace(PATH_site, '', $absoluteWritePathAndFilename);
		} else {
			$result = $css;
		}

		GeneralUtility::writeFile($absoluteWritePathAndFilename, $css);

		// Save cache depended on sha1 sum of parsed files.
		$parsedFiles = $this->less->AllParsedFiles();
		$cacheFiles = array();
		foreach ($parsedFiles as $parsedPathAndFilename) {
			$cacheFiles[$parsedPathAndFilename] = sha1_file($parsedPathAndFilename);
		}
		$this->cache->set('parsedFiles_' . $cacheIdentifier, $cacheFiles);
		$this->cache->set($cacheIdentifier, $absoluteWritePathAndFilename);

		return $result;
	}

	/**
	 * @deprecated
	 * @param string $content
	 * @param array $configuration
	 * @return string
	 */
	public function addLess($content, $configuration) {
		return \AdGrafik\AdxLess\Utility\LessUtility::includeCss();
	}

	/**
	 * @param string $cacheIdentifier
	 * @return string
	 */
	protected function getCachedContent($cacheIdentifier) {

		// Check cached files first and parse again if one hash not matching.
		$parsedFiles = $this->cache->get('parsedFiles_' . $cacheIdentifier) ?: array();
		foreach ($parsedFiles as $parsedFile => $hash) {
			if (sha1_file($parsedFile) !== $hash) {
				return FALSE;
			}
		}

		$cached = $this->cache->get($cacheIdentifier);

		return @is_file($cached) ? $cached : FALSE;
	}

}

?>