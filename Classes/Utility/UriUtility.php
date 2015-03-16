<?php
namespace Subugoe\Schulungen\Utility;

/* * *************************************************************
* Copyright notice
*
* (c) 2012
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
* ************************************************************* */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * Toolbox for independet URI generation
 *
 * @abstract
 * @static
 */
abstract class UriUtility {

	protected static $configuration = null;

	/**
	 * UriBuilder
	 * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
	 */
	protected static $uriBuilder = null;

	/**
	 * Build an uriBuilder that can be used from any context (backend, frontend, TCA) to generate frontend URI
	 * @param string $extensionName
	 * @param string $pluginName
	 * @return \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
	 */
	protected static function buildUriBuilder($extensionName, $pluginName) {

		// If we are in Backend we need to simulate minimal TSFE
		if (!isset($GLOBALS['TSFE']) || !($GLOBALS['TSFE'] instanceof tslib_fe)) {
			if (!is_object($GLOBALS['TT'])) {
				$GLOBALS['TT'] = new t3lib_timeTrack;
				$GLOBALS['TT']->start();
			}
			$TSFEclassName = @t3lib_div::makeInstance('tslib_fe');
			$GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], 0, '0', 1, '', '', '', '');
			$GLOBALS['TSFE']->initFEuser();
			$GLOBALS['TSFE']->fetch_the_id();
			$GLOBALS['TSFE']->getPageAndRootline();
			$GLOBALS['TSFE']->initTemplate();
			$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
			$GLOBALS['TSFE']->forceTemplateParsing = 1;
			$GLOBALS['TSFE']->getConfigArray();
		}

		// If extbase is not boostrapped yet, we must do it before building uriBuilder (when used from TCA)
		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof \TYPO3\CMS\Extbase\Core\Bootstrap)) {
			$extbaseBootstrap = $objectManager->get('TYPO3\\CMS\\Extbase\\Core\\Bootstrap');
			$extbaseBootstrap->initialize(array('extensionName' => $extensionName, 'pluginName' => $pluginName));
		}

		return $objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\Web\\Routing\UriBuilder');
	}


	/**
	 * Returns a frontend URI independently of current context, with or without extbase, and with or without TSFE
	 * @param string $actionName
	 * @param array $controllerArguments
	 * @param string $controllerName
	 * @param string $extensionName
	 * @param string $pluginName
	 * @return string absolute URI
	 */
	public static function buildFrontendUri($actionName, array $controllerArguments, $controllerName, $extensionName = 'Schulungen', $pluginName = 'Schulungen') {

		if (!self::$uriBuilder) {
			self::$uriBuilder = self::buildUriBuilder($extensionName, $pluginName);
		}
		$controllerArguments['action'] = $actionName;
		$controllerArguments['controller'] = $controllerName;

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		/** @var  \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService */
		$extensionService = $objectManager->get('TYPO3\\CMS\\Extbase\\Service\\ExtensionService');

		$pluginNamespace = $extensionService->getPluginNamespace($extensionName, $pluginName);

		$arguments = array($pluginNamespace => $controllerArguments);

		self::$uriBuilder
				->reset()
				->setUseCacheHash(FALSE)
				->setCreateAbsoluteUri(TRUE)
				->setArguments($arguments);

		return self::$uriBuilder->buildFrontendUri() . '&type=1342671779';
	}

}
