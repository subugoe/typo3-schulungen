<?php
namespace Subugoe\Schulungen\Tests\Unit\ViewHelpers;

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
use Subugoe\Schulungen\ViewHelpers\SortViewHelper;
use TYPO3\CMS\Core\Tests\BaseTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Testing sort viewhelper
 */
class SortViewHelperTest extends BaseTestCase
{

    /**
     * @var SortViewHelper
     */
    protected $fixture;

    /**
     * @var ObjectStorage
     */
    protected $objectStorage;

    public function setUp()
    {
        $this->fixture = $this->getMock(SortViewHelper::class, ['dummy']);
        $this->objectStorage = $this->getMock(ObjectStorage::class, ['dummy']);
    }

    /**
     * @test
     */
    public function ObjectsAreSortedCorrectly()
    {
        $objects = $this->objectStorage;
        $objects->attach(new \stdClass());
        $orderBy = null;
        $order = null;
        $expected = null;

        $this->markTestIncomplete();
        $this->assertSame($expected, $this->fixture->render($objects, $orderBy, $order));

    }
}
