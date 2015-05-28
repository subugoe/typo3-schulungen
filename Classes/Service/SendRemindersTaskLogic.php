<?php
namespace Subugoe\Schulungen\Service;
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
use Subugoe\Schulungen\Controller\BenachrichtigungController;
use Subugoe\Schulungen\Domain\Repository\SchulungRepository;
use Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository;
use Subugoe\Schulungen\Domain\Repository\TerminRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;

/**
 * Send reminders to participants
 */
class SendRemindersTaskLogic extends Bootstrap {

	/**
	 * @var BenachrichtigungController
	 */
	protected $benachrichtigung;

	public function execute(&$pObj) {

		// set parent task object
		$this->pObj = $pObj;

		// set this sucker up!
		$this->setupFramework();

		// initalization
		$this->initRepositories();

		$success = true;
		$this->benachrichtigung = GeneralUtility::makeInstance(BenachrichtigungController::class);
		$this->benachrichtigung->config['mail'] = $this->mailConfig;
		$success = $this->benachrichtigung->sendeBenachrichtigungAction();
		if (!$success) {
			GeneralUtility::devLog(
					'SendReminder-Task: Problem during execution. Stopping.',
					'schulungen',
					3
			);
		} else {
			GeneralUtility::devLog(
					'SendReminder-Task: Successfully executed.',
					'schulungen',
					-1
			);
		}

		return $success;
	}

	protected function setupFramework() {
		$configuration = [
			'extensionName' => 'schulungen',
			'pluginName' => 'Scheduler',
			'settings' => '< plugin.tx_schulungen',
			'controller' => 'Benachrichtigung',
			'switchableControllerActions' => [
				'Benachrichtigung' => ['actions' => 'sendeBenachrichtigung'],
				'Termin' => ['actions' => 'update']
			],
		];
		$this->initialize($configuration);
	}

	/**
	 * @return void
	 */
	protected function initRepositories() {
		$this->schulungRepository = $this->objectManager->get(SchulungRepository::class);
		$this->teilnehmerRepository = $this->objectManager->get(TeilnehmerRepository::class);
		$this->terminRepository = $this->objectManager->get(TerminRepository::class);
	}

}
