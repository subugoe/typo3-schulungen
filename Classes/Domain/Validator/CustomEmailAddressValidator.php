<?php
namespace Subugoe\Schulungen\Domain\Validator;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CustomEmailAddressValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {
	public function isValid($elem) {
		$option1 = $this->options['option1']; // options access
		$this->addError('ErrorString', 1262341470);
		return TRUE; // or FALSE                    // validates if TRUE
	}
}
