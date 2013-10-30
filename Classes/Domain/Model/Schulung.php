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
	 * @var int $teilnehmerMin
	 */
	protected $teilnehmerMin;

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
	/**
	 * @var string $mailKopie
	 */
	protected $mailKopie;

	/**
	 * tt_address-Kontakt einer Person
	 *
	 * @var string $contact
	 */
	/**
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Schulungen_Domain_Model_Person> $contact
	 */
	protected $contact;

	/**
	 * Kategorie der Schulung
	 *
	 * @var string $kategorie
	 */
	protected $kategorie;

	/**
	 * Flag, ob Schulungstermine angezeigt werden sollen
	 *
	 * @var boolean $termineVersteckt
	 */
	protected $termineVersteckt;

	/**
	 * Flag, ob Anmeldung zu Schulungsterminen deaktiviert werden soll
	 *
	 * @var boolean $anmeldungDeaktiviert
	 */
	protected $anmeldungDeaktiviert;

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
	 * Setter for teilnehmerMin
	 *
	 * @param int $teilnehmerMin Mindestteilnehmerzahl
	 * @return void
	 */
	public function setTeilnehmerMin($teilnehmerMin) {
		$this->teilnehmerMin = $teilnehmerMin;
	}

	/**
	 * Getter for teilnehmerMin
	 *
	 * @return int Mindestteilnehmerzahl
	 */
	public function getTeilnehmerMin() {
		return $this->teilnehmerMin;
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
		$this->contact = new Tx_Extbase_Persistence_ObjectStorage();
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
	 * @param Tx_Extbase_Persistence_ObjectStorage <Tx_Schulungen_Domain_Model_Termin> $schulungTermine
	 * @return void
	 */
	public function setSchulungTermine($schulungTermine) {
		$this->schulungTermine = $schulungTermine;
	}

	/**
	 * Returns the $contact
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Schulungen_Domain_Model_Person> $contact
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * Sets the schulungTermine
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage <Tx_Schulungen_Domain_Model_Person> $contact
	 * @return void
	 */
	public function setContact($contact) {
		$this->contact = $contact;
	}

	/**
	 * Getter for voraussetzungen
	 *
	 * @return string voraussetzungen
	 */
	public function getVoraussetzungen() {
		return $this->voraussetzungen;
	}

	/**
	 * Setter for voraussetzungen
	 *
	 * @param string voraussetzungen
	 * @return void
	 */
	public function setVoraussetzungen($voraussetzungen) {
		$this->voraussetzungen = $voraussetzungen;
	}

	/**
	 * Getter for treffpunkt
	 *
	 * @return string treffpunkt
	 */
	public function getTreffpunkt() {
		return $this->treffpunkt;
	}

	/**
	 * Setter for treffpunkt
	 *
	 * @param string treffpunkt
	 * @return void
	 */
	public function setTreffpunkt($treffpunkt) {
		$this->treffpunkt = $treffpunkt;
	}

	/**
	 * Getter for dauer
	 *
	 * @return string dauer
	 */
	public function getDauer() {
		return $this->dauer;
	}

	/**
	 * Setter for dauer
	 *
	 * @param string dauer
	 * @return void
	 */
	public function setDauer($dauer) {
		$this->dauer = $dauer;
	}

	/**
	 * Getter for veranstalter
	 *
	 * @return string veranstalter
	 */
	public function getVeranstalter() {
		return $this->veranstalter;
	}

	/**
	 * Setter for veranstalter
	 *
	 * @param string veranstalter
	 * @return void
	 */
	public function setVeranstalter($veranstalter) {
		$this->veranstalter = $veranstalter;
	}

	/**
	 * Getter for untertitel
	 *
	 * @return string untertitel
	 */
	public function getUntertitel() {
		return $this->untertitel;
	}

	/**
	 * Setter for untertitel
	 *
	 * @param string untertitel
	 * @return void
	 */
	public function setUntertitel($untertitel) {
		$this->untertitel = $untertitel;
	}

	/**
	 * Getter for kategorie
	 *
	 * @return string kategorie
	 */
	public function getKategorie() {
		return $this->kategorie;
	}

	/**
	 * Setter for kategorie
	 *
	 * @param string kategorie
	 * @return void
	 */
	public function setKategorie($kategorie) {
		$this->kategorie = $kategorie;
	}

	/**
	 * Getter for termineVersteckt
	 *
	 * @return int termineVersteckt
	 */
	public function getTermineVersteckt() {
		return $this->termineVersteckt;
	}

	/**
	 * Setter for termineVersteckt
	 *
	 * @param string termineVersteckt
	 * @return void
	 */
	public function setTermineVersteckt($termineVersteckt) {
		$this->termineVersteckt = $termineVersteckt;
	}

	/**
	 * Getter for anmeldungDeaktiviert
	 *
	 * @return int anmeldungDeaktiviert
	 */
	public function getAnmeldungDeaktiviert() {
		return $this->anmeldungDeaktiviert;
	}

	/**
	 * Setter for anmeldungDeaktiviert
	 *
	 * @param int anmeldungDeaktiviert
	 * @return void
	 */
	public function setAnmeldungDeaktiviert($anmeldungDeaktiviert) {
		$this->anmeldungDeaktiviert = $anmeldungDeaktiviert;
	}


}

?>