<?php
namespace Subugoe\Schulungen\Validation\Validator;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class CaptchaValidator extends AbstractValidator {
	public function isValid($elem) {
		require_once(ExtensionManagementUtility::extPath('jm_recaptcha') . "class.tx_jmrecaptcha.php");
		$recaptcha = new \tx_jmrecaptcha();

		$status = $recaptcha->validateReCaptcha();
		if ($status['verified']) {
			return TRUE; // validates if TRUE
		} else {
			$this->addError($status['error'], 1338820079); // type \TYPO3\CMS\Extbase\ValidationError
			return FALSE; // no Validation
		}
	}
}
