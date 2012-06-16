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
 * Controller for the Teilnehmer object
 *
 * @version $Id: TeilnehmerController.php 1886 2012-06-13 12:31:19Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Controller_TeilnehmerController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * teilnehmerRepository
	 *
	 * @var Tx_Schulungen_Domain_Repository_TeilnehmerRepository
	 */
	protected $teilnehmerRepository;
	/**
	 *
	 * @var Tx_Schulungen_Domain_Repository_TerminRepository
	 */
	protected $terminRepository;
	/**
	 *
	 * @var Tx_Schulungen_Controller_EmailController
	 */
	protected $emailController;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->teilnehmerRepository = t3lib_div::makeInstance('Tx_Schulungen_Domain_Repository_TeilnehmerRepository');
		$this->terminRepository = t3lib_div::makeInstance('Tx_Schulungen_Domain_Repository_TerminRepository');
		$this->schulungController = t3lib_div::makeInstance('Tx_Schulungen_Controller_SchulungController');
		$this->emailController = t3lib_div::makeInstance('Tx_Schulungen_Controller_EmailController');
	}

	/**
	 * Displays all Teilnehmers
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $schulung
	 * @return string The rendered list view
	 */
	public function listAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$teilnehmers = $this->teilnehmerRepository->findAll();
		$this->view->assign('teilnehmers', $teilnehmers);
		
		$this->view->assign('schulungsTitel', $schulung->getTitel());
		$this->view->assign('contacts', $schulung->getContact());
	}

	/**
	 * Displays a single Teilnehmer
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer the Teilnehmer to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$this->view->assign('teilnehmer', $teilnehmer);
	}

	/**
	 * Creates a new Teilnehmer and forwards to the list action.
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $newTeilnehmer a fresh Teilnehmer object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Teilnehmer
	 * @dontvalidate $newTeilnehmer
	 */
	public function newAction(Tx_Schulungen_Domain_Model_Teilnehmer $newTeilnehmer = null, Tx_Schulungen_Domain_Model_Termin $termin = null) {

		$termin = intval($this->request->getArgument('termin'));
		$terminObj = $this->terminRepository->findByUid($termin);
		$schulung = $terminObj->getSchulung();
		$schulungsTitel = $schulung->getTitel();
		$contacts = $schulung->getContact();
		
		$this->view->assign('teilnehmerTermin', $terminObj);
		$this->view->assign('newTeilnehmer', $newTeilnehmer);
		$this->view->assign('schulungsTitel', $schulungsTitel);
		$this->view->assign('contacts', $contacts);

		if($terminObj->getAnzahlTeilnehmer() >= $terminObj->getSchulung()->getTeilnehmerMax()) {
			$this->redirect('show', 'Schulung', Null, array('schulung' => $schulung));
		}

	}

	/**
	 * Creates a new Teilnehmer and forwards to the list action.
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $newTeilnehmer a fresh Teilnehmer object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Schulungen_Domain_Model_Teilnehmer $newTeilnehmer) {
		$termin = intval($this->request->getArgument('termin'));
		$termin = $this->terminRepository->findByUid($termin);
		$schulung = $termin->getSchulung();
                
		$time = new DateTime();
		$time->setTimestamp(time());

		$newTeilnehmer->setTermin($termin);
		// secret-identifier: timestamp,lastname,email,prename
		$identifier = md5($time->getTimestamp() . $newTeilnehmer->getNachname() . 
						$newTeilnehmer->getEmail() . $newTeilnehmer->getVorname());
		$newTeilnehmer->setSecret($identifier);

		if($termin->getAnzahlTeilnehmer() < $schulung->getTeilnehmerMax()) {

			$this->teilnehmerRepository->add($newTeilnehmer);

			if (count($this->teilnehmerRepository->getAddedObjects()) === 1) {
				//mail versenden
				$this->flashMessageContainer->flush();
				$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_teilnehmer_create.bestaetigung.text', 'schulungen'));

				if ($sender = $this->sendeBestaetigungsMail($newTeilnehmer)) {
						$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_teilnehmer_create.bestaetigung.email', 'schulungen', array($newTeilnehmer->getEmail())));
				} else {
						$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_email_versand.fail', 'schulungen'));
				}
			}

		}   else    {
			$this->flashMessageContainer->flush();
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_teilnehmer_create.fail', 'schulungen'));
		}

		//an die list-action weiterleiten
		$this->redirect('list', 'Teilnehmer', Null, array("schulung" => $schulung));
		// $this->schulungController->redirect('list');
	}

	/**
	 * Mailsendemethode
	 * @todo Termin usw in Mail mit angeben. View?
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer
	 * @return boolean 
	 */
	private function sendeBestaetigungsMail(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$time = new DateTime('now');
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
						'timestamp' => $time,
						'identifier' => array($teilnehmer->getSecret()),
						'mailcopy' => $mailcopy,
						'copy' => true,
            	);
                
		$templateName = Tx_Extbase_Utility_Localization::translate('tx_schulungen_email_bestaetigung_template', 'schulungen');

		$title = explode(':', $teilnehmer->getTermin()->getSchulung()->getTitel());
		$subject = Tx_Extbase_Utility_Localization::translate('tx_schulungen_email_bestaetigung_subject', 'schulungen') . " (" . $title[0] . " - " . $teilnehmer->getTermin()->getStartzeit()->format('d.m.Y H:i') . ")";

		return $this->emailController->sendeMail($recipient, $sender, $senderName, $subject, $templateName, $variables);

	}

	/**
	 * Updates an existing Teilnehmer and forwards to the index action afterwards.
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer the Teilnehmer to display
	 * @return string A form to edit a Teilnehmer
	 */
	public function editAction(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$this->view->assign('teilnehmer', $teilnehmer);
	}

	/**
	 * Updates an existing Teilnehmer and forwards to the list action afterwards.
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer the Teilnehmer to display
	 */
	public function updateAction(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->update($teilnehmer);
		$this->flashMessageContainer->add('Your Teilnehmer was updated.');
		$this->redirect('list');
	}

	/**
	 * Updates an existing Teilnehmer and forwards to the list action afterwards.
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer the Teilnehmer to display
	 */
	public function updateBackendAction(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->update($teilnehmer);
		$this->flashMessageContainer->add('Your Teilnehmer was updated.');
		// $this->redirect('index','Backend');
		$this->redirect('detail', 'Backend', $_EXTKEY, array("termin" => $teilnehmer->getTermin()));
	}

	/**
	 * De-Registration of a Teilnehmer by Mail-Notification
	 *
	 * @param $identifier the Teilnehmer to be de-registered
	 * @return void
	 */
	public function deregisterAction($identifier) {
		$time_format = Tx_Extbase_Utility_Localization::translate('tx_schulungen_format.date', 'schulungen');
		$now = new DateTime("now");
		t3lib_div::devlog("De-Registration: Passed value " . $identifier, "Schulungen", 1, $identifier);
		if(count($teilnehmer = $this->teilnehmerRepository->findOneBySecret($identifier[0])) > 0)	{
			$schulung = $teilnehmer->getTermin()->getSchulung();
				// create deep copy of termin to prevent db-update
			$termin = unserialize(serialize($teilnehmer->getTermin()));
			$this->view->assign('schulungsTitel', $schulung->getTitel() . ' (' . $termin->getStartzeit()->format($time_format) . ')');
			$this->view->assign('contacts', $schulung->getContact());

			$limit = $termin->getStartzeit()->sub(new DateInterval('P1D'));
			$limit = new DateTime($limit->format('Y-m-d') . ' 04:30');
				// only delete Teilnehmer, if deadline isn't crossed
			if($now < $limit)	{
				$this->teilnehmerRepository->remove($teilnehmer);
				$flashMsg = Tx_Extbase_Utility_Localization::translate('tx_schulungen_domain_model_teilnehmer.deregister.success.flash', 'schulungen');
				$this->flashMessageContainer->add($flashMsg . $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')');
				$this->view->assign('status', 'success');
			}	else 	{
				$flashMsg = Tx_Extbase_Utility_Localization::translate('tx_schulungen_domain_model_teilnehmer.deregister.fail.flash', 'schulungen');
				$this->flashMessageContainer->add(str_replace('###TEILNEHMER###', $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')', $flashMsg));
				$this->view->assign('status', 'fail');
			}
		}	else 	{
			$this->redirect('list', 'Schulung');
		}
	}

	/**
	 * Deletes an existing Teilnehmer
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer the Teilnehmer to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->remove($teilnehmer);
		$this->flashMessageContainer->add('Your Teilnehmer was removed.');
		$this->redirect('list');
		$this->redirect('detail', 'Backend', $_EXTKEY, array("termin" => $teilnehmer->getTermin()));
	}

        /**
	 * Deletes an existing Teilnehmer
	 *
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer the Teilnehmer to be deleted
	 * @return void
	 */
	public function deleteBackendAction(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {
		$this->teilnehmerRepository->remove($teilnehmer);
		$this->flashMessageContainer->add('Your Teilnehmer was removed.');
		$this->redirect('detail', 'Backend', $_EXTKEY, array("termin" => $teilnehmer->getTermin()));
	}

}

?>