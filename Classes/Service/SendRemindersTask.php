<?php

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
 *
 * @author Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 * @package Schulungen
 * @subpackage Service
 */
class Tx_Schulungen_Service_SendRemindersTask extends tx_scheduler_Task {

	private $mail;
	private $terminModel;

	/**
	 * Method executed from the Scheduler.
	 * @return  boolean TRUE if success, otherwise FALSE
	 */
        public function execute() {

                    /* Try to initialize ConfigurationManager to import  */
//              $configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_ConfigurationManager');
//		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            
                $configuration = array(
			'extensionName' => 'schulungen',
			'pluginName' => 'scheduler',
//                        'settings' => '< plugin.tx_schulungen',
/*                        'settings' => array('mail' => 
                                        array(
                                            'fromMail' => t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['mail.']['fromMail']),
                                            'fromName' => t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['mail.']['fromName'])
                                        )
                                    ),
*/                          
                        'settings' => array('mail' => 
                                        array(
                                            'fromMail' => 'zentralinfo@sub.uni-goettingen.de',
//                                            'fromMail' => "dominic.simm@sub.uni-goettingen.de",
                                            'fromName' => "SUB Zentralinformation"
                                        )
                                      ),
                            /* funktioniert so alles nicht: Typo3 findet keinen "default controller" */
                        'controller' => 'Benachrichtigung',
    			'switchableControllerActions' => array(
                                 'Benachrichtigung' => array('actions' => 'sendeBenachrichtigung')
   			),
                    );
                
                $_SERVER['REQUEST_METHOD'] = 'CLI'; // 'CLI|GET'

                /* Trying to set the default controller */
//                $_GET['scheduler']['controller'] = 'Benachrichtigung';     // Typo3 findet keinen "default controller"
//                $_GET['scheduler']['action'] = 'sendeBenachrichtigung';    // "

                /* Set the default controller, this works */
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['schulungen']['modules']['scheduler']['controllers'] 
                = array(
                     'Benachrichtigung' => array('actions' => array('sendeBenachrichtigung'))
                );
                
                if (!class_exists('Tx_Extbase_Utility_ClassLoader', FALSE)) {
                    $classLoader = t3lib_div::makeInstance('Tx_Extbase_Utility_ClassLoader');
                    spl_autoload_register(array($classLoader, 'loadClass'));
                }
                
                $bootstrap = t3lib_div::makeInstance('Tx_Extbase_Core_Bootstrap');
                $bootstrap->run('', $configuration);
                
                return true;
                
//                $reminder = t3lib_div::makeInstance('Tx_Schulungen_Service_SendRemindersTaskLogic');
//                $reminder->execute($this);
//                return true;

        }
        
	/**
	 * Function executed by the Scheduler.
	 * @return	boolean	TRUE if success, otherwise FALSE
	 */
/*	public function execute() {
                $success = true;
                $benachrichtigung = t3lib_div::makeInstance('tx_schulungen_controller_benachrichtigungcontroller');
                $success = $benachrichtigung->sendeBenachrichtigungAction();
                if (!$success) {
                        t3lib_div::devLog('SendReminder Scheduler Task: Problem during execution. Stopping.' , 'schulungen', 3);
                }

		return $success;
	} */
        

	/**
	 * Suche aller anstehenden Schulungen
	 * @return boolean 
	 */
	public function getTermine() {

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'*', //WHAT
						'tx_schulungen_domain_model_termin', //FROM
						'WHERE erinnerungenverschickt = 0 AND abgesagt = 0 AND  TIMESTAMPDIFF(DAY,FROM_UNIXTIME(startzeit),NOW()) >=0 AND TIMESTAMPDIFF(DAY,FROM_UNIXTIME(startzeit),NOW()) <2', //WHERE
						'', '', //ORDER BY
						'' //LIMIT
		);
		while ($termin = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$this->terminModel = t3lib_div::makeInstance('Tx_Schulungen_Domain_Model_Termin');

			$this->getTeilnehmer($termin['uid']);
		}

		return true;
	}

	/**
	 * Auswahl der Teilnehmer pro Schulung
	 * @param type $schulungstermin 
	 */
	private function getTeilnehmer($schulungstermin) {
		$teilnehmerquery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'*', //WHAT
						'tx_schulungen_domain_model_teilnehmer', //FROM
						'WHERE termin = '                  . $schulungstermin, //WHERE
						'', '', //ORDER BY
						'' //LIMIT
		);
		while ($teilnehmer = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$this->sendeErinnerungsMail($teilnehmer['email']);
		}
	}

	/**
	 * Senden der E-Mail
	 * @param type $teilnehmer
	 * @return boolean
	 */
	private function sendeErinnerungsMail($teilnehmer) {
		return $this->mail->sendeSchedulerMail($teilnehmer, 'info@sub.uni-goettingen.de', 'Schulungserinnerung', 'Erinnerung an Ihre Veranstaltung');
	}

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/schulungen/Classes/Service/SendRemindersTask.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/schulungen/Classes/Service/SendRemindersTask.php']);
}
?>