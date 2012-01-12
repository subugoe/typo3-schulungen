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
 * @version $Id: TeilnehmerController.php 1583 2012-01-05 13:39:45Z simm $
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
	 * @return string The rendered list view
	 */
	public function listAction() {
		$teilnehmers = $this->teilnehmerRepository->findAll();
		$this->view->assign('teilnehmers', $teilnehmers);
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
		$this->teilnehmerRepository->findByUid($termin);
                
		$this->view->assign('teilnehmerTermin', $termin);
		$this->view->assign('newTeilnehmer', $newTeilnehmer);

                $terminObj = $this->terminRepository->findByUid($termin);
                if($terminObj->getAnzahlTeilnehmer() >= $terminObj->getSchulung()->getTeilnehmerMax()) {
           		$this->redirect('show','Schulung',Null,array('schulung' => $terminObj->getSchulung()));
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
                
		$newTeilnehmer->setTermin($termin);
                                
                if($termin->getAnzahlTeilnehmer() < $termin->getSchulung()->getTeilnehmerMax()) {
                    
                    $this->teilnehmerRepository->add($newTeilnehmer);

                    if (count($this->teilnehmerRepository->getAddedObjects()) === 1) {
                            //mail versenden
                            $this->flashMessageContainer->flush();
                            $this->flashMessageContainer->add('Sie wurden erfolgreich in die Teilnehmerliste eingetragen');

                            if ($this->sendeBestaetigungsMail($newTeilnehmer)) {
                                    $this->flashMessageContainer->add("Eine E-Mail zur Bestätigung wurde an " . $newTeilnehmer->getEmail() . " gesendet");
                            } else {
                                    $this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_email_versand.fail', 'schulungen'));
                            }
                    }
                    
                }   else    {
                            $this->flashMessageContainer->flush();
                            $this->flashMessageContainer->add('Die Veranstaltung ist leider schon voll. Bitte versuchen Sie sich zu einem anderen Termin anzumelden.');                    
                }
		//an die list-action weiterleiten
		$this->redirect('list');
                //$this->schulungController->redirect('list');
	}

	/**
	 * Mailsendemethode
	 * @todo Termin usw in Mail mit angeben. View?
	 * @param Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer
	 * @return boolean 
	 */
	private function sendeBestaetigungsMail(Tx_Schulungen_Domain_Model_Teilnehmer $teilnehmer) {

		$recipient = $teilnehmer->getEmail();

		$sender = $this->settings['mail']['fromMail'];
		$senderName = $this->settings['mail']['fromName'];

		$time = new DateTime();
                $time->setTimestamp(time());
          
		$variables = array(
			'nachname' => $teilnehmer->getNachname(),
			'vorname' => $teilnehmer->getVorname(),
                        'email' => $teilnehmer->getEmail(),
                        'schulungsTitel' => $teilnehmer->getTermin()->getSchulung()->getTitel(),
                        'startZeit' => $teilnehmer->getTermin()->getStartzeit(),
                        'bemerkung' => $teilnehmer->getBemerkung(),
                        'mailcopy' => $teilnehmer->getTermin()->getSchulung()->getMailKopie(),
                        'timestamp' => $time
                    );

                
		$templateName = 'Bestaetigung';

		$subject = Tx_Extbase_Utility_Localization::translate('tx_schulungen_email_bestaetigung_subject', 'schulungen');

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