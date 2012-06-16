<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tx_Schulungen_Domain_Validator_EmptyValidator extends Tx_Extbase_Validation_Validator_AbstractValidator {
    public function isValid($elem) {
    	if(empty($elem) || $elem === "")	{
	        return TRUE;					// validates if TRUE
	} else { 
	        $this->addError('ErrorString', 1338992386);	// type Tx_Extbase_Validation_Error 
	        return FALSE;		                    	// no Validation
	} 
    }
}

?>
