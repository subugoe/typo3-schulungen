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
				t3lib_div::devLog('SendReminder-Scheduler-Task: Problem during execution. Stopping.' , 'schulungen', 3);
		}

		// $this->tearDownFramework();
                
		return $success;

	}
        
	protected function setupFramework()     {

			/* Example */
/*		$configuration = array(
			'extensionName' => 'myext',
			'pluginName' => 'tx_myext_task',
			'settings' => '< plugin.tx_myext.settings',
			'persistence' => '< plugin.tx_myext.persistence',
			'view' => '< plugin.tx_myext.view',
			'persistence.' => array(
				'storagePid' => $this->pObj->getPid()
			),
			'_LOCAL_LANG' => '< plugin.tx_myext._LOCAL_LANG'
		);
*/        
		
		$configuration = array(
			'extensionName' => 'schulungen',
			'pluginName' => 'scheduler',
			'settings' => '< plugin.tx_schulungen',
			'controller' => 'Benachrichtigung',
			'switchableControllerActions' => array(
				 'Benachrichtigung' => array('actions' => 'sendeBenachrichtigung')
   			),
			'mail' => array(
				'fromMail' => 'zentralinfo@sub.uni-goettingen.de', 
//				'fromMail' => 'dominic.simm@sub.uni-goettingen.de', 
				'fromName' => 'SUB Göttingen'
			),
		);

		$this->mailConfig = $configuration['mail'];
		$this->initialize($configuration);
                
	}

	protected function initRepositories() {
		$this->schulungRepository = $this->objectManager->get('tx_schulungen_domain_repository_schulungrepository');
		$this->teilnehmerRepository = $this->objectManager->get('tx_schulungen_domain_repository_teilnehmerrepository');
		$this->terminRepository = $this->objectManager->get('tx_schulungen_domain_repository_terminrepository');
	}

}

?>
