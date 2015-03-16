<?php
namespace Subugoe\Schulungen\Validation\Validator;

class CustomEmailAddressValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {
	public function isValid($elem) {
		$option1 = $this->options['option1']; // options access
		$this->addError('ErrorString', 1262341470);
		return TRUE; // or FALSE                    // validates if TRUE
	}
}
