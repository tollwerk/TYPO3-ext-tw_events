<?php
return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person',
        'label'                    => 'family_name',
        'label_alt'                => 'given_name',
        'label_alt_force'          => 'true',
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
        'searchFields'             => 'given_name,family_name,description,website,twitter,facebook,email,phone',
        'iconfile'                 => 'EXT:tw_events/Resources/Public/Icons/Person.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, given_name, family_name, photo, description',
    ],
    'types'     => [
        '1' => [
            'showitem' => '--palette--;;name,
                slug,
                summary,
                biography,
                teaser,
                photo,
                --palette--;;profiles,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                hidden,
                sys_language_uid, l10n_parent, l10n_diffsource'
        ],
    ],
    'palettes'  => [
        'name'     => ['showitem' => 'given_name, family_name', 'canNotCollapse' => true],
        'profiles' => ['showitem' => 'website, twitter, facebook, email, phone', 'canNotCollapse' => true],
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
                'foreign_table'       => 'tx_twevents_domain_model_person',
                'foreign_table_where' => 'AND {#tx_twevents_domain_model_person}.{#pid}=###CURRENT_PID### AND {#tx_twevents_domain_model_person}.{#sys_language_uid} IN (-1,0)',
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

        'given_name'  => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.given_name',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'family_name' => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.family_name',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'slug'        => [
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.slug',
            'exclude' => false,
            'config'  => [
                'type'              => 'slug',
                'generatorOptions'  => [
                    'fields'               => ['given_name', 'family_name'],
                    'fieldSeparator'       => '-',
                    'prefixParentPageSlug' => false,
                ],
                'fallbackCharacter' => '-',
                'eval'              => 'uniqueInSite',
            ],
        ],
        'photo'       => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.photo',
            'config'    => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'photo',
                [
                    'appearance'    => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference',
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
                    'maxitems'      => 1,
                    'minitems'      => 1
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'summary'     => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.summary',
            'config'    => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim'
            ]
        ],
        'biography'   => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.biography',
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
        'teaser'      => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.teaser',
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
        'website'     => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.website',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'twitter'     => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.twitter',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'facebook'    => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.facebook',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email'       => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.email',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email'
            ]
        ],
        'phone'       => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_person.phone',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],

    ],
];
