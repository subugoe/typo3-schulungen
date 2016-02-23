<?php
namespace Subugoe\Schulungen\Domain\Model;

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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;


/**
 * Schulungen der SUB Goettingen
 */
class Schulung extends AbstractEntity
{

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
     * Email Zusatz
     *
     * @var string $emailZusatz
     */
    protected $emailZusatz;

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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Subugoe\Schulungen\Domain\Model\Person> $contact
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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Subugoe\Schulungen\Domain\Model\Termin> $schulungTermine
     */
    protected $schulungTermine;

    /**
     * The constructor of this Schulung
     *
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties.
     */
    protected function initStorageObjects()
    {
        $this->schulungTermine = new ObjectStorage();
        $this->contact = new ObjectStorage();
    }

    /**
     * Getter for titel
     *
     * @return string Titel der Schulung
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * Setter for titel
     *
     * @param string $titel Titel der Schulung
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
    }

    /**
     * Getter for beschreibung
     *
     * @return string Beschreibung der Schulung
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Setter for beschreibung
     *
     * @param string $beschreibung Beschreibung der Schulung
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
    }

    /**
     * Getter for teilnehmerMin
     *
     * @return int Mindestteilnehmerzahl
     */
    public function getTeilnehmerMin()
    {
        return $this->teilnehmerMin;
    }

    /**
     * Setter for teilnehmerMin
     *
     * @param int $teilnehmerMin Mindestteilnehmerzahl
     */
    public function setTeilnehmerMin($teilnehmerMin)
    {
        $this->teilnehmerMin = $teilnehmerMin;
    }

    /**
     * Getter for teilnehmerMax
     *
     * @return int Maximale Teilnehmerzahl
     */
    public function getTeilnehmerMax()
    {
        return $this->teilnehmerMax;
    }

    /**
     * Setter for teilnehmerMax
     *
     * @param int $teilnehmerMax Maximale Teilnehmerzahl
     */
    public function setTeilnehmerMax($teilnehmerMax)
    {
        $this->teilnehmerMax = $teilnehmerMax;
    }

    /**
     * Getter for mailKopie
     *
     * @return string E-Mail Adresse einer Person
     */
    public function getMailKopie()
    {
        return $this->mailKopie;
    }

    /**
     * Setter for mailKopie
     *
     * @param string $mailKopie E-Mail Adresse einer Person
     */
    public function setMailKopie($mailKopie)
    {
        $this->mailKopie = $mailKopie;
    }

    /**
     * Adds a Termin
     *
     * @param \Subugoe\Schulungen\Domain\Model\Termin $schulungTermine
     */
    public function addSchulungTermine(\Subugoe\Schulungen\Domain\Model\Termin $schulungTermine)
    {
        $this->schulungTermine->attach($schulungTermine);
    }

    /**
     * Removes a Termin
     *
     * @param \Subugoe\Schulungen\Domain\Model\Termin $schulungTermineToRemove The Termin to be removed
     */
    public function removeSchulungTermine(\Subugoe\Schulungen\Domain\Model\Termin $schulungTermineToRemove)
    {
        $this->schulungTermine->detach($schulungTermineToRemove);
    }

    /**
     * Returns the schulungTermine
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Subugoe\Schulungen\Domain\Model\Termin> $schulungTermine
     */
    public function getSchulungTermine()
    {
        return $this->schulungTermine;
    }

    /**
     * Sets the schulungTermine
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Subugoe\Schulungen\Domain\Model\Termin> $schulungTermine
     */
    public function setSchulungTermine($schulungTermine)
    {
        $this->schulungTermine = $schulungTermine;
    }

    /**
     * Returns the $contact
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Subugoe\Schulungen\Domain\Model\Person> $contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Sets the schulungTermine
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Subugoe\Schulungen\Domain\Model\Person> $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Getter for voraussetzungen
     *
     * @return string voraussetzungen
     */
    public function getVoraussetzungen()
    {
        return $this->voraussetzungen;
    }

    /**
     * Setter for voraussetzungen
     *
     * @param string $voraussetzungen voraussetzungen
     */
    public function setVoraussetzungen($voraussetzungen)
    {
        $this->voraussetzungen = $voraussetzungen;
    }

    /**
     * Getter for treffpunkt
     *
     * @return string treffpunkt
     */
    public function getTreffpunkt()
    {
        return $this->treffpunkt;
    }

    /**
     * Setter for treffpunkt
     *
     * @param string $treffpunkt treffpunkt
     */
    public function setTreffpunkt($treffpunkt)
    {
        $this->treffpunkt = $treffpunkt;
    }

    /**
     * Getter for dauer
     *
     * @return string dauer
     */
    public function getDauer()
    {
        return $this->dauer;
    }

    /**
     * Setter for dauer
     *
     * @param string $dauer dauer
     */
    public function setDauer($dauer)
    {
        $this->dauer = $dauer;
    }

    /**
     * Getter for veranstalter
     *
     * @return string veranstalter
     */
    public function getVeranstalter()
    {
        return $this->veranstalter;
    }

    /**
     * Setter for veranstalter
     *
     * @param string $veranstalter veranstalter
     */
    public function setVeranstalter($veranstalter)
    {
        $this->veranstalter = $veranstalter;
    }

    /**
     * Getter for emailZusatz
     *
     * @return string emailZusatz
     */
    public function getEmailZusatz()
    {
        return $this->emailZusatz;
    }

    /**
     * Setter for emailZusatz
     *
     * @param string $emailZusatz emailZusatz
     */
    public function setEmailZusatz($emailZusatz)
    {
        $this->emailZusatz = $emailZusatz;
    }

    /**
     * Getter for untertitel
     *
     * @return string untertitel
     */
    public function getUntertitel()
    {
        return $this->untertitel;
    }

    /**
     * Setter for untertitel
     *
     * @param string $untertitel untertitel
     */
    public function setUntertitel($untertitel)
    {
        $this->untertitel = $untertitel;
    }

    /**
     * Getter for kategorie
     *
     * @return string kategorie
     */
    public function getKategorie()
    {
        return $this->kategorie;
    }

    /**
     * Setter for kategorie
     *
     * @param string $kategorie kategorie
     */
    public function setKategorie($kategorie)
    {
        $this->kategorie = $kategorie;
    }

    /**
     * Getter for termineVersteckt
     *
     * @return int termineVersteckt
     */
    public function getTermineVersteckt()
    {
        return $this->termineVersteckt;
    }

    /**
     * Setter for termineVersteckt
     *
     * @param string $termineVersteckt termineVersteckt
     */
    public function setTermineVersteckt($termineVersteckt)
    {
        $this->termineVersteckt = $termineVersteckt;
    }

    /**
     * Getter for anmeldungDeaktiviert
     *
     * @return int anmeldungDeaktiviert
     */
    public function getAnmeldungDeaktiviert()
    {
        return $this->anmeldungDeaktiviert;
    }

    /**
     * Setter for anmeldungDeaktiviert
     *
     * @param int $anmeldungDeaktiviert anmeldungDeaktiviert
     */
    public function setAnmeldungDeaktiviert($anmeldungDeaktiviert)
    {
        $this->anmeldungDeaktiviert = $anmeldungDeaktiviert;
    }

}
