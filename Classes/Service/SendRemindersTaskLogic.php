<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 Dominic Simm <dominic.simm@sub.uni-goettingen.de>
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

require_once(t3lib_extMgm::extPath('schulungen') . '/Classes/Utility/Uri.php');

/**
 * Reminder an die Teilnehmer versenden
 *
 * @author Dominic Simm <dominic.simm@sub.uni-goettingen.de>
 * @package Schulungen
 * @subpackage Service
 */
class Tx_Schulungen_Service_SendRemindersTaskLogic extends Tx_Extbase_Core_Bootstrap {

	public function execute(&$pObj) {

		// set parent task object
		$this->pObj = $pObj;

		// set this sucker up!
		$this->setupFramework();

		// initalization
		$this->initRepositories();

		$success = true;
		$this->benachrichtigung = t3lib_div::makeInstance('tx_schulungen_controller_benachrichtigungcontroller');
		$this->benachrichtigung->config['mail'] = $this->mailConfig;
		$success = $this->benachrichtigung->sendeBenachrichtigungAction();
		if (!$success) {
			t3lib_div::devLog('SendReminder-Task: Problem during execution. Stopping.', 'schulungen', 3);
		} else {
			t3lib_div::devLog('SendReminder-Task: Successfully executed.', 'schulungen', -1);
		}

		// $this->tearDownFramework();

		return $success;
	}

	protected function setupFramework() {

		$configuration = array(
			'extensionName' => 'schulungen',
			'pluginName' => 'Scheduler',
			'settings' => '< plugin.tx_schulungen',
			'controller' => 'Benachrichtigung',
			'switchableControllerActions' => array(
				'Benachrichtigung' => array('actions' => 'sendeBenachrichtigung'),
				'Termin' => array('actions' => 'update')
			),
		);
		$this->initialize($configuration);

	}

	protected function initRepositories() {
		$this->schulungRepository = $this->objectManager->get('tx_schulungen_domain_repository_schulungrepository');
		$this->teilnehmerRepository = $this->objectManager->get('tx_schulungen_domain_repository_teilnehmerrepository');
		$this->terminRepository = $this->objectManager->get('tx_schulungen_domain_repository_terminrepository');
	}

}

?>