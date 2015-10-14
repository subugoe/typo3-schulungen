<?php
namespace Subugoe\Schulungen\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class CustomEmailAddressValidator extends AbstractValidator
{
    public function isValid($elem)
    {
        $option1 = $this->options['option1'];
        $this->addError('ErrorString', 1262341470);
        return true;
    }
}
