<?php

return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_note',
        'label'                    => 'author',
        'label_alt'                => 'author_name',
        'label_alt_force'          => true,
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled' => 'hidden',
        ],
        'searchFields'             => 'content,author,author_name',
        'iconfile'                 => 'EXT:tw_events/Resources/Public/Icons/Note.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, content,author,author_name',
    ],
    'types'     => [
        '1' => [
            'showitem' => '
        --palette--;;author,
        content,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden,
        sys_language_uid, l10n_parent, l10n_diffsource
        '
        ],
    ],
    'palettes'  => [
        'author' => ['showitem' => 'author, author_name', 'canNotCollapse' => true],
    ],
    'columns'   => [
        'sys_language_uid' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'special'    => 'languages',
                'items'      => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default'    => 0,
            ],
        ],
        'l10n_parent'      => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => true,
            'label'       => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config'      => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'default'             => 0,
                'items'               => [
                    ['', 0],
                ],
                'foreign_table'       => 'tx_twevents_domain_model_note',
                'foreign_table_where' => 'AND {#tx_twevents_domain_model_note}.{#pid}=###CURRENT_PID### AND {#tx_twevents_domain_model_note}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource'  => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden'           => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'  => [
                'type'       => 'check',
                'renderType' => 'checkboxToggle',
                'items'      => [
                    [
                        0                    => '',
                        1                    => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],

        'author'       => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_note.author',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_twevents_domain_model_person',
                'minitems'      => 0,
                'maxitems'      => 1,
                'items'         => [['---', null]]
            ],
        ],
        'author_name'  => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_note.author_name',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'content'      => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_note.content',
            'config'  => [
                'type'                  => 'text',
                'enableRichtext'        => true,
                'richtextConfiguration' => 'default',
                'fieldControl'          => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols'                  => 40,
                'rows'                  => 15,
                'eval'                  => 'trim',
            ],
        ],
        'presentation' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
