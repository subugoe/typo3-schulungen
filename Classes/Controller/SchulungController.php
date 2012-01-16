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
 * @version $Id: SchulungController.php 1590 2012-01-13 17:38:19Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Controller_SchulungController extends Tx_Extbase_MVC_Controller_ActionController {

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
	public function listAction() {
		$schulungs = $this->schulungRepository->findAll();
		$this->view->assign('schulungs', $schulungs);
	}

	/**
	 * Termine und Teilnehmer pro Schulung anzeigen
	 */
	public function listTermineUndTeilnehmerAction() {

		$schulung = $this->schulungRepository->findAll();
		$this->view->assign('schulung', $schulung);
	}

	/**
	 * Displays a single Schulung
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $schulung the Schulung to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {

		$termine = $this->terminRepository->errechneAnstehendeSchulungTermine($schulung);

		$time = new DateTime();
		$time->setTimestamp(time());

		$this->view->assign('time',$time);
		if(count($termine) > 0) $this->view->assign('termine', $termine);
		$this->view->assign('schulung', $schulung);
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
	 * action edit
	 *
	 * @return string The rendered edit action
	 * @param $schulung
	 */
	public function editAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$this->view->assign('schulung', $schulung);
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
	 * action export
	 *
	 * @return void
	 * @param int $uid
	 */
	public function exportAction($uid = null){
		$schulungs = $this->schulungRepository->findAll();
 		$this->view->assign('fluidVarsObject', $schulungs);
	}

}

?>