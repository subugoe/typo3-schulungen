<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$TCA['tx_schulungen_domain_model_termin'] = [
		'ctrl' => $TCA['tx_schulungen_domain_model_termin']['ctrl'],
		'interface' => [
				'showRecordFieldList' => 'schulung,startzeit,ende,abgesagt,teilnehmer',
		],
		'types' => [
				'1' => ['showitem' => 'schulung,startzeit,ende,abgesagt,teilnehmer'],
		],
		'palettes' => [
				'1' => ['showitem' => ''],
		],
		'columns' => [
				'sys_language_uid' => [
						'exclude' => 1,
						'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
						'config' => [
								'type' => 'select',
								'foreign_table' => 'sys_language',
								'foreign_table_where' => 'ORDER BY sys_language.title',
								'items' => [
										['LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1],
										['LLL:EXT:lang/locallang_general.php:LGL.default_value', 0]
								],
						]
				],
				'l18n_parent' => [
						'displayCond' => 'FIELD:sys_language_uid:>:0',
						'exclude' => 1,
						'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
						'config' => [
								'type' => 'select',
								'items' => [
										['', 0],
								],
								'foreign_table' => 'tx_schulungen_domain_model_termin',
								'foreign_table_where' => 'AND tx_schulungen_domain_model_termin.uid=###REC_FIELD_l18n_parent### AND tx_schulungen_domain_model_termin.sys_language_uid IN (-1,0)',
						]
				],
				'l18n_diffsource' => [
						'config' => [
								'type' => 'passthrough',
						]
				],
				't3ver_label' => [
						'displayCond' => 'FIELD:t3ver_label:REQ:true',
						'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
						'config' => [
								'type' => 'none',
								'cols' => 27,
						]
				],
				'hidden' => [
						'exclude' => 1,
						'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
						'config' => [
								'type' => 'check',
						]
				],
				'startzeit' => [
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.start',
						'config' => [
								'type' => 'input',
								'size' => 12,
								'max' => 20,
								'eval' => 'datetime',
								'checkbox' => '0',
								'default' => '0'
						],
				],
				'ende' => [
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.ende',
						'config' => [
								'type' => 'input',
								'size' => 12,
								'max' => 20,
								'eval' => 'datetime',
								'checkbox' => '0',
								'default' => '0'
						],
				],
				'abgesagt' => [
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.abgesagt',
						'config' => [
								'type' => 'check',
								'default' => 0
						],
				],
				'erinnerungen_verschickt' => [
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.erinnerungenverschickt',
						'config' => [
								'type' => 'check',
								'default' => 0
						],
				],
				'schulung' => [
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung',
						'config' => [
								'type' => 'select',
								'loadingStrategy' => 'eager',
								'foreign_table' => 'tx_schulungen_domain_model_schulung',
								'size' => 1,
								'minitems' => 1,
								'maxitems' => 1
						]
				],
				'teilnehmer' => [
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_termin.termin_teilnehmer',
						'config' => [
								'type' => 'inline',
								'foreign_table' => 'tx_schulungen_domain_model_teilnehmer',
								'foreign_field' => 'termin',
								'minitems' => 0,
								'maxitems' => 9999,
								'appearance' => [
										'collapse' => 0,
										'newRecordLinkPosition' => 'bottom',
										'showSynchronizationLink' => 1,
										'showPossibleLocalizationRecords' => 1,
										'showAllLocalizationLink' => 1
								],
						],
				],
		],
];
