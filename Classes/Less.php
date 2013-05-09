<?php

class Tx_AdxLess_Less implements t3lib_Singleton {

	/**
	 * @var array $extensionConfiguration
	 */
	protected $extensionConfiguration;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->extensionConfiguration = $this->getExtensionConfiguration();
	}

	/**
	 * @return void
	 */
	public function addClientCompilerLibrary() {

		if ($this->isClientSide() && $this->integrateClientCompiler()) {

			$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
			$pageRenderer->addJsLibrary(
				'tx_adxless_client_compiler',
				$this->getClientCompilerUrl(),
				'text/javascript',
				FALSE,
				TRUE,
				$this->getClientCompilerOptions(),
				FALSE
			);
		}
	}

	/**
	 * @param string $content Content input, ignore (just put blank string)
	 * @param array $configuration TypoScript configuration of the plugin!
	 * @return void
	 */
	public function addLess($content, $configuration) {

		$contentObject = $GLOBALS['TSFE']->cObj;

		// If the lib is not added to page yet, add it!
		$this->addClientCompilerLibrary();

		// Append LESS file
		if ($configuration['lessFile'] || $configuration['lessFile.']) {

			$file = t3lib_div::getFileAbsFileName($contentObject->stdWrap($configuration['lessFile'], $configuration['lessFile.']));

			if ($this->isClientSide()) {
				// Get file path
				$file = t3lib_div::getFileAbsFileName($contentObject->stdWrap($configuration['lessFile'], $configuration['lessFile.']));
				$file = str_replace(PATH_site, '', $file);
			} else {
				// Get content
				$file = $this->compileLessAndWriteTempFile($file, $contentObject);
			}

			$this->addLessFile($file, $configuration);
		}

		// Add LESS URL, only lesscss!
		if ($this->isClientSide() && ($configuration['lessUrl'] || $configuration['lessUrl.'])) {
			// Get file URL
			$file = $contentObject->stdWrap($configuration['lessUrl'], $configuration['lessUrl.']);
			$this->addLessFile($file, $configuration);
		}

		// Add LESS data
		if ($configuration['lessData'] || $configuration['lessData.']) {

			$content = $contentObject->stdWrap($configuration['lessData'], $configuration['lessData.']);

			if ($this->isServerSide()) {
				// Get content
				$content = $this->compileLess($content, $contentObject);
			}

			// Write temp file
			$file = class_exists('TYPO3\CMS\Frontend\Page\PageGenerator')
				? TYPO3\CMS\Frontend\Page\PageGenerator::inline2TempFile($content, 'css')
				: TSpagegen::inline2TempFile($content, 'css');

			$this->addLessFile($file, $configuration);
		}
	}

	/**
	 * @param string $content
	 * @param mixed $reference Page ID or content object tslib_cObj
	 * @return mixed
	 */
	public function compileLessAndWriteTempFile($content, $reference) {

		$content = $this->compileLess($content, $reference);
		// Write temp file
		$file = class_exists('TYPO3\CMS\Frontend\Page\PageGenerator')
			? TYPO3\CMS\Frontend\Page\PageGenerator::inline2TempFile($content, 'css')
			: TSpagegen::inline2TempFile($content, 'css');

		return $file;
	}

	/**
	 * @param string $content
	 * @param mixed $reference Page ID or content object tslib_cObj
	 * @return string
	 */
	public function compileLess($content, $reference) {

		if (@is_file($content)) {
			$content = t3lib_div::getUrl($content);
		}

		$configuration = $this->getConfiguration($reference, 'lessphp');
		$less = new lessc;

		if ($configuration['formatter']) {
			$less->setFormatter($configuration['formatter']);
		}

		if ($configuration['preserveComments']) {
			$less->setPreserveComments((boolean) $configuration['preserveComments']);
		}

		if (count((array) $configuration['variables.'])) {

			$variables = array();
			foreach ($configuration['variables.'] as $key => $value) {

				if (strpos($key, '.')) {
					$variables[substr($key, 0, -1)] = $reference->stdWrap($value, $configuration['variables.'][$key]);
				} else {
					$variables[$key] = $value;
				}
			}

			$less->setVariables($variables);
		}

		if ($configuration['importDirectories']) {

			$importDirectories = Tx_Extbase_Utility_Arrays::trimExplode(',', $configuration['importDirectories']);
			foreach ($importDirectories as &$importDirectory) {
				$importDirectory = t3lib_div::getFileAbsFileName($importDirectory);
			}

			$less->setImportDir($importDirectories);
		}

		$content = $less->compile($content);

		return $content;
	}

	/**
	 * Returns TRUE if the lib should be integrated
	 * 
	 * @return boolean
	 */
	public function integrateClientCompiler() {

		if (is_object($GLOBALS['TSFE'])) {

			if ($this->getDontIntegrateInRootline() && count($GLOBALS['TSFE']->rootLine) > 0) {
				foreach ($GLOBALS['TSFE']->rootLine as $page) {
					if (in_array($page['uid'], array_values(t3lib_div::trimExplode(',', $this->getDontIntegrateInRootline(), TRUE)))) {
						return FALSE;
					}
				}
			}

			return ( ! $this->getDontIntegrateOnUID() || ! in_array($GLOBALS['TSFE']->id, array_values(t3lib_div::trimExplode(',', $this->getDontIntegrateOnUID(), TRUE))));
		}

		return FALSE;
	}

	/**
	 * @return string
	 */
	public function getClientCompilerVersion() {
		return isset($this->extensionConfiguration['clientCompilerVersion'])
			? $this->extensionConfiguration['clientCompilerVersion']
			: '0.3.9';
	}

	/**
	 * @return string
	 */
	public function getDontIntegrateInRootline() {
		return isset($this->extensionConfiguration['dontIntegrateInRootline'])
			? $this->extensionConfiguration['dontIntegrateInRootline']
			: '';
	}

	/**
	 * @return string
	 */
	public function getDontIntegrateOnUID() {
		return isset($this->extensionConfiguration['dontIntegrateOnUID'])
			? $this->extensionConfiguration['dontIntegrateOnUID']
			: '';
	}

	/**
	 * @return boolean
	 */
	public function isAlwaysIntegrate() {
		return isset($this->extensionConfiguration['alwaysIntegrate'])
			? (boolean) $this->extensionConfiguration['alwaysIntegrate']
			: FALSE;
	}

	/**
	 * @return boolean
	 */
	public function isClientSide() {
		return isset($this->extensionConfiguration['compiler'])
			? ($this->extensionConfiguration['compiler'] == 'lesscss')
			: FALSE;
	}

	/**
	 * @return boolean
	 */
	public function isServerSide() {
		return isset($this->extensionConfiguration['compiler'])
			? ($this->extensionConfiguration['compiler'] == 'lessphp')
			: TRUE;
	}

	/**
	 * Get the configuration of adx_less
	 * 
	 * @return array
	 */
	protected function getExtensionConfiguration() {
		return (array) @unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['adx_less']);
	}

	/**
	 * @param mixed $reference Page ID or content object tslib_cObj
	 * @return array
	 */
	protected function getConfiguration($reference, $key = '') {

		if (is_object($reference)) {

			$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
			$configurationManager = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManager');
			$configurationManager->setContentObject($reference);
			$settings = $configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
			);

			$settings = isset($settings['plugin.']['tx_adxless.'])
				? $settings['plugin.']['tx_adxless.']
				: array();

		} else {

			$pageSelect = t3lib_div::makeInstance('t3lib_pageSelect');
			$rootLine = $pageSelect->getRootLine($reference);
			$tsParser = t3lib_div::makeInstance('t3lib_tsparser_ext');
			$tsParser->tt_track = 0;
			$tsParser->init();
			$tsParser->runThroughTemplates($rootLine);
			$tsParser->generateConfig();

			$settings = isset($tsParser->setup['plugin.']['tx_adxless.'])
				? $tsParser->setup['plugin.']['tx_adxless.']
				: array();
		}

		return $key ? $settings[$key . '.'] : $settings;
	}

	/**
	 * @return string
	 */
	protected function getClientCompilerOptions() {

		$contentObject = $GLOBALS['TSFE']->cObj;
		$allWrap = '';

		$compilerOptions = $this->getConfiguration($contentObject, 'lesscss');

		if ($compilerOptions) {

			$options = array();
			if ($compilerOptions['env'] || $compilerOptions['env.']) {
				$env = $contentObject->stdWrap($compilerOptions['env'], $compilerOptions['env.']);
				if ($env && $env != 'production') {
					$options[] = 'env: \'' . $env . '\'';
				}
			}
			if (isset($compilerOptions['async']) || isset($compilerOptions['async.'])) {
				$async = $contentObject->stdWrap($compilerOptions['async'], $compilerOptions['async.']);
				if ($async) {
					$options[] = 'async: true';
				}
			}
			if (isset($compilerOptions['fileAsync']) || isset($compilerOptions['fileAsync.'])) {
				$fileAsync = $contentObject->stdWrap($compilerOptions['fileAsync'], $compilerOptions['fileAsync.']);
				if ($fileAsync) {
					$options[] = 'fileAsync: true';
				}
			}
			if ($compilerOptions['poll'] || $compilerOptions['poll.']) {
				$poll = intval($contentObject->stdWrap($compilerOptions['poll'], $compilerOptions['poll.']));
				if ($poll && $poll != 1500) {
					$options[] = 'poll: ' . $poll;
				}
			}
			if ($compilerOptions['functions'] || $compilerOptions['functions.']) {
				$functions = $contentObject->stdWrap($compilerOptions['functions'], $compilerOptions['functions.']);
				if ($functions) {
					$options[] = 'functions: ' . $functions;
				}
			}
			if ($compilerOptions['dumpLineNumbers'] || $compilerOptions['dumpLineNumbers.']) {
				$dumpLineNumbers = $contentObject->stdWrap($compilerOptions['dumpLineNumbers'], $compilerOptions['dumpLineNumbers.']);
				if ($dumpLineNumbers && $dumpLineNumbers !== 'comments') {
					$options[] = 'dumpLineNumbers: ' . $dumpLineNumbers;
				}
			}
			if (isset($compilerOptions['relativeUrls']) || isset($compilerOptions['relativeUrls.'])) {
				$relativeUrls = $contentObject->stdWrap($compilerOptions['relativeUrls'], $compilerOptions['relativeUrls.']);
				if ($relativeUrls) {
					$options[] = 'relativeUrls: true';
				}
			}
			if ($compilerOptions['rootpath'] || $compilerOptions['rootpath.']) {
				$rootpath = $contentObject->stdWrap($compilerOptions['rootpath'], $compilerOptions['rootpath.']);
				if ($rootpath) {
					$options[] = 'rootpath: \'' . $rootpath . '\'';
				}
			}

			$allWrap = '<script type="text/javascript">' . PHP_EOL;
			$allWrap .= 'less = { ';
			$allWrap .= implode(', ', $options);
			$allWrap .= ' };' . PHP_EOL;
			$allWrap .= '</script>' . PHP_EOL . '|';
		}

		return $allWrap;
	}

	/**
	 * Add LESS file to the HTML
	 * 
	 * @param string $file
	 * @param array $configuration
	 * @return void
	 */
	protected function addLessFile($file, $configuration = array()) {

		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		$pageRenderer->addCssFile(
			$file,
			$this->isClientSide() ? 'stylesheet/less' : 'stylesheet',
			$configuration['media'] ? $configuration['media'] : 'all',
			$configuration['title'] ? $configuration['title'] : '',
			$configuration['compress'] ? $configuration['compress'] : TRUE,
			$configuration['forceOnTop'] ? $configuration['forceOnTop'] : FALSE,
			$configuration['allWrap'] ? $configuration['allWrap'] : '',
			$excludeFromConcatenation = $configuration['excludeFromConcatenation'] ? $configuration['excludeFromConcatenation'] : FALSE
		);
	}

	/**
	 * Get the script URL.
	 *
	 * @return mixed HTML Script tag to load the JavaScript library, on error FALSE
	 */
	protected function getClientCompilerUrl() {

		$url = t3lib_extMgm::extPath('adx_less') . 'Resources/Public/JavaScript/LESSCSS/less-' . $this->getClientCompilerVersion() . '.min.js';
		$url = str_replace(PATH_site, '', $url);

		if (!file_exists(PATH_site . $url)) {
			t3lib_div::devLog('\'' . $url . '\' does not exists!', 'adx_less', 3);
			return FALSE;
		}

		// Adding absRefPrefix here, makes sure that included correctly
		$url = $GLOBALS['TSFE']->absRefPrefix . $url;

		return $url;
	}

	/**
	 * Get the script tag.
	 *
	 * @param boolean $urlOnly If TRUE, only the URL is returned, not a full script tag
	 * @return mixed HTML Script tag to load the JavaScript library, on error FALSE
	 */
	protected function getClientCompilerTag() {
		return '<script type="text/javascript" src="' . $this->getClientCompilerUrl() . '"></script>';
	}

}

?>