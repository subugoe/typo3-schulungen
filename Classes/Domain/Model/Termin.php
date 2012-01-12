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
 * Termine
 *
 * @version $Id: Termin.php 1098 2011-08-17 14:03:22Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Domain_Model_Termin extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * schulung
	 *
	 * @var Tx_Schulungen_Domain_Model_Schulung $schulung
	 */
	protected $schulung;
	/**
	 * Anzahl der Teilnehmer
         * 
	 * @var int $anzahlTeilnehmer
	 */
	protected $anzahlTeilnehmer;
	/**
         * Array mit Teilnehmer-Objekten
	 *
	 * @var array $teilnehmer
	 */
	protected $teilnehmer;
	/**
	 * Startdatum und Zeit
	 *
	 * @var DateTime $startzeit
	 */
	protected $startzeit;
	/**
	 * Enddatum
	 *
	 * @var DateTime $ende
	 */
	protected $ende;
	/**
	 * Flag ob der Termin abgesagt wurde
	 *
	 * @var boolean $abgesagt
	 * @validate NotEmpty
	 */
	protected $abgesagt;
	/**
	 * Flag, ob Erinnerungen via Scheduler verschickt wurden
         * 
	 * @var boolean $erinnerungenverschickt
	 */
	protected $erinnerungenverschickt;


	public function getErinnerungenVerschickt() {
		return $this->erinnerungenverschickt;
	}

	public function setErinnerungenVerschickt($erinnerungenVerschickt) {
		$this->erinnerungenverschickt = $erinnerungenVerschickt;
	}

	/**
	 * @lazy
	 * @return array
	 */
	public function getTeilnehmer() {
		$teilnehmer = t3lib_div::makeInstance('Tx_Schulungen_Domain_Repository_TeilnehmerRepository');
		return $teilnehmer->findByTermin($this->getUid());
	}

	public function setTeilnehmer($teilnehmer) {
		$this->teilnehmer = $teilnehmer;
	}

	public function getAnzahlTeilnehmer() {

		$teilnehmer = t3lib_div::makeInstance('Tx_Schulungen_Domain_Repository_TeilnehmerRepository');

		return $teilnehmer->countByTermin($this->uid);
	}

	public function setAnzahlTeilnehmer($anzahlTeilnehmer) {
		$this->anzahlTeilnehmer = $anzahlTeilnehmer;
	}

	/**
	 * Returns the startzeit
	 *
	 * @return void
	 */
	public function getStartzeit() {
		return $this->startzeit;
	}

	/**
	 * Sets the startzeit
	 *
	 * @return void
	 * @param $startzeit
	 */
	public function setStartzeit($startzeit) {
		$this->startzeit = $startzeit;
	}

	/**
	 * Returns the ende
	 *
	 * @return void
	 */
	public function getEnde() {
		return $this->ende;
	}

	/**
	 * Sets the ende
	 *
	 * @return void
	 * @param $ende
	 */
	public function setEnde($ende) {
		$this->ende = $ende;
	}

	/**
	 * Returns the abgesagt
	 *
	 * @return void
	 */
	public function getAbgesagt() {
		return $this->abgesagt;
	}

	/**
	 * Sets the abgesagt
	 *
	 * @return void
	 * @param $abgesagt
	 */
	public function setAbgesagt($abgesagt) {
		$this->abgesagt = $abgesagt;
	}

	/**
	 * getSchulung
	 *
	 * @return void
	 */
	public function getSchulung() {
		return $this->schulung;
	}

	/**
	 * setSchulung
	 *
	 * @return void
	 * @param $schulung
	 */
	public function setSchulung($schulung) {
		$this->schulung = $schulung;
	}

	/**
	 * The constructor of this Termin
	 *
	 * @return void
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Returns the boolean state of abgesagt
	 *
	 * @return boolean
	 */
	public function isAbgesagt() {
		return $this->getAbgesagt();
	}

	/**
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		// empty
	}

}

?>