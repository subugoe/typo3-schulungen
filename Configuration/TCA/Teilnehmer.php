<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$TCA['tx_schulungen_domain_model_teilnehmer'] = [
    'ctrl' => $TCA['tx_schulungen_domain_model_teilnehmer']['ctrl'],
    'interface' => [
        'showRecordFieldList' => 'vorname,nachname,email,studienfach,bemerkung,termin',
    ],
    'types' => [
        '1' => ['showitem' => 'vorname,nachname,email,studienfach,bemerkung,termin'],
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
                'foreign_table' => 'tx_schulungen_domain_model_teilnehmer',
                'foreign_table_where' => 'AND tx_schulungen_domain_model_teilnehmer.uid=###REC_FIELD_l18n_parent### AND tx_schulungen_domain_model_teilnehmer.sys_language_uid IN (-1,0)',
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
        'vorname' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.vorname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'nachname' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.nachname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'email' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'studienfach' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.studienfach',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'bemerkung' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.bemerkung',
            'config' => [
                'type' => 'text',
                'cols' => 50,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'termin' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.teilnehmer_termin',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_schulungen_domain_model_termin',
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'secret' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:schulungen/Resources/Private/Language/locallang_db.xml:tx_schulungen_domain_model_teilnehmer.secret',
            'config' => [
                'type' => 'none',
                'eval' => 'trim'
            ],
        ],

    ],
];
