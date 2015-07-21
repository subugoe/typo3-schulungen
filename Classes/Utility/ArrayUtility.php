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

/**
 * Helper-Class for array sorting
 */
class ArrayUtility
{

    /**
     * @param $array
     * @return bool
     */
    static public function sortByName($array)
    {
        return usort($array, 'ArrayUtility::usortByName');
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    static public function usortByName($a, $b)
    {
        return strnatcasecmp($a->getName(), $b->getName());
    }

}
