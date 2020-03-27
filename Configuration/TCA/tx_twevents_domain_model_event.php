<?php

return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event',
        'label'                    => 'name',
        'label_alt'                => 'event_start',
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
        'searchFields'             => 'name,description,location_description,ticket_description,ticket_url,facebook,colloq,hashtag,livestream_url,livestream_embed',
        'iconfile'                 => 'EXT:tw_events/Resources/Public/Icons/Event.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, event_start, event_end, description, location_description, ticket_description, ticket_url, facebook, colloq, hashtag, livestream_url, livestream_embed, downloads, location, sponsors, organizers, presentations, coverage',
    ],
    'types'     => [
        '1' => [
            'showitem' => '
        --palette--;;namedate,
        --palette--;;statusattendance,
        --palette--;;slugpage,
        location,
        organizers,
        sponsors,
        --palette--;;urls,
        --palette--;;livestream,
        --div--;LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tabs.descriptions,
        summary,
        description,
        location_description,
        ticket_description,
        --div--;LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tabs.schedule,
        presentations,
        --div--;LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tabs.coverage,
        downloads,
        coverage,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden,
        sys_language_uid, l10n_parent, l10n_diffsource
        '
        ],
    ],
    'palettes'  => [
        'namedate'         => ['showitem' => 'name, event_start, event_end', 'canNotCollapse' => true],
        'slugpage'         => ['showitem' => 'slug,page', 'canNotCollapse' => true],
        'statusattendance' => ['showitem' => 'status,attendance_mode', 'canNotCollapse' => true],
        'urls'             => ['showitem'       => 'website, ticket_url, facebook, colloq, hashtag',
                               'canNotCollapse' => true
        ],
        'livestream'       => [
            'showitem'       => 'livestream_embed, livestream_url, livestream_start, livestream_end',
            'canNotCollapse' => true
        ],
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
                'foreign_table'       => 'tx_twevents_domain_model_event',
                'foreign_table_where' => 'AND {#tx_twevents_domain_model_event}.{#pid}=###CURRENT_PID### AND {#tx_twevents_domain_model_event}.{#sys_language_uid} IN (-1,0)',
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

        'name'                 => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.name',
            'config'  => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required'
            ],
        ],
        'slug'                 => [
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.slug',
            'exclude' => false,
            'config'  => [
                'type'              => 'slug',
                'generatorOptions'  => [
                    'fields'               => ['name'],
                    'fieldSeparator'       => '-',
                    'replacements'         => [
                        '/' => '-'
                    ],
                    'prefixParentPageSlug' => false,
                ],
                'prependSlash'      => false,
                'fallbackCharacter' => '-',
                'eval'              => 'uniqueInSite',
            ],
        ],
        'event_start'          => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.event_start',
            'config'  => [
                'dbType'     => 'datetime',
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 12,
                'eval'       => 'datetime',
                'default'    => null,
            ],
        ],
        'event_end'            => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.event_end',
            'config'  => [
                'dbType'     => 'datetime',
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 12,
                'eval'       => 'datetime',
                'default'    => null,
            ],
        ],
        'attendance_mode'      => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.attendance_mode',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'size'       => 1,
                'minitems'   => 0,
                'maxitems'   => 1,
                'items'      => [
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.attendance_mode.offline',
                        0
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.attendance_mode.online',
                        1
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.attendance_mode.mixed',
                        2
                    ],
                ]
            ],
        ],
        'status'               => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.status',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'size'       => 1,
                'minitems'   => 0,
                'maxitems'   => 1,
                'items'      => [
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.status.scheduled',
                        0
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.status.rescheduled',
                        1
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.status.postponed',
                        2
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.status.movedOnline',
                        3
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.status.cancelled',
                        4
                    ],
                ]
            ],
        ],
        'summary'              => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.summary',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'description'          => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.description',
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
                'eval'                  => 'trim,required',
            ],

        ],
        'location_description' => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.location_description',
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
        'ticket_description'   => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.ticket_description',
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
        'ticket_url'           => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.ticket_url',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'website'              => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.website',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'facebook'             => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.facebook',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'colloq'               => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.colloq',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'hashtag'              => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.hashtag',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'livestream_url'       => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.livestream_url',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'livestream_embed'     => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.livestream_embed',
            'config'  => [
                'type' => 'text',
                'size' => 30,
                'rows' => 3,
                'eval' => 'trim'
            ],
        ],
        'livestream_start'     => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.livestream_start',
            'config'  => [
                'dbType'     => 'datetime',
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 12,
                'eval'       => 'datetime',
                'default'    => null,
            ],
        ],
        'livestream_end'       => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.livestream_end',
            'config'  => [
                'dbType'     => 'datetime',
                'type'       => 'input',
                'renderType' => 'inputDateTime',
                'size'       => 12,
                'eval'       => 'datetime',
                'default'    => null,
            ],
        ],
        'downloads'            => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.downloads',
            'config'  =>
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                    'downloads',
                    [
                        'appearance'    => [
                            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
                            'fileUploadAllowed'          => false
                        ],
                        'foreign_types' => [
                            '0'                                                 => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT        => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE       => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO       => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO       => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ]
                        ],
                        'maxitems'      => 99
                    ],
                    'pdf'
                ),

        ],
        'location'             => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.location',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_twevents_domain_model_organization',
                'size'          => 1,
                'minitems'      => 0,
                'maxitems'      => 1,
                'items'         => [
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.location.tba',
                        0
                    ]
                ]
            ],
        ],
        'sponsors'             => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.sponsors',
            'config'  => [
                'type'           => 'inline',
                'foreign_table'  => 'tx_twevents_domain_model_sponsor',
                'foreign_sortby' => 'sorting',
                'foreign_field'  => 'event',
                'maxitems'       => 9999,
                'appearance'     => [
                    'collapseAll'                     => 1,
                    'expandSingle'                    => 1,
                    'levelLinksPosition'              => 'top',
                    'showSynchronizationLink'         => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink'         => 1
                ],
            ],
        ],
        'organizers'           => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.organizers',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_twevents_domain_model_person',
                'MM'            => 'tx_twevents_event_person_mm',
                'size'          => 3,
                'autoSizeMax'   => 5,
                'maxitems'      => 10,
                'multiple'      => 0,
                'fieldControl'  => [
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
        'presentations'        => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.presentations',
            'config'  => [
                'type'                   => 'inline',
                'foreign_table'          => 'tx_twevents_domain_model_presentation',
                'foreign_field'          => 'event',
                'foreign_default_sortby' => 'tx_twevents_domain_model_presentation.start ASC',
                'maxitems'               => 9999,
                'appearance'             => [
                    'collapseAll'                     => 1,
                    'expandSingle'                    => 1,
                    'levelLinksPosition'              => 'top',
                    'showSynchronizationLink'         => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink'         => 1
                ],
            ],

        ],
        'coverage'             => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.coverage',
            'config'  => [
                'type'           => 'inline',
                'foreign_table'  => 'tx_twevents_domain_model_coverage',
                'foreign_field'  => 'event',
                'foreign_sortby' => 'sorting',
                'maxitems'       => 9999,
                'appearance'     => [
                    'collapseAll'                     => 1,
                    'expandSingle'                    => 1,
                    'levelLinksPosition'              => 'top',
                    'showSynchronizationLink'         => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink'         => 1,
                    'useSortable'                     => true
                ],
            ],
        ],
        'page'                 => [
            'exclude'   => true,
            'l10n_mode' => 'prefixLangTitle',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_event.page',
            'config'    => [
                'type'           => 'group',
                'internal_type'  => 'db',
                'allowed'        => 'pages',
                'size'           => 1,
                'maxitems'       => 1,
                'minitems'       => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'nav_title, alias, url',
                        'addWhere'               => ' AND pages.doktype = '.\Tollwerk\TwEvents\Domain\Model\Event::DOKTYPE
                    ]
                ],
            ]
        ],
    ],
];
