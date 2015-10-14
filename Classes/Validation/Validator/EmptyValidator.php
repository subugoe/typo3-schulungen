<?php
namespace Subugoe\Schulungen\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class EmptyValidator extends AbstractValidator
{

    public function isValid($elem)
    {
        if (empty($elem) || $elem === "") {
            return true;
        } else {
            $this->addError('ErrorString', 1338992386);
            return false;
        }
    }
}
