<?php

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

/**
 * Zentraler Controller fuer das Versenden von E-Mails 
 * Funktioniert mit unterschiedlichen Methoden im Extbase Kontext und im Scheduler
 * @author ingop
 */
class Tx_Schulungen_Controller_EmailController extends Tx_Extbase_MVC_Controller_AbstractController {
    
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
		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_ConfigurationManager');
		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

		$templateRootPath = t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['view.']['templateRootPath']);

		$templatePathAndFilename = $templateRootPath . 'Email/' . $templateName . '.html';
		$emailView = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);

		$emailView->setFormat('html');

		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();

		$return = false;

		// Wir nutzen den Swiftmailer
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');

		$mail->setFrom($sender);

		$mail->addTo($recipient);
		if($variables['copy'] != false) {
			$mail->addCc($sender);
			$mail->addBcc($variables['mailcopy']);
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
	 * @param array $variables
	 * @return boolean 
	 */
	public function sendeTransactionMail($sender, $senderName, $subject, array $variables = array()) {
		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_ConfigurationManager');
		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

		$templateRootPath = t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['view.']['templateRootPath']);

		$templatePathAndFilename = $templateRootPath . 'Email/TransactionMail.html';
		$emailView = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);

		$emailView->setFormat('html');

		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();

		$return = false;

		// Wir nutzen den Swiftmailer
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');

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

		// Wir nutzen den Swiftmailer
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');

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

?>
