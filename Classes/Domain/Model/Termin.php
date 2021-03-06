<?php
namespace Subugoe\Schulungen\Domain\Model;

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
use Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Termine
 */
class Termin extends AbstractEntity
{

    /**
     * schulung
     *
     * @var \Subugoe\Schulungen\Domain\Model\Schulung $schulung
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
     * @var \DateTime $startzeit
     */
    protected $startzeit;
    /**
     * Enddatum
     *
     * @var \DateTime $ende
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
     * Flag, whether reminder was already sent by scheduler
     *
     * @var boolean $erinnerungenVerschickt
     * @validate NotEmpty
     */
    protected $erinnerungenVerschickt;

    /**
     * getSchulung
     * @return Schulung
     * @throws \Exception
     */
    public function getSchulung()
    {
        if ($this->schulung) {
            return $this->schulung;
        } else {
            throw new \Exception('Termin has no Schulung.');
        }
    }

    /**
     * setSchulung
     *
     * @param $schulung
     */
    public function setSchulung($schulung)
    {
        $this->schulung = $schulung;
    }

    /**
     * Returns the number of Teilnehmer
     *
     * @return int
     */
    public function getAnzahlTeilnehmer()
    {
        $teilnehmer = GeneralUtility::makeInstance(TeilnehmerRepository::class);
        return $teilnehmer->countByTermin($this->uid);
    }

    /**
     * Sets the number of Teilnehmer
     *
     * @param int $anzahlTeilnehmer
     */
    public function setAnzahlTeilnehmer($anzahlTeilnehmer)
    {
        $this->anzahlTeilnehmer = $anzahlTeilnehmer;
    }

    /**
     * Returns the Teilnehmer ordered by Termin
     *
     * @lazy
     * @return array
     */
    public function getTeilnehmer()
    {
        /** @var TeilnehmerRepository $teilnehmer */
        $teilnehmer = GeneralUtility::makeInstance(TeilnehmerRepository::class);
        return $teilnehmer->findByTermin($this->getUid());
    }

    /**
     * Setter of Teilnehmer
     *
     * @param array $teilnehmer Teilnehmer
     */
    public function setTeilnehmer($teilnehmer)
    {
        $this->teilnehmer = $teilnehmer;
    }

    /**
     * Returns the startzeit
     *
     * @return \DateTime
     */
    public function getStartzeit()
    {
        return $this->startzeit;
    }

    /**
     * Sets the startzeit
     *
     * @param \DateTime $startzeit
     */
    public function setStartzeit($startzeit)
    {
        $this->startzeit = $startzeit;
    }

    /**
     * Returns the ende
     *
     * @return \DateTime
     */
    public function getEnde()
    {
        return $this->ende;
    }

    /**
     * Sets the ende
     *
     * @param \DateTime $ende
     */
    public function setEnde($ende)
    {
        $this->ende = $ende;
    }

    /**
     * Returns the state of abgesagt
     *
     * @return boolean
     */
    public function getAbgesagt()
    {
        return $this->abgesagt;
    }

    /**
     * Returns the boolean state of abgesagt
     *
     * @return boolean
     */
    public function isAbgesagt()
    {
        return $this->getAbgesagt();
    }

    /**
     * Sets the abgesagt
     *
     * @param boolean $abgesagt
     */
    public function setAbgesagt($abgesagt)
    {
        $this->abgesagt = $abgesagt;
    }

    /**
     * Returns the startzeit
     *
     * @return boolean
     */
    public function getErinnerungenVerschickt()
    {
        return $this->erinnerungenVerschickt;
    }

    /**
     * Returns the startzeit
     *
     * @param boolean $erinnerungenVerschickt
     */
    public function setErinnerungenVerschickt($erinnerungenVerschickt)
    {
        $this->erinnerungenVerschickt = $erinnerungenVerschickt;
    }

}
