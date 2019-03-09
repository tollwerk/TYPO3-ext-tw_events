<?php

use Tollwerk\TwEvents\Domain\Model\Presentation;

return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation',
        'label'                    => 'title',
        'label_alt'                => 'performers,start',
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
        'searchFields'             => 'title,summary,description,hashtag',
        'iconfile'                 => 'EXT:tw_events/Resources/Public/Icons/Presentation.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, type, summary, description, hashtag, start, duration, performers, coverage',
    ],
    'types'     => [
        '1' => [
            'showitem' => '
        --palette--;;titletype,
        slug,
        --palette--;;duration,
        performers, 
        summary,
        description,
        --div--;LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tabs.coverage,
        coverage,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden,
        sys_language_uid, l10n_parent, l10n_diffsource
        '
        ],
    ],
    'palettes'  => [
        'titletype' => ['showitem' => 'title, type, hashtag', 'canNotCollapse' => true],
        'duration'  => ['showitem' => 'start, duration', 'canNotCollapse' => true],
        'profiles'  => ['showitem' => 'website, twitter, facebook, email, phone', 'canNotCollapse' => true],
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
                'foreign_table'       => 'tx_twevents_domain_model_presentation',
                'foreign_table_where' => 'AND {#tx_twevents_domain_model_presentation}.{#pid}=###CURRENT_PID### AND {#tx_twevents_domain_model_presentation}.{#sys_language_uid} IN (-1,0)',
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

        'title'       => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.title',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'slug'        => [
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.slug',
            'exclude' => false,
            'config'  => [
                'type'              => 'slug',
                'generatorOptions'  => [
                    'fields'               => ['title', 'start'],
                    'fieldSeparator'       => '-',
                    'prefixParentPageSlug' => false,
                ],
                'fallbackCharacter' => '-',
                'eval'              => 'uniqueInSite',
            ],
        ],
        'type'        => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.type',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.type.talk',
                        Presentation::TYPE_TALK
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.type.session',
                        Presentation::TYPE_SESSION
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.type.panel',
                        Presentation::TYPE_PANEL
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.type.break',
                        Presentation::TYPE_BREAK
                    ],
                ],
                'size'       => 1,
                'maxitems'   => 1,
                'eval'       => 'required'
            ],
        ],
        'summary'     => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.summary',
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
        'description' => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.description',
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
        'hashtag'     => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.hashtag',
            'config'  => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim'
            ],
        ],
        'start'       => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.start',
            'config'  => [
                'dbType'     => 'datetime',
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 12,
                'eval'       => 'datetime',
                'default'    => null,
            ],
        ],
        'duration'    => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.duration',
            'config'  => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'performers'  => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.performers',
            'config'  => [
                'type'                => 'select',
                'renderType'          => 'selectMultipleSideBySide',
                'foreign_table'       => 'tx_twevents_domain_model_person',
                'foreign_table_where' => 'ORDER BY tx_twevents_domain_model_person.family_name ASC, tx_twevents_domain_model_person.given_name ASC',
                'MM'                  => 'tx_twevents_presentation_person_mm',
                'size'                => 10,
                'autoSizeMax'         => 30,
                'maxitems'            => 9999,
                'multiple'            => 0,
                'fieldControl'        => [
                    'editPopup'  => [
                        'disabled' => false,
                    ],
                    'addRecord'  => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],

        ],
        'coverage'    => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_presentation.coverage',
            'config'  => [
                'type'          => 'inline',
                'foreign_table' => 'tx_twevents_domain_model_coverage',
                'foreign_field' => 'presentation',
                'maxitems'      => 9999,
                'appearance'    => [
                    'collapseAll'                     => 0,
                    'levelLinksPosition'              => 'top',
                    'showSynchronizationLink'         => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink'         => 1
                ],
            ],

        ],

        'event' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
