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
		$this->cache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('adx_less');
	}

	/**
	 * @param string $cacheIdentifier
	 * @return string
	 */
	public function getCachedContent($cacheIdentifier) {
		// Check cached files first and parse again if one hash not matching.
		$parsedFiles = $this->cache->get('parsedFiles_' . $cacheIdentifier) ?: array();
		foreach ($parsedFiles as $parsedFile => $hash) {
			if (sha1_file($parsedFile) !== $hash) {
				return FALSE;
			}
		}

		$cache = FALSE;
		if (($cache = $this->cache->get('parsedData_' . $cacheIdentifier)) == FALSE) {
			if ($cache = $this->cache->get('parsedFile_' . $cacheIdentifier)) {
				$cacheFile = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($cache);
				$cache = @is_file($cacheFile) ? $cache : FALSE;
			}
		}

		return $cache;
	}

	/**
	 * Compiles a LESS file or string.
	 *
	 * @param string $content		Absolute file or string.
	 * 								If $content is a file, the parsed file will be saved in the given 'cacheDirectory' with the name format 'filename.sha1.css'.
	 * @param array $configuration
	 * @param mixed $saveAsFile
	 * @return string				Returns the parsed string if $saveAsFile is FALSE, else if it's TRUE this methode returns the new relative file name.
	 * 								If $saveAsFile is a string, the methode expected a file name and saves the parsed content there.
	 */
	public function compile($content, array $configuration, $saveAsFile = TRUE) {

		$cacheIdentifier = sha1($content);
		if ($cached = $this->getCachedContent($cacheIdentifier)) {
			return $cached;
		}

		// Default cache directory is "typo3temp/".
		$cacheDirectory = trim(($configuration['cacheDirectory'] ?: 'typo3temp'), '/') . '/';
		$configuration['cacheDirectory'] = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($cacheDirectory . 'lesscache/');

		// If "importDirectories" is not an array, split it to an array. Afterwards check for integer keys and convert them to oyejorge/lessphp format.
		$importDirectories = array();
		if (isset($configuration['importDirectories'])) {
			if (isset($configuration['importDirectories'])) {
				$importDirectories = (array) \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $configuration['importDirectories']);
			}
			if (isset($configuration['importDirectories.'])) {
				$importDirectories = array_replace($importDirectories, $configuration['importDirectories.']);
			}
			$directories = array();
			foreach ($importDirectories as $path => $directory) {
				if (is_integer($path)) {
					$absolutePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($directory);
				} else {
					$absolutePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path);
				}
				$directories[$absolutePath] = str_replace(PATH_site, '', $absolutePath);
			}
			$importDirectories = $directories;
		}

		$options = array(
			'compress' => (isset($configuration['compress']) ? (boolean) $configuration['compress'] : TRUE),
			'relativeUrls' => (isset($configuration['relativeUrls']) ? (boolean) $configuration['relativeUrls'] : TRUE),
			'strictUnits' => (isset($configuration['strictUnits']) ? (boolean) $configuration['strictUnits'] : FALSE),
			'strictMath' => (isset($configuration['strictMath']) ? (boolean) $configuration['strictMath'] : FALSE),
			'import_dirs' => $importDirectories,
			'cache_dir' => $configuration['cacheDirectory'],
		);

		$fileName = NULL;
		if ($saveAsFile === TRUE) {
			if (isset($configuration['targetFile']) && $configuration['targetFile']) {
				$fileName = $configuration['targetFile'];
			}
			else if (@is_file($content)) {
				$fileName = pathinfo($content, PATHINFO_FILENAME);
				$fileName = $cacheDirectory . $fileName . '.' . $cacheIdentifier . '.css';
			} else {
				$fileName = $cacheDirectory . 'compliled-less-file.' . $cacheIdentifier . '.css';
			}
		} else if (is_string($saveAsFile)) {
			$fileName = $cacheDirectory . $saveAsFile;
		}

		// Using Reset instead of SetOptions because SetOptions will throw an exception if more then one LESS file is parsed and one of them needs an import.
		// For example adx_twitter_bootstrap loads "bootstrap.less" first and afterwards "datepicker3.less" which needs an import of "mixins/buttons.less" of bootstrap. This will fail without reset.
		$this->less->Reset($options);

		// At least parse LESS.
		if (@is_file($content)) {
			$siteUrl = dirname(str_replace(PATH_site, '', $content));
			$this->less->parseFile($content, $siteUrl);
		} else {
			$this->less->parse($content);
		}

		if (isset($configuration['variables.'])) {
			$this->less->modifyVars($configuration['variables.']);
		}

		$content = $this->less->getCss();

		// Try to delete already cached files if exists.
		$cachedFileName = preg_replace('/\.[0-9a-z]{40}\.css/', '.*.css', $fileName);
		$cachedFiles = glob($cachedFileName);
		foreach ($cachedFiles as $cachedFile) {
			unlink($cachedFile);
		}

		// Save cache depended on sha1 sum of parsed files.
		$parsedFiles = $this->less->AllParsedFiles();
		$cacheFiles = array();
		foreach ($parsedFiles as $parsedFile) {
			$cacheFiles[$parsedFile] = sha1_file($parsedFile);
		}
		$this->cache->set('parsedFiles_' . $cacheIdentifier, $cacheFiles);
		$dataCacheKey = $fileName ? 'parsedFile_' : 'parsedData_';
		$this->cache->set($dataCacheKey . $cacheIdentifier, $fileName ?: $content);

		if ($fileName === NULL) {
			return $content;
		} else {
			// Write temporary file.
			$temporaryFile = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($fileName);
			\TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($temporaryFile, $content);
			return $fileName;
		}
	}

	/**
	 * @param string $content Content input, ignore (just put blank string)
	 * @param array $configuration TypoScript configuration of the plugin!
	 * @return void
	 */
	public function addLess($content, $configuration) {

		$contentObject = $GLOBALS['TSFE']->cObj;

		$pathAndFilename = $this->thisConfig['contentCSS'];
		$result = preg_match('/^(.*\.less)(?:\?+.*(?:lessCompilerContext=([^&]*)))?.*$/i', $pathAndFilename, $matches);

		// If not a LESS file, nothing else to do.
		if ($result === 0) {
			return parent::getContentCssFileName();
		}

		// Get compiler context if set.
		$context = isset($matches[2]) ? $matches[2] : NULL;
		$absolutePathAndFilename = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($matches[1]);
		$configuration = \AdGrafik\AdxLess\Utility\LessUtility::getConfiguration($this->currentPage, $context);

		// Append LESS file.
		$lessFile = isset($configuration['less.']['file']) ? $configuration['less.']['file'] : '';
		if (isset($configuration['less.']['file.'])) {
			$lessFile = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($contentObject->stdWrap($lessFile, $configuration['less.']['file.']));
		}
		if ($lessFile) {
			$fileName = $this->compile($lessFile, $configuration['less.']['configuration.']);
			\AdGrafik\AdxLess\Utility\LessUtility::addLessFile($fileName, $configuration);
		}

		// Add LESS data.
		$lessData = isset($configuration['less.']['data']) ? $configuration['less.']['data'] : '';
		if (isset($configuration['less.']['data.'])) {
			$lessData = $contentObject->stdWrap($lessData, $configuration['less.']['data.']);
		}
		if ($lessData) {
			$fileName = $this->compile($lessData, $configuration['less.']['configuration.']);
			\AdGrafik\AdxLess\Utility\LessUtility::addLessFile($fileName, $configuration);
		}
	}

}

?>