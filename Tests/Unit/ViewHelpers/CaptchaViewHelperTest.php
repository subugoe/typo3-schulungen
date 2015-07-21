<?php
namespace Subugoe\Schulungen\Texts\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>
 *      Goettingen State Library
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

use Subugoe\Schulungen\ViewHelpers\CaptchaViewHelper;
use TYPO3\CMS\Core\Tests\BaseTestCase;

class CaptchaViewHelperTest extends BaseTestCase
{

    /**
     * @var CaptchaViewHelper
     */
    protected $fixture;

    /**
     * @test
     */
    public function extensionIsNotLoaded()
    {
        $this->fixture
            ->expects($this->at(0))
            ->method('isExtensionLoaded')
            ->will($this->returnValue(false));

        $this->setExpectedException(\TYPO3\CMS\Extensionmanager\Exception\MissingExtensionDependencyException::class);
        $this->markTestIncomplete();
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = $this->getMock(CaptchaViewHelper::class, ['dummy']);
    }

}