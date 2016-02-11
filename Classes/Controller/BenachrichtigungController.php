<?php
namespace Subugoe\Schulungen\Controller;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
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
use Subugoe\Schulungen\Domain\Model\Teilnehmer;
use Subugoe\Schulungen\Domain\Model\Termin;
use Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository;
use Subugoe\Schulungen\Domain\Repository\TerminRepository;
use Subugoe\Schulungen\Service\SeminarStateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Zentraler Controller fuer das Versenden von Benachrichtigungen
 * Funktioniert mit unterschiedlichen Methoden im Extbase Kontext und im Scheduler
 */
class BenachrichtigungController extends ActionController
{

    /**
     * @var \Subugoe\Schulungen\Domain\Repository\TerminRepository
     * @inject
     */
    protected $terminRepository;

    /**
     * @var \Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository
     * @inject
     */
    protected $teilnehmerRepository;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var SeminarStateService
     */
    protected $seminarStateService;

    /**
     * Initializes the current action
     */
    protected function initializeAction()
    {

        /** @var ObjectManager objectManager */
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $configurationManager = $this->objectManager->get(ConfigurationManager::class);

        $extbaseFrameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $this->settings = $extbaseFrameworkConfiguration;

        $this->persistenceManager = $this->objectManager->get(PersistenceManager::class);
        $this->terminRepository = $this->objectManager->get(TerminRepository::class);
        $this->teilnehmerRepository = $this->objectManager->get(TeilnehmerRepository::class);
        $this->seminarStateService = $this->objectManager->get(SeminarStateService::class);
    }

    /**
     * Action für den Scheduler: Berechnung der anstehenden Termine plus Mailversand an betroffene Teilnehmer
     *
     * @return bool
     */
    public function sendeBenachrichtigungAction()
    {

        $this->initializeAction();
        $anstehendeTermine = $this->terminRepository->errechneAnstehendeTermine();

        /** @var Termin $erinnerungsTermin */
        foreach ($anstehendeTermine as $erinnerungsTermin) {
            if ($erinnerungsTermin->getErinnerungenVerschickt() == false) {
                $this->sendMailToParticipants($erinnerungsTermin->getTeilnehmer(), $erinnerungsTermin, true);
            } else {
                GeneralUtility::devLog(
                    'Reminder mails already sent.',
                    'schulungen',
                    0
                );
            }
        }

        if (count($anstehendeTermine) === 0) {
            GeneralUtility::devLog(
                'No seminars for the next two days.',
                'schulungen',
                0);
        }

        return true;
    }

    /**
     * Action für das Backend: Einfacher Mailversand bei Ab/-Zusage von Schulungsterminen
     * @param Teilnehmer $participant
     * @param Termin $date
     * @param mixed $obj
     * @return bool
     */
    public function sendeBenachrichtigungSofortAction(Teilnehmer $participant, Termin $date, &$obj)
    {
        $this->initializeAction();
        $result = $this->sendMailToParticipants($participant, $date, true);
        return $result;
    }

