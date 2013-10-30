<?php

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

require_once(PATH_t3lib . 'class.t3lib_extmgm.php');
require_once(PATH_t3lib . 'class.t3lib_befunc.php');

/**
 * Toolbox for independet URI generation
 *
 * @abstract
 * @static
 * @package Schulungen
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class Tx_Schulungen_Utility_Uri {

	protected static $configuration = null;

	/**
	 * UriBuilder
	 * @var Tx_Extbase_MVC_Web_Routing_UriBuilder
	 */
	protected static $uriBuilder = null;

	/**
	 * Build an uriBuilder that can be used from any context (backend, frontend, TCA) to generate frontend URI
	 * @param string $extensionName
	 * @param string $pluginName
	 * @return Tx_Extbase_MVC_Web_Routing_UriBuilder
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
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		if (!(isset($GLOBALS['dispatcher']) && $GLOBALS['dispatcher'] instanceof Tx_Extbase_Core_Bootstrap)) {
			$extbaseBootstrap = $objectManager->get('Tx_Extbase_Core_Bootstrap');
			$extbaseBootstrap->initialize(array('extensionName' => $extensionName, 'pluginName' => $pluginName));
		}

		return $objectManager->get('Tx_Extbase_MVC_Web_Routing_UriBuilder');
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

		// Use latest classes available from TYPO3 4.6+
		if (class_exists('Tx_Extbase_Service_ExtensionService')) {
			$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
			$extensionService = $objectManager->get('Tx_Extbase_Service_ExtensionService');

			$pluginNamespace = $extensionService->getPluginNamespace($extensionName, $pluginName);
		} // Or fallback to retro compatibility with TYPO3 4.5
		else {
			$pluginNamespace = Tx_Extbase_Utility_Extension::getPluginNamespace($extensionName, $pluginName);
		}

		$arguments = array($pluginNamespace => $controllerArguments);

		self::$uriBuilder
				->reset()
				->setUseCacheHash(FALSE)
				->setCreateAbsoluteUri(TRUE)
				->setArguments($arguments);

		return self::$uriBuilder->buildFrontendUri() . '&type=1342671779';
	}

}

?>