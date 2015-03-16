<?php
namespace Subugoe\Schulungen\Validation\Validator;

class EmptyValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {

	public function isValid($elem) {
		if (empty($elem) || $elem === "") {
			return TRUE; // validates if TRUE
		} else {
			$this->addError('ErrorString', 1338992386);
			return FALSE; // no Validation
		}
	}
}
