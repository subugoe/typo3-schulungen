<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tx_Schulungen_Domain_Validator_CustomEmailAddressValidator extends Tx_Extbase_Validation_Validator_AbstractValidator {
    public function isValid($elem) {
        $option1 = $this->options['option1'];       // options access 
        $this->addError('ErrorString', 1262341470); // type Tx_Extbase_Validation_Error 
        return TRUE; // or FALSE                    // validates if TRUE
    }
}

?>
