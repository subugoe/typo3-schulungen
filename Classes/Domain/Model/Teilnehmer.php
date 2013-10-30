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
 * Teilnehmer an Schulungen
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Domain_Model_Teilnehmer extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * Vorname des Teilnehmers
	 *
	 * @var string $vorname
	 * @validate NotEmpty
	 */
	protected $vorname ;
	/**
	 * Nachname des Teilnehmers
	 *
	 * @var string $nachname
	 * @validate NotEmpty
	 */
	protected $nachname;
	/**
	 * E-Mail Adresse des Teilnehmers
	 *
	 * @var string $email
     * @validate NotEmpty, EmailAddress
	 */
	protected $email;
	/**
	 * Studienfach des Teilnehmers
	 *
	 * @var string $studienfach
	 */
	protected $studienfach;
	/**
	 * Bemerkung des Teilnehmers
	 *
	 * @var string $bemerkung
	 */
	protected $bemerkung;
	/**
	 * Zusatz: Hidden-Field für Robots (Anti-Spam)
	 *
	 * @var string $zusatz
	 * @validate Tx_Schulungen_Domain_Validator_EmptyValidator
	 */
	protected $zusatz;
	/**
	 * Teilnehmer an einem Termin
	 *
	 * @var Tx_Schulungen_Domain_Model_Termin
	 */
	protected $termin;
	/**
	 * Captcha einer neuen Teilnehmers
	 *
	 * @var string $captcha
	 */
	protected $captcha;
	/**
	 * Secret for De-Registration eines Teilnehmers
	 *
	 * @var string $secret
	 */
	protected $secret;

	/**
	 * Getter for secret
	 *
	 * @return string $secret
	 */
	public function getSecret() {
		return $this->secret;
	}
	/**
	 * Setter for secret
	 *
	 * @param string $secret
	 * @return void
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
	}
	/**
	 * Setter for Zusatz
	 *
	 * @param string $Zusatz
	 * @return void
	 */
	public function setZusatz($zusatz) {
		$this->zusatz = $zusatz;
	}
	/**
	 * Getter for Zusatz
	 *
	 * @return string $zusatz
	 */
	public function getZusatz() {
		return $this->zusatz;
	}
	/**
	 * Setter for captcha
	 *
	 * @param string $captcha
	 * @return void
	 */
	public function setCaptcha($captcha) {
		$this->captcha = $captcha;
	}

	/**
	 * Getter for captcha
	 *
	 * @return string $captcha
	 */
	public function getCaptcha() {
		return $this->captcha;
	}

	/**
	 * Setter for vorname
	 *
	 * @param string $vorname Vorname des Teilnehmers
	 * @return void
	 */
	public function setVorname($vorname) {
		$this->vorname = $vorname;
	}

	/**
	 * Getter for vorname
	 *
	 * @return string Vorname des Teilnehmers
	 */
	public function getVorname() {
		return $this->vorname;
	}

	/**
	 * Setter for nachname
	 *
	 * @param string $nachname Nachname des Teilnehmers
	 * @return void
	 */
	public function setNachname($nachname) {
		$this->nachname = $nachname;
	}

	/**
	 * Getter for nachname
	 *
	 * @return string Nachname des Teilnehmers
	 */
	public function getNachname() {
		return $this->nachname;
	}

	/**
	 * Setter for email
	 *
	 * @param string $email E-Mail Adresse des Teilnehmers
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Getter for email
	 *
	 * @return string E-Mail Adresse des Teilnehmers
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Getter for studienfach
	 *
	 * @return string Studienfach des Teilnehmers
	 */
        public function getStudienfach() {
            return $this->studienfach;
        }

	/**
	 * Setter for studienfach
	 *
	 * @param string $studienfach Studienfach des Teilnehmers
	 * @return void
	 */
        public function setStudienfach($studienfach) {
            $this->studienfach = $studienfach;
        }

	/**
	 * Getter for bemerkung
	 *
	 * @return string Bemerkung des Teilnehmers
	 */
        public function getBemerkung() {
            return $this->bemerkung;
        }

	/**
	 * Setter for bemerkung
	 *
	 * @param string $bemerkung Bemerkung des Teilnehmers
	 * @return void
	 */
        public function setBemerkung($bemerkung) {
            $this->bemerkung = $bemerkung;
        }
        
	/**
	 * Returns the termin
	 *
	 * @return Tx_Schulungen_Domain_Model_Termin $termin
	 */
	public function getTermin() {
		return $this->termin;
	}

	/**
	 * Sets the termin
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $termin
	 * @return void
	 */
	public function setTermin($termin) {
		$this->termin = $termin;
	}

}

?>