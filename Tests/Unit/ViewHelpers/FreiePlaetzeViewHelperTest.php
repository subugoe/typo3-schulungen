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
use Subugoe\Schulungen\ViewHelpers\FreiePlaetzeViewHelper;
use TYPO3\CMS\Core\Tests\BaseTestCase;

/**
 * Testing spare seats viewhelper
 */
class FreiePlaetzeViewHelperTest extends BaseTestCase
{

    /**
     * @var FreiePlaetzeViewHelper
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = $this->getMock(FreiePlaetzeViewHelper::class, ['dummy']);
    }

    /**
     * @return array
     */
    public function spareParticipantsProvider()
    {
        return [
            [20, 5, 15],
            [5, 20, 0],
            [0, 0, 0]
        ];
    }

    /**
     * @dataProvider spareParticipantsProvider
     *
     * @test
     * @param $maxPlaetze
     * @param $belegtePlaetze
     * @param $expected
     */
    public function numberOfLeftPlacesIsCorrectlyCalculated($maxPlaetze, $belegtePlaetze, $expected)
    {
        $this->assertSame($expected, $this->fixture->render($maxPlaetze, $belegtePlaetze));
    }
}
