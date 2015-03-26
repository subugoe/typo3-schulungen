<?php
namespace Subugoe\Schulungen\Command;
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
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
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A dummy Command Controller with a noop command which simply echoes the argument
 */
class ReminderCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {
 
	/**
	* Reminder command
	*
	* Sends an email to each participant of the next 'Schulung'
	*
	* @return bool
	*/
	public function remindCommand() {
		/** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
		$configurationManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$configuration = array(
			'settings' => array(
				'persistence' => array(
					'storagePid' => 1648
				),
				'mail' => array(
					'fromMail' => $extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['mail.']['fromMail'],
					'fromName' => $extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['mail.']['fromName']
				)
			)
		);

		/** @var \Subugoe\Schulungen\Controller\BenachrichtigungController $benachrichtigung */
		$benachrichtigung = $this->objectManager->get(\Subugoe\Schulungen\Controller\BenachrichtigungController::class);
		$benachrichtigung->config = $configuration['settings'];

		$success = $benachrichtigung->sendeBenachrichtigungAction();
		if (!$success) {
			GeneralUtility::devLog('SendReminder Scheduler Task: Problem during execution. Stopping.' , 'schulungen', 3);
		}

		return $success;
	}
   
}