    /**
     * Logik-Methode: Legt Typ der zu versendenden Mails fest und sendet diese an übergebene Teilnehmer
     *
     * Methode wird von Scheduler und Backend verwendet
     *
     * Wegen Problemen mit der Darstellung der flashMessages (BE-Aufruf)
     * lassen sich diese über das $silent-Flag abschalten
     *
     * @param mixed $teilnehmer
     * @param Termin $termin
     * @param bool $silent
     * @return bool returns TRUE, if all messages are sent successfully
     */
    protected function sendMailToParticipants($teilnehmer, &$termin, $silent = false)
    {

        $fail = false;
        $seminarState = SeminarStateService::TAKES_PLACE;
        $seminar = $termin->getSchulung();

        /** @var Teilnehmer $person */
        foreach ($teilnehmer as $person) {
            if ($termin->isAbgesagt()) {
                $seminarState = SeminarStateService::CANCELED;
            } else {

                if ($termin->getAnzahlTeilnehmer() >= $seminar->getTeilnehmerMin()) {
                    $seminarState = SeminarStateService::TAKES_PLACE;
                } else {
                    $seminarState = SeminarStateService::TOO_FEW_PARTICIPANTS;
                }
            }


            /* Abschalten der Copy ($cc) für Reminder, da Transaktionsmail existiert */
            $result = $this->sendMail($person, $seminarState);

            if ($result) {
                if (!$silent) {
                    $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.success',
                            'schulungen') . $person->getEmail());
                }
                GeneralUtility::devLog(
                    'Reminder mail ("' . substr($seminar->getTitel(), 0,
                        20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') to ' . $person->getEmail() . ' successfully sent.',
                    'schulungen',
                    -1
                );
            } else {
                if (!$silent) {
                    $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.fail',
                        'schulungen'));
                }
                GeneralUtility::devLog(
                    'Reminder mail ("' . substr($seminar->getTitel(), 0,
                        20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') to ' . $person->getEmail() . ' failed to send!',
                    'schulungen',
                    3
                );
                $fail = true;
            }
        }

        $termin->setAbgesagt($seminarState > 0 ? true : false);

        if (!$fail && $termin->getAnzahlTeilnehmer() > 0) {
            $termin->setErinnerungenVerschickt(true);

            /* Transaktionsmail an Admin/Redakteur */
            $mail = $this->objectManager->get(EmailController::class);
            $result = $mail->sendeTransactionMail($this->settings['mail']['fromMail'],
                $this->settings['mail']['fromName'],
                LocalizationUtility::translate('tx_schulungen_email_versand.transaction_title', 'schulungen'), '',
                [
                    'teilnehmer' => $teilnehmer,
                    'action' => LocalizationUtility::translate('tx_schulungen_email_versand.mail_type.' . $seminarState,
                        'schulungen'),
                    'schulung' => $seminar->getTitel(),
                    'termin' => $termin->getStartzeit(),
                    'ende' => $termin->getEnde()
                ]
            );
            if ($result) {
                if (!$silent) {
                    $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.success',
                            'schulungen') . $person->getEmail());
                }
                GeneralUtility::devLog(
                    'Transaction mail ("' . substr($seminar->getTitel(), 0,
                        20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') successfully sent!',
                    'schulungen',
                    -1
                );
            } else {
                if (!$silent) {
                    $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.fail',
                        'schulungen'));
                }
                GeneralUtility::devLog(
                    'Transaction mail ("' . substr($seminar->getTitel(), 0,
                        20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') failed to send!',
                    'schulungen',
                    3
                );
            }
        }
        $this->terminRepository->update($termin);
        $this->persistenceManager->persistAll();

        return !$fail;
    }

    /**
     * @param Teilnehmer $participant
     * @param int $type
     * @return bool
     * @throws \Exception
     */
    protected function sendMail(Teilnehmer $participant, $type)
    {

        $termin = $participant->getTermin();
        $schulung = $termin->getSchulung();
        /** @var EmailController $mail */
        $mail = $this->objectManager->get(EmailController::class);

        $mailcopy = [];
        $contacts = $schulung->getContact();
        foreach ($contacts as $contact) {
            array_push($mailcopy, $contact->getEmail());
        }

        $result = $mail->sendeMail(
            $participant->getEmail(),
            $this->settings['mail']['fromMail'],
            $this->settings['mail']['fromName'],
            LocalizationUtility::translate('tx_schulungen_email_versand.reminder_title', 'schulungen'),
            $type,
            [
                'vorname' => $participant->getVorname(),
                'nachname' => $participant->getNachname(),
                'studienfach' => $participant->getStudienfach(),
                'bemerkung' => $participant->getBemerkung(),
                'start' => $termin->getStartzeit(),
                'ende' => $termin->getEnde(),
                'schulung' => $schulung->getTitel(),
                'identifier' => $participant->getSecret(),
                'contact' => $mailcopy[0],
                'mailcopy' => $mailcopy
            ]
        );

        return $result;
    }

}
