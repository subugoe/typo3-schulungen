<?php
namespace Subugoe\Controller;
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
use SJBR\StaticInfoTables\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller for the Teilnehmer object
 *
 */
class TeilnehmerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository
	 * @inject
	 */
	protected $teilnehmerRepository;
	/**
	 *
	 * @var \Subugoe\Schulungen\Domain\Repository\TerminRepository
	 * @inject
	 */
	protected $terminRepository;
	/**
	 *
	 * @var \Subugoe\Schulungen\Controller\EmailController
	 * @inject
	 */
	protected $emailController;

	/**
	 * @var \Subugoe\Schulungen\Controller\SchulungController
	 * @inject
	 */
	protected $schulungController;

	/**
	 * Displays all Teilnehmers
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Schulung $schulung
	 * @param string $status
	 * @return string The rendered list view
	 */
	public function listAction(\Subugoe\Schulungen\Domain\Model\Schulung $schulung, $status) {
		$teilnehmers = $this->teilnehmerRepository->findAll();
		$this->view->assign('teilnehmers', $teilnehmers);

		$this->view->assign('schulungsTitel', $schulung->getTitel());
		$this->view->assign('status', $status);
		$this->view->assign('contacts', $schulung->getContact());
	}

	/**
	 * Displays a single Teilnehmer
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer the Teilnehmer to display
	 * @return string The rendered view
	 */
	public function showAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$this->view->assign('teilnehmer', $teilnehmer);
	}

	/**
	 * Creates a new Teilnehmer and forwards to the list action.
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $newTeilnehmer a fresh Teilnehmer object which has not yet been added to the repository
	 * @param \Subugoe\Schulungen\Domain\Model\Termin $termin
	 * @return string An HTML form for creating a new Teilnehmer
	 * @dontvalidate $newTeilnehmer
	 */
	public function newAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $newTeilnehmer = NULL, \Subugoe\Schulungen\Domain\Model\Termin $termin = NULL) {

		$termin = intval($this->request->getArgument('termin'));
		$terminObj = $this->terminRepository->findByUid($termin);
		$schulung = $terminObj->getSchulung();
		$schulungsTitel = $schulung->getTitel();
		$contacts = $schulung->getContact();

		$this->view->assign('teilnehmerTermin', $terminObj);
		$this->view->assign('newTeilnehmer', $newTeilnehmer);
		$this->view->assign('schulungsTitel', $schulungsTitel);
		$this->view->assign('contacts', $contacts);

		if ($terminObj->getAnzahlTeilnehmer() >= $terminObj->getSchulung()->getTeilnehmerMax()) {
			$this->redirect('show', 'Schulung', Null, array('schulung' => $schulung));
		}

	}

	/**
	 * Creates a new Teilnehmer and forwards to the list action.
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $newTeilnehmer a fresh Teilnehmer object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $newTeilnehmer) {
		$status = 'fail';
		$time = new \DateTime('now');
		$termin = intval($this->request->getArgument('termin'));
		$termin = $this->terminRepository->findByUid($termin);
		$schulung = $termin->getSchulung();

		$newTeilnehmer->setTermin($termin);
		// secret-identifier: timestamp,lastname,email,prename
		$identifier = md5($time->getTimestamp() . $newTeilnehmer->getNachname() .
			$newTeilnehmer->getEmail() . $newTeilnehmer->getVorname());
		$newTeilnehmer->setSecret($identifier);

		if ($termin->getAnzahlTeilnehmer() < $schulung->getTeilnehmerMax()) {

			if (count($this->teilnehmerRepository->teilnehmerAngemeldet($newTeilnehmer, $termin)) == 0) {

				$this->teilnehmerRepository->add($newTeilnehmer);

				if (count($this->teilnehmerRepository->getAddedObjects()) === 1) {
					//mail versenden
					$this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
					$this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.bestaetigung.text', 'schulungen'));

					if ($sender = $this->sendeBestaetigungsMail($newTeilnehmer)) {
						$this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.bestaetigung.email', 'schulungen', array($newTeilnehmer->getEmail())));
						$status = 'success';
					} else {
						$this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.fail', 'schulungen'));
					}
				}
				// Teilnehmer existiert schon
			} else {
				$this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
				$this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.already_registered', 'schulungen'));
			}
			// Termin ist voll
		} else {
			$this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
			$this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.fail', 'schulungen'));
		}

		//an die list-action weiterleiten
		$this->redirect('list', 'Teilnehmer', Null, array("schulung" => $schulung, "status" => $status));
	}

	/**
	 * Mailsendemethode
	 * @todo Termin usw in Mail mit angeben. View?
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer
	 * @return boolean
	 */
	private function sendeBestaetigungsMail(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$time = new \DateTime('now');
		$recipient = $teilnehmer->getEmail();
		$sender = $this->settings['mail']['fromMail'];
		$senderName = $this->settings['mail']['fromName'];
		$mailcopy = array();
		$contacts = $teilnehmer->getTermin()->getSchulung()->getContact();
		foreach ($contacts as $contact) {
			array_push($mailcopy, $contact->getEmail());
		}

		$variables = array(
			'nachname' => $teilnehmer->getNachname(),
			'vorname' => $teilnehmer->getVorname(),
			'email' => $teilnehmer->getEmail(),
			'studienfach' => $teilnehmer->getStudienfach(),
			'bemerkung' => $teilnehmer->getBemerkung(),
			'schulungsTitel' => $teilnehmer->getTermin()->getSchulung()->getTitel(),
			'startZeit' => $teilnehmer->getTermin()->getStartzeit(),
			'ende' => $teilnehmer->getTermin()->getEnde(),
			'timestamp' => $time,
			'identifier' => array($teilnehmer->getSecret()),
			'mailcopy' => $mailcopy,
			'copy' => true,
		);

		$templateName = LocalizationUtility::translate('tx_schulungen_email_bestaetigung_template', 'schulungen');

		$title = explode(':', $teilnehmer->getTermin()->getSchulung()->getTitel());
		$subject = LocalizationUtility::translate('tx_schulungen_email_bestaetigung_subject', 'schulungen') . " (" . $title[0] . " - " . $teilnehmer->getTermin()->getStartzeit()->format('d.m.Y H:i') . ")";

		return $this->emailController->sendeMail($recipient, $sender, $senderName, $subject, $templateName, $variables);

	}

	/**
	 * Updates an existing Teilnehmer and forwards to the index action afterwards.
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer the Teilnehmer to display
	 * @return string A form to edit a Teilnehmer
	 */
	public function editAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$this->view->assign('teilnehmer', $teilnehmer);
	}

	/**
	 * Updates an existing Teilnehmer and forwards to the list action afterwards.
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer the Teilnehmer to display
	 */
	public function updateAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->update($teilnehmer);
		$this->addFlashMessage('Your Teilnehmer was updated.');
		$this->redirect('list');
	}

	/**
	 * Updates an existing Teilnehmer and forwards to the list action afterwards.
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer the Teilnehmer to display
	 */
	public function updateBackendAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->update($teilnehmer);
		$this->addFlashMessage('Your Teilnehmer was updated.');
		$this->redirect('detail', 'Backend', 'schulungen', array("termin" => $teilnehmer->getTermin()));
	}

	/**
	 * De-Registration of a Teilnehmer by Mail-Notification
	 *
	 * @param $identifier the Teilnehmer to be de-registered
	 * @return void
	 */
	public function deregisterAction($identifier) {
		$time_format = LocalizationUtility::translate('tx_schulungen_format.date', 'schulungen');
		$now = new \DateTime("now");
		GeneralUtility::devlog("De-Registration: Passed value " . $identifier, "Schulungen", 1, $identifier);
		if (count($teilnehmer = $this->teilnehmerRepository->findOneBySecret($identifier[0])) > 0) {
			$schulung = $teilnehmer->getTermin()->getSchulung();

			// create deep copy of termin to prevent db-update
			$termin = unserialize(serialize($teilnehmer->getTermin()));
			$this->view->assign('schulungsTitel', $schulung->getTitel() . ' (' . $termin->getStartzeit()->format($time_format) . ')');
			$this->view->assign('contacts', $schulung->getContact());

			// Deadline is the start time of the event
			$limit = $termin->getStartzeit();

			// Only delete Teilnehmer, if deadline isn't crossed
			if ($now < $limit) {
				$this->teilnehmerRepository->remove($teilnehmer);
				$flashMsg = LocalizationUtility::translate('tx_schulungen_domain_model_teilnehmer.deregister.success.flash', 'schulungen');
				$this->addFlashMessage($flashMsg . $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')');
				$this->addFlashMessage($flashMsg . $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')');

				// Mail notification
				$sender = $this->settings['mail']['fromMail'];
				$senderName = $this->settings['mail']['fromName'];
				$title = explode(':', $teilnehmer->getTermin()->getSchulung()->getTitel());
				$subject = LocalizationUtility::translate('tx_schulungen_domain_model_teilnehmer.deregister', 'schulungen') . " (" . $title[0] . " - " . $teilnehmer->getTermin()->getStartzeit()->format('d.m.Y H:i') . ")";
				$templateName = LocalizationUtility::translate('tx_schulungen_email_deregistration_template', 'schulungen');
				$result = $this->emailController->sendeTransactionMail($sender, $senderName, $subject, $templateName,
					array(
						'teilnehmer' => $teilnehmer,
						'schulung' => $schulung->getTitel(),
						'termin' => $termin->getStartzeit(),
						'ende' => $termin->getEnde()
					)
				);
				GeneralUtility::devlog("De-Registration: TransactionMail sent?", "Schulungen", 1, array($result));

				$this->view->assign('status', 'success');
			} else {
				$flashMsg = LocalizationUtility::translate('tx_schulungen_domain_model_teilnehmer.deregister.fail.flash', 'schulungen');
								$this->flashMessageContainer->add(str_replace('###TEILNEHMER###', $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')', $flashMsg));
				$this->addFlashMessage(str_replace('###TEILNEHMER###', $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')', $flashMsg));
				$this->view->assign('status', 'fail');
			}
		} else {
			$this->redirect('list', 'Schulung');
		}
	}

	/**
	 * Deletes an existing Teilnehmer
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer the Teilnehmer to be deleted
	 * @return void
	 */
	public function deleteAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->remove($teilnehmer);
		$this->addFlashMessage('Your Teilnehmer was removed.');
		$this->redirect('list');
		$this->redirect('detail', 'Backend', 'schulungen', array("termin" => $teilnehmer->getTermin()));
	}

	/**
	 * Deletes an existing Teilnehmer
	 *
	 * @param \Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer the Teilnehmer to be deleted
	 * @return void
	 */
	public function deleteBackendAction(\Subugoe\Schulungen\Domain\Model\Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->remove($teilnehmer);
		$this->addFlashMessage('Your Teilnehmer was removed.');
		$this->redirect('detail', 'Backend', 'schulungen', array("termin" => $teilnehmer->getTermin()));
	}

}
