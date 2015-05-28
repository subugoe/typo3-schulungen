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
use Subugoe\Schulungen\Controller\BenachrichtigungController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

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
		/** @var ConfigurationManager $configurationManager */
		$configurationManager = $this->objectManager->get(ConfigurationManager::class);
		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$configuration = [
			'settings' => [
				'persistence' => [
					'storagePid' => 1648
				],
				'mail' => [
					'fromMail' => $extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['mail.']['fromMail'],
					'fromName' => $extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['mail.']['fromName']
				]
			]
		];

		/** @var BenachrichtigungController $benachrichtigung */
		$benachrichtigung = $this->objectManager->get(BenachrichtigungController::class);
		$benachrichtigung->config = $configuration['settings'];

		$success = $benachrichtigung->sendeBenachrichtigungAction();
		if (!$success) {
			GeneralUtility::devLog('SendReminder Scheduler Task: Problem during execution. Stopping.' , 'schulungen', 3);
		}

		return $success;
	}

}
