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

/**
 * A dummy Command Controller with a noop command which simply echoes the argument
 */
class Tx_Schulungen_Command_ReminderCommandController extends Tx_Extbase_MVC_Controller_CommandController {
 
  /**
   * Reminder command
   *
   * Sends an email to each participant of the next 'Schulung'
   *
   * @return void
   */
  public function remindCommand() {
        $success = true;
        $benachrichtigung = t3lib_div::makeInstance('tx_schulungen_controller_benachrichtigungcontroller');
        $success = $benachrichtigung->sendeBenachrichtigungAction();
        if (!$success) {
                t3lib_div::devLog('SendReminder Scheduler Task: Problem during execution. Stopping.' , 'schulungen', 3);
        }

        return $success;
  }
  

 
}?>
