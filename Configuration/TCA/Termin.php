<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$TCA['tx_schulungen_domain_model_termin'] = array(
		'ctrl' => $TCA['tx_schulungen_domain_model_termin']['ctrl'],
		'interface' => array(
				'showRecordFieldList' => 'schulung,startzeit,ende,abgesagt,teilnehmer',
		),
		'types' => array(
				'1' => array('showitem' => 'schulung,startzeit,ende,abgesagt,teilnehmer'),
		),
		'palettes' => array(
				'1' => array('showitem' => ''),
		),
		'columns' => array(
				'sys_language_uid' => array(
						'exclude' => 1,
						'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
						'config' => array(
								'type' => 'select',
								'foreign_table' => 'sys_language',
								'foreign_table_where' => 'ORDER BY sys_language.title',
								'items' => array(
										array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1),
										array('LLL:EXT:lang/locallang_general.php:LGL.default_value', 0)
								),
						)
				),
				'l18n_parent' => array(
						'displayCond' => 'FIELD:sys_language_uid:>:0',
						'exclude' => 1,
						'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
						'config' => array(
								'type' => 'select',
								'items' => array(
										array('', 0),
								),
								'foreign_table' => 'tx_schulungen_domain_model_termin',
								'foreign_table_where' => 'AND tx_schulungen_domain_model_termin.uid=###REC_FIELD_l18n_parent### AND tx_schulungen_domain_model_termin.sys_language_uid IN (-1,0)',
						)
				),
				'l18n_diffsource' => array(
						'config' => array(
								'type' => 'passthrough',
						)
				),
				't3ver_label' => array(
						'displayCond' => 'FIELD:t3ver_label:REQ:true',
						'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
						'config' => array(
								'type' => 'none',
								'cols' => 27,
						)
				),
				'hidden' => array(
						'exclude' => 1,
						'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
						'config' => array(
								'type' => 'check',
						)
				),
				'startzeit' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.start',
						'config' => array(
								'type' => 'input',
								'size' => 12,
								'max' => 20,
								'eval' => 'datetime',
								'checkbox' => '0',
								'default' => '0'
						),
				),
				'ende' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.ende',
						'config' => array(
								'type' => 'input',
								'size' => 12,
								'max' => 20,
								'eval' => 'datetime',
								'checkbox' => '0',
								'default' => '0'
						),
				),
				'abgesagt' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.abgesagt',
						'config' => array(
								'type' => 'check',
								'default' => 0
						),
				),
				'erinnerungen_verschickt' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.erinnerungenverschickt',
						'config' => array(
								'type' => 'check',
								'default' => 0
						),
				),
				'schulung' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung',
						'config' => array(
								'type' => 'select',
								'loadingStrategy' => 'eager',
								'foreign_table' => 'tx_schulungen_domain_model_schulung',
								'size' => 1,
								'minitems' => 1,
								'maxitems' => 1
						)
				),
				'teilnehmer' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.termin_teilnehmer',
						'config' => array(
								'type' => 'inline',
								'foreign_table' => 'tx_schulungen_domain_model_teilnehmer',
								'foreign_field' => 'termin',
								'minitems' => 0,
								'maxitems' => 9999,
								'appearance' => array(
										'collapse' => 0,
										'newRecordLinkPosition' => 'bottom',
										'showSynchronizationLink' => 1,
										'showPossibleLocalizationRecords' => 1,
										'showAllLocalizationLink' => 1
								),
						),
				),
		),
);
