<?php
namespace Subugoe\Schulungen\Utility;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Helper-Class for automatic flexform inclusion
 */
class HelperUtility
{

    /**
     * Call this function at the end of your ext_tables.php to autoregister the flexforms
     * of the extension to the given plugins.
     */
    public static function flexFormAutoLoader()
    {
        global $TCA, $_EXTKEY;
        $FlexFormPath = ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/FlexForms/';
        $extensionName = GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);

        $FlexForms = GeneralUtility::getFilesInDir($FlexFormPath, 'xml');
        foreach ($FlexForms as $FlexForm) {
            $fileKey = str_replace('.xml', '', $FlexForm);

            $pluginSignature = strtolower($extensionName . '_' . $fileKey);
            $TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,recursive';
            $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
            ExtensionManagementUtility::addPiFlexFormValue($pluginSignature,
                'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/' . $fileKey . '.xml');
        }
    }

}
