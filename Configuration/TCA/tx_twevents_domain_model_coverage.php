<?php

use Tollwerk\TwEvents\Domain\Model\Coverage;

return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage',
        'label'                    => 'title',
        'label_alt'                => 'type, author, author_name',
        'label_alt_force'          => true,
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'languageField'            => 'sys_language_uid',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete'                   => 'deleted',
        'enablecolumns'            => [
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
        ],
        'searchFields'             => 'url',
        'iconfile'                 => 'EXT:tw_events/Resources/Public/Icons/Coverage.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type, url, author',
    ],
    'types'     => [
        '1' => [
            'showitem' => '
                    --palette--;;titletype,
                    --palette--;;author,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    starttime,
                    endtime
                    sys_language_uid, l10n_parent, l10n_diffsource, hidden
                '
        ],
    ],
    'palettes'  => [
        'titletype' => ['showitem' => 'title, type, url', 'canNotCollapse' => true],
        'author'    => ['showitem' => 'author, author_name', 'canNotCollapse' => true],
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
                'foreign_table'       => 'tx_twevents_domain_model_coverage',
                'foreign_table_where' => 'AND {#tx_twevents_domain_model_coverage}.{#pid}=###CURRENT_PID### AND {#tx_twevents_domain_model_coverage}.{#sys_language_uid} IN (-1,0)',
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
        'starttime'        => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config'  => [
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'eval'       => 'datetime,int',
                'default'    => 0,
                'behaviour'  => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime'          => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config'  => [
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'eval'       => 'datetime,int',
                'default'    => 0,
                'range'      => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour'  => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'title'            => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'type'             => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.type',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.type.gallery',
                        Coverage::TYPE_GALLERY
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.type.blog',
                        Coverage::TYPE_BLOG
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.type.tweet',
                        Coverage::TYPE_TWEET
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.type.video',
                        Coverage::TYPE_VIDEO
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.type.talk',
                        Coverage::TYPE_TALK
                    ],
                ],
                'size'       => 1,
                'maxitems'   => 1,
                'eval'       => 'required'
            ],
        ],
        'url'              => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.url',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'author'           => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.author',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_twevents_domain_model_person',
                'minitems'      => 0,
                'maxitems'      => 1,
                'items'         => [['---', null]]
            ],
        ],
        'author_name'      => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_coverage.author_name',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'event'            => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'presentation'     => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
