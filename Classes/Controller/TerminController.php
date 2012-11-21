<?php
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


/**
 * Controller for the Termin object
 *
 * @version $Id: TerminController.php 1974 2012-11-15 09:27:31Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

 class Tx_Schulungen_Controller_TerminController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * terminRepository
	 *
	 * @var Tx_Schulungen_Domain_Repository_TerminRepository
	 */
	protected $terminRepository;

	/**
	 * Displays a single Termin
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $termin the Termin to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Schulungen_Domain_Model_Termin $termin) {
		$this->view->assign('termin', $termin);
	}

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->terminRepository = t3lib_div::makeInstance(Tx_Schulungen_Domain_Repository_TerminRepository);
	}

	/**
	 * action list
	 *
	 * @return string The rendered list action
	 */
	public function listAction() {
	}

	/**
	 * action new
	 *
	 * @return string The rendered new action
	 */
	public function newAction() {
		$this->view->assign('termin');
	}

	/**
	 * action create
	 *
	 * @return string The rendered create action
	 */
	public function createAction() {
	}

	/**
	 * action edit
	 *
	 * @return string The rendered edit action
	 */
	public function editAction() {
	}

	/**
	 * action update
	 *
	 * @return string The rendered update action
	 */
	public function updateAction() {
	}

	/**
	 * action delete
	 *
	 * @return string The rendered delete action
	 */
	public function deleteAction() {
	}
	
	/**
	 * action export
	 *
	 * @return void
	 * @param int $uid
	 */
	public function exportAction($uid = null){
	  	$parameter = t3lib_div::_GP('tx_schulungen');
		$termin = $this->terminRepository->findByUid($parameter['uid']);
 		$this->view->assign('termin', $termin);
	}
}
?>