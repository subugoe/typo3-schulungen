<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tx_Schulungen_Domain_Validator_CaptchaValidator extends Tx_Extbase_Validation_Validator_AbstractValidator {
    public function isValid($elem) {
	require_once(t3lib_extMgm::extPath('jm_recaptcha')."class.tx_jmrecaptcha.php"); 
	$recaptcha = new tx_jmrecaptcha(); 

	$status = $recaptcha->validateReCaptcha(); 
	if ($status['verified']) { 
	        return TRUE;					// validates if TRUE
	} else { 
	        $this->addError($status['error'], 1338820079);	// type Tx_Extbase_Validation_Error 
	        return FALSE;		                    	// no Validation
	} 
    }
}

?>
