<?php
namespace Subugoe\Schulungen\Service;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
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

use Subugoe\Schulungen\Controller\EmailController;
use Subugoe\Schulungen\Domain\Model\Termin;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Reminder an die Teilnehmer versenden
 */
class SendRemindersTask extends AbstractTask
{

    /**
     * @var EmailController
     */
    private $mail;

    /**
     * @var Termin
     */
    private $terminModel;

    /**
     * @var DatabaseConnection
     */
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Method executed from the Scheduler.
     * @return  boolean TRUE if success, otherwise FALSE
     */
    public function execute()
    {

        // Current running Scheduler
        /** @var SendRemindersTaskLogic $reminder */
        $reminder = GeneralUtility::makeInstance(SendRemindersTaskLogic::class);
        $reminder->execute($this);
        return true;

    }

    /**
     * Suche aller anstehenden Schulungen
     * @return boolean
     */
    public function getTermine()
    {

        $res = $this->db->exec_SELECTquery(
            '*', //WHAT
            'tx_schulungen_domain_model_termin', //FROM
            'WHERE erinnerungenverschickt = 0 AND abgesagt = 0 AND  TIMESTAMPDIFF(DAY,FROM_UNIXTIME(startzeit),NOW()) >=0 AND TIMESTAMPDIFF(DAY,FROM_UNIXTIME(startzeit),NOW()) <2'
        );

        while ($termin = $this->db->sql_fetch_assoc($res)) {
            $this->terminModel = GeneralUtility::makeInstance(Termin::class);
            $this->getTeilnehmer($termin['uid']);
        }

        return true;
    }

    /**
     * Auswahl der Teilnehmer pro Schulung
     * @param $schulungstermin
     */
    private function getTeilnehmer($schulungstermin)
    {
        $teilnehmerquery = $this->db->exec_SELECTquery(
            '*', //WHAT
            'tx_schulungen_domain_model_teilnehmer', //FROM
            'WHERE termin = ' . $schulungstermin, //WHERE
            '',
            '', //ORDER BY
            '' //LIMIT
        );
        while ($teilnehmer = $this->db->sql_fetch_assoc($teilnehmerquery)) {
            $this->sendeErinnerungsMail($teilnehmer['email']);
        }
    }

    /**
     * Senden der E-Mail
     * @param $teilnehmer
     * @return boolean
     */
    private function sendeErinnerungsMail($teilnehmer)
    {
        return $this->mail->sendeSchedulerMail(
            $teilnehmer,
            'info@sub.uni-goettingen.de',
            'Schulungserinnerung',
            'Erinnerung an Ihre Veranstaltung'
        );
    }
}
