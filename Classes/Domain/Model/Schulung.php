<?php
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
 * Schulungen der SUB Goettingen
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

 class Tx_Schulungen_Domain_Model_Schulung extends Tx_Extbase_DomainObject_AbstractEntity {

     /**
	 * Titel der Schulung
	 *
	 * @var string $titel
	 * @validate NotEmpty
	 */
	protected $titel;

	/**
	 * Beschreibung der Schulung
	 *
	 * @var string $beschreibung
	 * @validate NotEmpty
	 */
	protected $beschreibung;

	/**
	 * Mindestteilnehmerzahl
	 *
	 * @var int $teilnehmernMin
	 */
	protected $teilnehmernMin;

	/**
	 * Maximale Teilnehmerzahl
	 *
	 * @var int $teilnehmerMax
	 */
	protected $teilnehmerMax;

	/**
	 * Voraussetzungen für die Schulung
	 *
	 * @var string $voraussetzungen
	 */
	protected $voraussetzungen;

	/**
	 * Treffpunkt
	 *
	 * @var string $treffpunkt
	 */
	protected $treffpunkt;

	/**
	 * Dauer der Schulung
	 *
	 * @var string $dauer
	 */
	protected $dauer;

	/**
	 * Veranstalter der Schulung
	 *
	 * @var int $veranstalter
	 */
	protected $veranstalter;

	/**
	 * Untertitel
	 *
	 * @var string $untertitel
	 */
	protected $untertitel;

	/**
	 * E-Mail Adresse einer Person
	 *
	 * @var string $mailKopie
	 */
	protected $mailKopie;

	/**
	 * Termine der Schulung
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Schulungen_Domain_Model_Termin> $schulungTermine
	 */
	protected $schulungTermine;

	/**
	 * Setter for titel
	 *
	 * @param string $titel Titel der Schulung
	 * @return void
	 */
	public function setTitel($titel) {
		$this->titel = $titel;
	}

	/**
	 * Getter for titel
	 *
	 * @return string Titel der Schulung
	 */
	public function getTitel() {
		return $this->titel;
	}

	/**
	 * Setter for beschreibung
	 *
	 * @param string $beschreibung Beschreibung der Schulung
	 * @return void
	 */
	public function setBeschreibung($beschreibung) {
		$this->beschreibung = $beschreibung;
	}

	/**
	 * Getter for beschreibung
	 *
	 * @return string Beschreibung der Schulung
	 */
	public function getBeschreibung() {
		return $this->beschreibung;
	}

	/**
	 * Setter for teilnehmernMin
	 *
	 * @param int $teilnehmernMin Mindestteilnehmerzahl
	 * @return void
	 */
	public function setTeilnehmernMin($teilnehmernMin) {
		$this->teilnehmernMin = $teilnehmernMin;
	}

	/**
	 * Getter for teilnehmernMin
	 *
	 * @return int Mindestteilnehmerzahl
	 */
	public function getTeilnehmernMin() {
		return $this->teilnehmernMin;
	}

	/**
	 * Setter for teilnehmerMax
	 *
	 * @param int $teilnehmerMax Maximale Teilnehmerzahl
	 * @return void
	 */
	public function setTeilnehmerMax($teilnehmerMax) {
		$this->teilnehmerMax = $teilnehmerMax;
	}

	/**
	 * Getter for teilnehmerMax
	 *
	 * @return int Maximale Teilnehmerzahl
	 */
	public function getTeilnehmerMax() {
		return $this->teilnehmerMax;
	}

	/**
	 * Setter for mailKopie
	 *
	 * @param string $mailKopie E-Mail Adresse einer Person
	 * @return void
	 */
	public function setMailKopie($mailKopie) {
		$this->mailKopie = $mailKopie;
	}

	/**
	 * Getter for mailKopie
	 *
	 * @return string E-Mail Adresse einer Person
	 */
	public function getMailKopie() {
		return $this->mailKopie;
	}

	/**
	 * The constructor of this Schulung
	 *
	 * @return void
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		* Do not modify this method!
		* It will be rewritten on each save in the kickstarter
		* You may modify the constructor of this class instead
		*/
		$this->schulungTermine = new Tx_Extbase_Persistence_ObjectStorage();
	}

	/**
	 * Adds a Termin
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $schulungTermine
	 * @return void
	 */
	public function addSchulungTermine(Tx_Schulungen_Domain_Model_Termin $schulungTermine) {
		$this->schulungTermine->attach($schulungTermine);
	}

	/**
	 * Removes a Termin
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $schulungTermineToRemove The Termin to be removed
	 * @return void
	 */
	public function removeSchulungTermine(Tx_Schulungen_Domain_Model_Termin $schulungTermineToRemove) {
		$this->schulungTermine->detach($schulungTermineToRemove);
	}

	/**
	 * Returns the schulungTermine
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Schulungen_Domain_Model_Termin> $schulungTermine
	 */
	public function getSchulungTermine() {
		return $this->schulungTermine;
	}

	/**
	 * Sets the schulungTermine
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Schulungen_Domain_Model_Termin> $schulungTermine
	 * @return void
	 */
	public function setSchulungTermine($schulungTermine) {
		$this->schulungTermine = $schulungTermine;
	}

	public function getVoraussetzungen() {
		return $this->voraussetzungen;
	}

	public function setVoraussetzungen($voraussetzungen) {
		$this->voraussetzungen = $voraussetzungen;
	}

	public function getTreffpunkt() {
		return $this->treffpunkt;
	}

	public function setTreffpunkt($treffpunkt) {
		$this->treffpunkt = $treffpunkt;
	}

	public function getDauer() {
		return $this->dauer;
	}

	public function setDauer($dauer) {
		$this->dauer = $dauer;
	}

	public function getVeranstalter() {
		return $this->veranstalter;
	}

	public function setVeranstalter($veranstalter) {
		$this->veranstalter = $veranstalter;
	}

	public function getUntertitel() {
		return $this->untertitel;
	}

	public function setUntertitel($untertitel) {
		$this->untertitel = $untertitel;
	}
}
?>