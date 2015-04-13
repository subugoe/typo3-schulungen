<?php
namespace Subugoe\Schulungen\Controller;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Zentraler Controller fuer das Versenden von E-Mails
 * Funktioniert mit unterschiedlichen Methoden im Extbase Kontext und im Scheduler
 */
class EmailController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Methode zum Versenden von E-Mails
	 * @param string $recipient
	 * @param string $sender
	 * @param string $senderName
	 * @param string $subject
	 * @param string $templateName
	 * @param array $variables
	 * @return boolean
	 */
	public function sendeMail($recipient, $sender, $senderName, $subject, $templateName, array $variables = array()) {
		$configurationManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

		$templateRootPath = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['view.']['templateRootPath']);

		$templatePathAndFilename = $templateRootPath . 'Email/' . $templateName . '.html';
		$emailView = $this->objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);

		$emailView->setFormat('html');

		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();

		$return = false;

		// Wir nutzen den Swiftmailer
		/** @var \TYPO3\CMS\Core\Mail\MailMessage $mail */
		$mail = $this->objectManager->get(\TYPO3\CMS\Core\Mail\MailMessage::class);

		$mail->setFrom($sender);

		$mail->addTo($recipient);
		if ($variables['copy'] == true) {
			if (is_array($variables['mailcopy'])) {
				foreach ($variables['mailcopy'] as $mailcopy) {
					$mail->addBcc($mailcopy);
				}
			}
		}

		$mail->setSubject($subject);
		$mail->setBody($emailBody, 'text/html');

		if ($mail->send() > 0) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Benachrichtigungsmethode über gerade erfolgte Transaktion
	 * @param string $sender
	 * @param string $senderName
	 * @param string $subject
	 * @param string $templateName
	 * @param array $variables
	 * @return boolean
	 */
	public function sendeTransactionMail($sender, $senderName, $subject, $templateName, array $variables = array()) {
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

		$templateRootPath = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['view.']['templateRootPath']);
		if (strlen($templateName) == 0) {
			$templatePathAndFilename = $templateRootPath . 'Email/TransactionMail.html';
		} else {
			$templatePathAndFilename = $templateRootPath . 'Email/' . $templateName . '.html';
		}
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
		$emailView = $this->objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);

		$emailView->setFormat('html');

		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();

		$return = false;

		// Wir nutzen den Swiftmailer
		/** @var \TYPO3\CMS\Core\Mail\MailMessage $mail */
		$mail = $this->objectManager->get(\TYPO3\CMS\Core\Mail\MailMessage::class);

		$mail->setFrom($sender);

		$mail->addTo($sender);

		$mail->setSubject($subject);
		$mail->setBody($emailBody, 'text/html');

		if ($mail->send() > 0) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Keine Fluid Frontendgeschichten verfuegbar im Scheduler (nur mit hohem Aufwand)
	 * @todo Fluid E-Mail Templating
	 * @param string $recipient
	 * @param string $sender
	 * @param string $senderName
	 * @param string $subject
	 * @return boolean
	 */
	public function sendeSchedulerMail($recipient, $sender, $senderName, $subject) {

		//@todo Body zentral definieren wenn nicht mit fluid Nutzung
		$emailBody = "Erinnerung an die Schulung";

		$return = false;

		/** @var \TYPO3\CMS\Core\Mail\MailMessage $mail */
		$mail = $this->objectManager->get(\TYPO3\CMS\Core\Mail\MailMessage::class);

		$mail->setFrom($sender);

		$mail->setTo($recipient);

		$mail->setSubject($subject);
		$mail->setBody($emailBody, 'text/html');

		if ($mail->send() > 0) {
			$return = true;
		}

		return $return;
	}

}
