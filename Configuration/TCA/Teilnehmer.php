<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$TCA['tx_schulungen_domain_model_teilnehmer'] = array(
	'ctrl' => $TCA['tx_schulungen_domain_model_teilnehmer']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'vorname,nachname,email,studienfach,bemerkung,termin',
	),
	'types' => array(
		'1' => array('showitem' => 'vorname,nachname,email,studienfach,bemerkung,termin'),
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
				'foreign_table' => 'tx_schulungen_domain_model_teilnehmer',
				'foreign_table_where' => 'AND tx_schulungen_domain_model_teilnehmer.uid=###REC_FIELD_l18n_parent### AND tx_schulungen_domain_model_teilnehmer.sys_language_uid IN (-1,0)',
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
		'vorname' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.vorname',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'nachname' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.nachname',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'studienfach' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.studienfach',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'bemerkung' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.bemerkung',
			'config'	=> array(
				'type' => 'text', 
				'cols' => 50,
				'rows' => 5,
				'eval' => 'trim', 
                         ),
		),
		'termin' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.teilnehmer_termin',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_schulungen_domain_model_termin',
				'size' => 1,
				'maxitems' => 1,
			),
		),
		'secret' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.secret',
			'config' => array(
				'type' => 'none',
				'eval' => 'trim'
			),
		),

	),
);
?>