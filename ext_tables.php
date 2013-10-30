<?php

if (!defined('TYPO3_MODE'))
	die('Access denied.');


Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY, 'Schulungen', 'Schulungen'
);

Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY, 'SchulungenSideMenu', 'Schulungen (Side-Menu)'
);

/**
 * Registers a Backend Module
 */
Tx_Extbase_Utility_Extension::registerModule(
	$_EXTKEY,
	'web', // Make module a submodule of 'web'
	'schulungsverwaltung', // Submodule key
	'',
	 // Position
	array(
		'Backend' => 'index,detail,cancel,uncancel,export',
		'Teilnehmer' => 'deregister,edit,list,updateBackend,deleteBackend'
	),
	array(
		'access' => 'user,group',
		'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
		'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_schulungsverwaltung.xml',
	)
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Schulungen');

t3lib_extMgm::addLLrefForTCAdescr('tx_schulungen_domain_model_schulung', 'EXT:schulungen/Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_schulung.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_schulungen_domain_model_schulung');
$TCA['tx_schulungen_domain_model_schulung'] = array(
	'ctrl' => array(
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
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Schulung.php',
		'searchFields' => 'titel',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_schulungen_domain_model_schulung.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_schulungen_domain_model_termin', 'EXT:schulungen/Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_termin.xml');
$TCA['tx_schulungen_domain_model_termin'] = array(
	'ctrl' => array(
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
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Termin.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_schulungen_domain_model_termin.gif',
		'default_sortby' => 'ORDER BY tstamp DESC'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_schulungen_domain_model_teilnehmer', 'EXT:schulungen/Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_teilnehmer.xml');
$TCA['tx_schulungen_domain_model_teilnehmer'] = array(
	'ctrl' => array(
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
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Teilnehmer.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_schulungen_domain_model_tn.gif',
		'default_sortby' => 'ORDER BY termin, nachname DESC'
	)
);

Tx_Schulungen_Utility_HelperUtility::flexFormAutoLoader();

?>