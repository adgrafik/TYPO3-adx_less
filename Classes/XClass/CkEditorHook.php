<?php
declare(strict_types=1);
namespace AdGrafik\AdxLess\XClass;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\RteCKEditor\Form\Element\RichTextElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use AdGrafik\AdxLess\Less;
use AdGrafik\AdxLess\Utility\LessUtility;

/**
 * Render rich text editor in FormEngine
 */
class CkEditorHook extends RichTextElement
{
    /**
     * Compiles the configuration set from the outside
     * to have it easily injected into the CKEditor.
     *
     * @return array the configuration
     */
    protected function prepareConfigurationForEditor(): array
    {
        $configuration = parent::prepareConfigurationForEditor($configuration);
		if (!is_array($configuration['contentsCss']))
		{
			$configuration['contentsCss'] = [$configuration['contentsCss']];
		}

		$less = GeneralUtility::makeInstance(Less::class);
		$cssFiles = array();
		foreach ($configuration['contentsCss'] as $key => $pathAndFilename)
		{
			// Skip on external resources.
			if (GeneralUtility::isValidUrl($pathAndFilename) || strpos($pathAndFilename, '.less') === false)
			{
				$cssFiles[] = $pathAndFilename;
				continue;
			}

			$cssFile = PathUtility::getCanonicalPath(PATH_site . $pathAndFilename);

			// If not a LESS file, nothing else to do.
			if (LessUtility::isValidFile($cssFile) === false)
			{
				if (is_file($cssFile))
				{
					$cssFiles[] = PathUtility::stripPathSitePrefix($cssFile) . '?' . filemtime($cssFile);
				}
				else if (GeneralUtility::isValidUrl($cssFile))
				{
					$cssFiles[] = $cssFile . '?' . filemtime($cssFile);
				}
				continue;
			}

			$lessConfiguration = LessUtility::getConfiguration($this->data['effectivePid']);
			$compiledPathAndFilename = '/' . $less->compile($cssFile, $lessConfiguration);

			$cssFiles[] = PathUtility::getAbsoluteWebPath($compiledPathAndFilename) . '?' . filemtime($cssFile);
		}
		$configuration['contentsCss'] = $cssFiles;

		return $configuration;
    }
}
