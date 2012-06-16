<?php

/* * *************************************************************
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
 * ************************************************************* */

/**
 * Repository for Tx_Schulungen_Domain_Model_Termin
 *
 * @version $Id: TerminRepository.php 1883 2012-05-25 08:23:09Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Domain_Repository_TerminRepository extends Tx_Extbase_Persistence_Repository {

	protected $defaultPid = 1648;

	// Sortierung absteigend nach Terminbeginn
	protected $defaultOrderings = array(
			'startzeit' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING
	);

/*	public function initializeObject() {
		$querySettings = $this->objectManager->create('Tx_Extbase_Persistence_Typo3QuerySettings');
		$querySettings->setRespectStoragePage(TRUE);
		$this->setDefaultQuerySettings($querySettings);
	}
*/
	public function errechneAnstehendeTermine() {
		$query = $this->createQuery();
//		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		
		# Erinnerung, wenn Termin weniger als 2 Tage entfernt 
//		$query->statement('SELECT * FROM tx_schulungen_domain_model_termin WHERE pid = ' . $this->defaultPid . ' AND erinnerungen_verschickt = 0 AND TIMESTAMPDIFF(DAY,FROM_UNIXTIME(startzeit),NOW()) >= 0 AND TIMESTAMPDIFF(DAY,FROM_UNIXTIME(startzeit),NOW()) < 2 ORDER BY startzeit ASC');

		$query->matching(
			$query->logicalAnd( 
//				$query->equals('pid', $this->defaultPid),
				$query->equals('erinnerungen_verschickt', 0),
				$query->greaterThan('startzeit', time()+(60*60*24)),
				$query->lessThan('startzeit', time()+(2*60*60*24))    
			)
		);
		$query->setOrderings(array('startzeit' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
		return $query->execute();
	}

	public function errechneAnstehendeSchulungTermine(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd( 
				$query->equals('schulung', $schulung),
				$query->greaterThan('startzeit', time())
			)
		);
		$query->setOrderings(array('startzeit' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
		return $query->execute();
	}

}

?>