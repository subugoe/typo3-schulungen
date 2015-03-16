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

/**
 * Klasse zur Berechnung der noch zur Verfuegung stehenden freien Plaetze pro Termin
 */
class FreiePlaetzeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Berechnet die Anzahl der freien Plaetze
	 *
	 * @param int $maxPlaetze Die maximale Anzahl der Plaetze
	 * @param int $belegtePlaetze Bereits belegte Plaetze
	 * @return $freiePlaetze Anzahl der noch freien Plaetze
	 */
	public function render($maxPlaetze, $belegtePlaetze) {

		$belegtPlaetze = 0;

		if ($belegtPlaetze < $maxPlaetze) {

			$freiePlaetze = (intval($maxPlaetze) - intval($belegtePlaetze));
		}

		return $freiePlaetze;
	}

}
