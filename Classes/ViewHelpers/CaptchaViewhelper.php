<?php
namespace Subugoe\Schulungen\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
use TYPO3\CMS\Extensionmanager\Exception\MissingExtensionDependencyException;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ReCAPTCHA-ViewHelper
 */
class CaptchaViewHelper extends AbstractViewHelper
{

    /**
     * Createing a reCaptcha
     *
     * @return string $content ReCAPTCHA-Sourcecode
     */
    public function render()
    {
        $this->isExtensionLoaded();
        $captcha = new \tx_jmrecaptcha();
        return $captcha->getReCaptcha();
    }

    /**
     * @return bool
     * @throws MissingExtensionDependencyException
     */
    protected function isExtensionLoaded()
    {
        if (!ExtensionManagementUtility::isLoaded('jm_recaptcha')) {
            throw new MissingExtensionDependencyException('Extension jm_recaptcha is not loaded', 1432629406);
        }
        return true;
    }

}
