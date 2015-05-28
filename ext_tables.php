<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Subugoe.' . $_EXTKEY, 'Schulungen', 'Schulungen'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Subugoe.' . $_EXTKEY, 'SchulungenSideMenu', 'Schulungen (Side-Menu)'
);

/**
 * Registers a Backend Module
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
	'Subugoe.' . $_EXTKEY,
	'web', // Make module a submodule of 'web'
	'schulungsverwaltung', // Submodule key
	'',
	// Position
	[
		'Backend' => 'index,detail,cancel,uncancel,export',
		'Teilnehmer' => 'deregister,edit,list,updateBackend,deleteBackend'
	],
	[
		'access' => 'user,group',
		'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
		'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_schulungsverwaltung.xml',
	]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Schulungen');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_schulungen_domain_model_schulung', 'EXT:schulungen/Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_schulung.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schulungen_domain_model_schulung');
$TCA['tx_schulungen_domain_model_schulung'] = [
	'ctrl' => [
		'title' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung',
		'label' => 'titel',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Schulung.php',
		'searchFields' => 'titel',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_schulungen_domain_model_schulung.gif'
	]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_schulungen_domain_model_termin', 'EXT:schulungen/Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_termin.xml');
$TCA['tx_schulungen_domain_model_termin'] = [
	'ctrl' => [
		'title' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin',
		'label' => 'startzeit',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Termin.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_schulungen_domain_model_termin.gif',
		'default_sortby' => 'ORDER BY tstamp DESC'
	]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_schulungen_domain_model_teilnehmer', 'EXT:schulungen/Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_teilnehmer.xml');
$TCA['tx_schulungen_domain_model_teilnehmer'] = [
	'ctrl' => [
		'title' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer',
		'label' => 'nachname',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Teilnehmer.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_schulungen_domain_model_tn.gif',
		'default_sortby' => 'ORDER BY termin, nachname DESC'
	]
];

\Subugoe\Schulungen\Utility\HelperUtility::flexFormAutoLoader();
