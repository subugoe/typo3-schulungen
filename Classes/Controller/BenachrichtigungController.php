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
use Subugoe\Schulungen\Domain\Repository\TerminRepository;
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
class BenachrichtigungController extends ActionController {

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

	/*
	 * 0 = normale Erinnerung
	 * 1 = zu wenig Teilnehmer
	 * 2 = Termin wurde gecanceled
	 */
	protected $mailType = [
			'Reminder',
			'ReminderParticipation',
			'ReminderCancelation'
	];

	/**
	 * @var ObjectManager
	 */
	protected $objectManager;

	/**
	 * @var PersistenceManager
	 */
	protected $persistenceManager;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {

		/** @var ObjectManager objectManager */
		$this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		$configurationManager = $this->objectManager->get(ConfigurationManager::class);

		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$this->settings = $extbaseFrameworkConfiguration;

		$this->persistenceManager = $this->objectManager->get(PersistenceManager::class);
		$this->terminRepository = $this->objectManager->get(TerminRepository::class);
		$this->teilnehmerRepository = $this->objectManager->get(Teilnehmer::class);
	}

	/**
	 * Action für den Scheduler: Berechnung der anstehenden Termine plus Mailversand an betroffene Teilnehmer
	 *
	 * @return bool
	 */
	public function sendeBenachrichtigungAction() {

		$this->initializeAction();
		$anstehendeTermine = $this->terminRepository->errechneAnstehendeTermine();

		/** @var Termin $erinnerungsTermin */
		foreach ($anstehendeTermine as $erinnerungsTermin) {
			if ($erinnerungsTermin->getErinnerungenVerschickt() == FALSE) {
				$this->verschickeMailAnTeilnehmer($erinnerungsTermin->getTeilnehmer(), $erinnerungsTermin, TRUE);
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

		return TRUE;
	}

	/**
	 * Action für das Backend: Einfacher Mailversand bei Ab/-Zusage von Schulungsterminen
	 * @param Teilnehmer $teilnehmer
	 * @param Termin $termin
	 * @param mixed $obj
	 */
	public function sendeBenachrichtigungSofortAction($teilnehmer, $termin, &$obj) {
		$this->initializeAction();
		$result = $this->verschickeMailAnTeilnehmer($teilnehmer, $termin, TRUE);
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
	 * @return bool
	 */
	protected function verschickeMailAnTeilnehmer($teilnehmer, &$termin, $silent = FALSE) {

		$fail = FALSE;
		$mailType = 0;
		$schulung = $termin->getSchulung();

		/** @var Teilnehmer $person */
		foreach ($teilnehmer as $person) {
			if (!$termin->isAbgesagt()) {
				if ($termin->getAnzahlTeilnehmer() >= $schulung->getTeilnehmerMin()) {
					/* normale Erinnerung */
					$mailType = 0;
					$cc = FALSE;
				} else {
					/* zu wenig Teilnehmer */
					$mailType = 1;
					$cc = FALSE;
				}
			} else {
				/* Termin wurde ohne Begründung abgesagt */
				$mailType = 2;
				$cc = FALSE;
			}
			/* Abschalten der Copy ($cc) für Reminder, da Transaktionsmail existiert */
			$result = $this->sendeMail($person, $mailType, $cc);

			if ($result) {
				if (!$silent) $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.success', 'schulungen') . $person->getEmail());
				GeneralUtility::devLog(
						'Reminder mail ("' . substr($schulung->getTitel(), 0, 20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') to ' . $person->getEmail() . ' successfully sent.',
						'schulungen',
						-1
				);
			} else {
				if (!$silent) $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.fail', 'schulungen'));
				GeneralUtility::devLog(
						'Reminder mail ("' . substr($schulung->getTitel(), 0, 20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') to ' . $person->getEmail() . ' failed to send!',
						'schulungen',
						3
				);
				$fail = TRUE;
			}
		}
		$termin->setAbgesagt($mailType > 0 ? TRUE : FALSE);

		if (!$fail && $termin->getAnzahlTeilnehmer() > 0) {
			$termin->setErinnerungenVerschickt(TRUE);

			/* Transaktionsmail an Admin/Redakteur */
			$mail = $this->objectManager->get(EmailController::class);
			$result = $mail->sendeTransactionMail($this->settings['mail']['fromMail'], $this->settings['mail']['fromName'], LocalizationUtility::translate('tx_schulungen_email_versand.transaction_title', 'schulungen'), '',
					['teilnehmer' => $teilnehmer,
							'action' => LocalizationUtility::translate('tx_schulungen_email_versand.mail_type.' . $mailType, 'schulungen'),
							'schulung' => $schulung->getTitel(),
							'termin' => $termin->getStartzeit(),
							'ende' => $termin->getEnde()
					]
			);
			if ($result) {
				if (!$silent) $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.success', 'schulungen') . $person->getEmail());
				GeneralUtility::devLog(
						'Transaction mail ("' . substr($schulung->getTitel(), 0, 20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') successfully sent!',
						'schulungen',
						-1
				);
			} else {
				if (!$silent) $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.fail', 'schulungen'));
				GeneralUtility::devLog(
						'Transaction mail ("' . substr($schulung->getTitel(), 0, 20) . '...", ' . $termin->getStartzeit()->format('d.m.Y') . ') failed to send!',
						'schulungen',
						3
				);
			}
		}
		$this->persistenceManager->persistAll();

		return !$fail; // returns TRUE, wenn alle Nachrichten erfolgreich versendet wurden
	}

	/**
	 * @param Teilnehmer $tn
	 * @param $type
	 * @param $cc
	 * @return bool
	 * @throws \Exception
	 */
	protected function sendeMail(Teilnehmer $tn, $type, $cc) {

		$termin = $tn->getTermin();
		$schulung = $termin->getSchulung();
		/** @var \Subugoe\Schulungen\Controller\EmailController $mail */
		$mail = $this->objectManager->get(\Subugoe\Schulungen\Controller\EmailController::class);

		$mailcopy = [];
		$contacts = $schulung->getContact();
		foreach ($contacts as $contact) {
			array_push($mailcopy, $contact->getEmail());
		}

		$result = $mail->sendeMail(
				$tn->getEmail(),
				$this->settings['mail']['fromMail'],
				$this->settings['mail']['fromName'],
				LocalizationUtility::translate('tx_schulungen_email_versand.reminder_title', 'schulungen'),
				$this->mailType[$type],
				[
						'vorname' => $tn->getVorname(),
						'nachname' => $tn->getNachname(),
						'studienfach' => $tn->getStudienfach(),
						'bemerkung' => $tn->getBemerkung(),
						'start' => $termin->getStartzeit(),
						'ende' => $termin->getEnde(),
						'schulung' => $schulung->getTitel(),
						'identifier' => $tn->getSecret(),
						'contact' => $mailcopy[0],
						'mailcopy' => $mailcopy,
						'copy' => $cc
				]
		);

		return $result;
	}

}
