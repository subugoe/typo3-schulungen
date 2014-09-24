<?php
namespace Subugoe\Schulungen\Domain\Validator;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CaptchaValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {
	public function isValid($elem) {
		require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('jm_recaptcha') . "class.tx_jmrecaptcha.php");
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
