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
 * Controller for the Schulung object
 *
 * @version $Id: BackendController.php 1588 2012-01-11 17:58:42Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Controller_BackendController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * schulungRepository
	 *
	 * @var Tx_Schulungen_Domain_Repository_SchulungRepository
	 */
	protected $schulungRepository;
	/**
	 * terminRepository
	 *
	 * @var Tx_Schulungen_Domain_Repository_TerminRepository
	 */
	protected $terminRepository;
	/**
	 * Teilnehmer
	 * @var Tx_Schulungen_Domain_Repository_TeilnehmerRepository
	 */
	protected $teilnehmerRepository;

	/**
	 * inject Schulung
	 *
	 * @param Tx_Schulungen_Domain_Repository_SchulungRepository $schulungRepository
	 * @return
	 */
	public function injectSchulung(Tx_Schulungen_Domain_Repository_SchulungRepository $schulungRepository) {
		$this->schulungRepository = $schulungRepository;
	}

	/**
	 * DI fuer Termin
	 *
	 * @param Tx_Schulungen_Domain_Repository_TerminRepository $terminRepository
	 */
	public function injectTermin(Tx_Schulungen_Domain_Repository_TerminRepository $terminRepository) {
		$this->terminRepository = $terminRepository;
	}

	/**
	 * DI fuer Teilnehmer
	 *
	 * @param Tx_Schulungen_Domain_Repository_TeilnehmerRepository $teilnehmerRepository
	 */
	public function injectTeilnehmer(Tx_Schulungen_Domain_Repository_TeilnehmerRepository $teilnehmerRepository) {
		$this->teilnehmerRepository = $teilnehmerRepository;
	}

	/**
	 * Displays all Schulungs
	 *
	 * @return string The rendered list view
	 */
	public function indexAction() {
		$schulungs = $this->schulungRepository->findAll();
		$numberOfTermine = $this->terminRepository->countAll();
		$numberOfTeilnehmer = $this->teilnehmerRepository->countAll();
/*		$schulungs = array_merge($schulungs, array($numberOfTermine));
		$schulungs = array_merge($schulungs, array($numberOfTeilnehmer));
*/		$this->view->assign('schulungs', $schulungs);

        }

	/**
	 * Displays a single Termin with Details
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $termin the Schulung to display
	 * @return string The rendered view
	 */
	public function detailAction(Tx_Schulungen_Domain_Model_Termin $termin) {
		$this->view->assign('termin', $termin);
	}

	/**
	 * Creates a new Schulung and forwards to the list action.
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $newSchulung a fresh Schulung object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Schulung
	 * @dontvalidate $newSchulung
	 */
	public function newAction(Tx_Schulungen_Domain_Model_Schulung $newSchulung = null) {
		$this->view->assign('newSchulung', $newSchulung);
	}

	/**
	 * Creates a new Schulung and forwards to the list action.
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $newSchulung a fresh Schulung object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Schulungen_Domain_Model_Schulung $newSchulung) {
		$this->schulungRepository->add($newSchulung);
		$this->flashMessageContainer->add('Your new Schulung was created.');
		$this->redirect('list');
	}

	/**
	 * Deletes an existing Schulung
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $schulung the Schulung to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$this->schulungRepository->remove($schulung);
		$this->flashMessageContainer->add('Your Schulung was removed.');
		$this->redirect('list');
	}

	/**
	 * Absagen eines Schulungstermins
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $termin
	 */
	public function cancelAction(Tx_Schulungen_Domain_Model_Termin $termin) {
		$termin->setAbgesagt(true);
		$this->terminRepository->update($termin);
                
		$this->benachrichtigung = t3lib_div::makeInstance('Tx_Schulungen_Controller_BenachrichtigungController');
		$teilnehmer = $termin->getTeilnehmer();
		$this->benachrichtigung->sendeBenachrichtigungSofortAction($teilnehmer, $termin);

		$this->flashMessageContainer->add('Schulung wurde abgesagt. Die Teilnehmer werden per E-Mail benachrichtigt!');
		$this->redirect('index');
	}

	/**
	 * Wieder Zusagen eines Schulungstermins
	 * @param Tx_Schulungen_Domain_Model_Termin $termin
	 */
	public function uncancelAction(Tx_Schulungen_Domain_Model_Termin $termin) {
		$termin->setAbgesagt(false);
		$this->terminRepository->update($termin);

		$this->benachrichtigung = t3lib_div::makeInstance('Tx_Schulungen_Controller_BenachrichtigungController');
		$teilnehmer = $termin->getTeilnehmer();
		$this->benachrichtigung->sendeBenachrichtigungSofortAction($teilnehmer, $termin);
                
		$this->flashMessageContainer->add('Schulung wurde wieder zugesagt. Die Teilnehmer werden per E-Mail benachrichtigt!');
		$this->redirect('index');
	}

	/**
	 * action update
	 *
	 * @return string The rendered update action
	 * @param $schulung
	 */
	public function updateAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$this->schulungRepository->update($schulung);
	}

	/**
	 * Just a test for fed
	 *
	 * @return void
	 */
	public function exportAction(){
		
		$schulungs = $this->schulungRepository->findAll();
		$this->view->assign('fluidVarsObject', $schulungs);
	}

}

?>