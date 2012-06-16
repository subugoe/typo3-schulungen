<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Dominic Simm <dominic.simm@sub.uni-goettingen.de>, Goettingen State Library
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
 * ************************************************************* */

/**
 * Helper-Class for automatical flexform inclusion
 *
 * @version $Id: Helper.php 1590 2012-01-13 17:38:19Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Utility_HelperUtility {
    
	/**
     * Call this function at the end of your ext_tables.php to autoregister the flexforms
     * of the extension to the given plugins.
     */
    public static function flexFormAutoLoader() {
        global $TCA, $_EXTKEY;
        $FlexFormPath = t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/FlexForms/';
        $extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
 
        $FlexForms = t3lib_div::getFilesInDir($FlexFormPath, 'xml');
        foreach ($FlexForms as $FlexForm) {
            $fileKey = str_replace('.xml', '', $FlexForm);
 
            $pluginSignature = strtolower($extensionName . '_' . $fileKey);
            $TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,recursive';
            $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
            t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/' . $fileKey . '.xml');
        }
    }
 
}

?>