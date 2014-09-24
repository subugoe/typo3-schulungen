<?php
namespace Subugoe\Schulungen\Domain\Repository;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>, Goettingen State Library
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
	 ***************************************************************/

/**
 * Repository for Teilnehmer
 */

class TeilnehmerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	// Sortierung absteigend nach Terminbeginn
	protected $defaultOrderings = array(
			'nachname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
	);

	public function teilnehmerAngemeldet(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer, \Subugoe\Schulungen\Domain\Model\Termin $termin) {
		$query = $this->createQuery();
		$query->matching(
				$query->logicalAnd(
						$query->equals('termin', $termin),
						$query->equals('email', $teilnehmer->getEmail()),
						$query->equals('vorname', $teilnehmer->getVorname()),
						$query->equals('nachname', $teilnehmer->getNachname())
				)
		);
		$query->setOrderings(array('nachname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
		return $query->execute();
	}
}
