<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_schulungen_domain_model_schulung'] = array(
		'ctrl' => $TCA['tx_schulungen_domain_model_schulung']['ctrl'],
		'interface' => array(
				'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, titel,untertitel,kategorie,sort_index,beschreibung,voraussetzungen,treffpunkt,dauer,veranstalter,teilnehmer_min,teilnehmer_max,contact,anmeldung_deaktiviert,termine_versteckt',
		),
		'types' => array(
				'1' => array('showitem' => 'sys_language_uid;;;;1-1-1,titel,untertitel,kategorie,sort_index,beschreibung;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_patenschaften/rte/],voraussetzungen,treffpunkt,dauer,veranstalter,teilnehmer_min,teilnehmer_max,contact,anmeldung_deaktiviert,termine_versteckt'),
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
								'foreign_table' => 'tx_schulungen_domain_model_schulung',
								'foreign_table_where' => 'AND tx_schulungen_domain_model_schulung.uid=###REC_FIELD_l18n_parent### AND tx_schulungen_domain_model_schulung.sys_language_uid IN (-1,0)',
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
				'titel' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.titel',
						'config' => array(
								'type' => 'input',
								'size' => 30,
								'eval' => 'trim,required'
						),
				),
				'untertitel' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.untertitel',
						'config' => array(
								'type' => 'input',
								'size' => 40,
								'eval' => 'trim'
						),
				),
				'beschreibung' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.beschreibung',
						'config' => array(
								'type' => 'text',
								'cols' => 40,
								'rows' => 15,
								'eval' => 'required',
								'wizards' => array(
										'_PADDING' => 2,
										'RTE' => array(
												'notNewRecords' => 1,
												'RTEonly' => 1,
												'type' => 'script',
												'title' => 'Full screen Rich Text Editing',
												'icon' => 'wizard_rte2.gif',
												'script' => 'wizard_rte.php',
										),
								),

						),
				),
				'voraussetzungen' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.voraussetzungen',
						'config' => array(
								'type' => 'text',
								'cols' => 40,
								'rows' => 1,
								'eval' => 'trim'
						),
				),
				'treffpunkt' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.treffpunkt',
						'config' => array(
								'type' => 'text',
								'cols' => 40,
								'rows' => 6,
								'eval' => 'trim'
						),
				),
				'dauer' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.dauer',
						'config' => array(
								'type' => 'input',
								'size' => 15,
								'eval' => 'trim'
						),
				),
				'veranstalter' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.veranstalter',
						'config' => array(
								'type' => 'input',
								'size' => 30,
								'eval' => 'trim'
						),
				),
				'teilnehmer_min' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.teilnehmer_min',
						'config' => array(
								'type' => 'input',
								'size' => 2,
								'eval' => 'int,required'
						),
				),
				'teilnehmer_max' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.teilnehmer_max',
						'config' => array(
								'type' => 'input',
								'size' => 2,
								'eval' => 'int,required'
						),
				),
				'mail_kopie' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.mail_kopie',
						'config' => array(
								'type' => 'input',
								'size' => 2,
								'eval' => 'int,required'
						)
				),
				'contact' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kontakt',
						'config' => array(
								'type' => 'group',
								'internal_type' => 'db',
								'allowed' => 'tt_address',
								'size' => 3,
								'minitems' => 0,
								'maxitems' => 3
						)
				),
				'kategorie' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie',
						'config' => array(
								'type' => 'select',
								'items' => array(
										array('LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.0', 0),
										array('LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.1', 1),
										array('LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.2', 2),
										array('LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.3', 3),
										array('LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.4', 4)
								),
						),
				),
				'termine_versteckt' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.termine_versteckt',
						'config' => array(
								'type' => 'check',
								'default' => '0'
						),
				),
				'anmeldung_deaktiviert' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.anmeldung_deaktiviert',
						'config' => array(
								'type' => 'check',
								'default' => '0'
						),
				),
				'sort_index' => array(
						'exclude' => 0,
						'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.sort_index',
						'config' => array(
								'type' => 'input',
								'size' => 2,
								'eval' => 'int,required'
						),
				),
		)
	);
?>