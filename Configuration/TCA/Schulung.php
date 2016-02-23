<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$TCA['tx_schulungen_domain_model_schulung'] = [
    'ctrl' => $TCA['tx_schulungen_domain_model_schulung']['ctrl'],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, titel,untertitel,kategorie,sort_index,beschreibung,voraussetzungen,treffpunkt,dauer,veranstalter,teilnehmer_min,teilnehmer_max,contact,email_zusatz,anmeldung_deaktiviert,termine_versteckt',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid;;;;1-1-1,titel,untertitel,kategorie,sort_index,beschreibung;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_patenschaften/rte/],voraussetzungen,treffpunkt,dauer,veranstalter,teilnehmer_min,teilnehmer_max,contact,email_zusatz,anmeldung_deaktiviert,termine_versteckt'],
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
                'renderType' => 'selectSingle',
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
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_schulungen_domain_model_schulung',
                'foreign_table_where' => 'AND tx_schulungen_domain_model_schulung.uid=###REC_FIELD_l18n_parent### AND tx_schulungen_domain_model_schulung.sys_language_uid IN (-1,0)',
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
        'titel' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.titel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'untertitel' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.untertitel',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim'
            ],
        ],
        'beschreibung' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.beschreibung',
            'defaultExtras' => 'richtext[]:rte_transform[flag=rte_enabled]',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'required',
                'wizards' => [
                    '_PADDING' => 2,
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'Full screen Rich Text Editing',
                        'icon' => 'wizard_rte2.gif',
                        'module' => [
                            'name' => 'wizard_rte'
                        ]
                    ],
                ],

            ],
        ],
        'email_zusatz' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.email_zusatz',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ],
        ],
        'voraussetzungen' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.voraussetzungen',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 1,
                'eval' => 'trim'
            ],
        ],
        'treffpunkt' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.treffpunkt',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 6,
                'eval' => 'trim'
            ],
        ],
        'dauer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.dauer',
            'config' => [
                'type' => 'input',
                'size' => 15,
                'eval' => 'trim'
            ],
        ],
        'veranstalter' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.veranstalter',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'teilnehmer_min' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.teilnehmer_min',
            'config' => [
                'type' => 'input',
                'size' => 2,
                'eval' => 'int,required'
            ],
        ],
        'teilnehmer_max' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.teilnehmer_max',
            'config' => [
                'type' => 'input',
                'size' => 2,
                'eval' => 'int,required'
            ],
        ],
        'mail_kopie' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.mail_kopie',
            'config' => [
                'type' => 'input',
                'size' => 2,
                'eval' => 'int,required'
            ]
        ],
        'contact' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kontakt',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tt_address',
                'size' => 3,
                'minitems' => 0,
                'maxitems' => 3
            ]
        ],
        'kategorie' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.0',
                        0
                    ],
                    [
                        'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.1',
                        1
                    ],
                    [
                        'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.2',
                        2
                    ],
                    [
                        'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.3',
                        3
                    ],
                    [
                        'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.kategorie.4',
                        4
                    ]
                ],
            ],
        ],
        'termine_versteckt' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.termine_versteckt',
            'config' => [
                'type' => 'check',
                'default' => '0'
            ],
        ],
        'anmeldung_deaktiviert' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.anmeldung_deaktiviert',
            'config' => [
                'type' => 'check',
                'default' => '0'
            ],
        ],
        'sort_index' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_schulung.sort_index',
            'config' => [
                'type' => 'input',
                'size' => 2,
                'eval' => 'int,required'
            ],
        ],
    ]
];
