<?php
return [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization',
        'label'                    => 'name',
        'label_alt'                => 'street_address,postal_code,locality,country',
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
        'searchFields'             => 'name,street_address,postal_code,locality,website,twitter,facebook,email,phone,description',
        'iconfile'                 => 'EXT:tw_events/Resources/Public/Icons/Organization.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, name, label, street_address, postal_code, locality, region, country, latitude, longitude, website, twitter, facebook, email, phone, logo, photos, description',
    ],
    'types'     => [
        '1' => [
            'showitem' => '
            --palette--;;name,
            slug,
            description,
            --palette--;;address,
            --palette--;;region,
            --palette--;;geo,
            map_url,
            --palette--;;profiles,
            --div--;LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tabs.images,
            logo, photos, 
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        hidden,
        sys_language_uid, l10n_parent, l10n_diffsource'
        ],
    ],
    'palettes'  => [
        'name'     => ['showitem' => 'name, label', 'canNotCollapse' => true],
        'address'  => ['showitem' => 'street_address, postal_code, locality', 'canNotCollapse' => true],
        'region'   => ['showitem' => 'region, country', 'canNotCollapse' => true],
        'geo'      => ['showitem' => 'latitude, longitude', 'canNotCollapse' => true],
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
                'foreign_table'       => 'tx_twevents_domain_model_organization',
                'foreign_table_where' => 'AND {#tx_twevents_domain_model_organization}.{#pid}=###CURRENT_PID### AND {#tx_twevents_domain_model_organization}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource'  => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden'           => [
            'exclude'   => true,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'    => [
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

        'name'           => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.name',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'slug'           => [
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.slug',
            'exclude' => false,
            'config'  => [
                'type'              => 'slug',
                'generatorOptions'  => [
                    'fields'               => ['name'],
                    'fieldSeparator'       => '-',
                    'prefixParentPageSlug' => false,
                ],
                'fallbackCharacter' => '-',
                'eval'              => 'uniqueInSite',
            ],
        ],
        'label'          => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.label',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'street_address' => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.street_address',
            'config'    => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim,required'
            ]
        ],
        'postal_code'    => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.postal_code',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'locality'       => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.locality',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'region'         => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.region',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'country'        => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.country',
            'config'    => [
                'type'                => 'select',
                'foreign_table'       => 'static_countries',
                'foreign_table_where' => 'static_countries.cn_eu_member = 1 ORDER BY static_countries.cn_short_en',
                'itemsProcFunc'       => 'SJBR\\StaticInfoTables\\Hook\\Backend\\Form\\FormDataProvider\\TcaSelectItemsProcessor->translateCountriesSelector',
                'size'                => 1,
                'minitems'            => 0,
                'maxitems'            => 1
            ]
        ],
        'latitude'       => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.latitude',
            'config'    => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required'
            ]
        ],
        'longitude'      => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.longitude',
            'config'    => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim,required'
            ]
        ],
        'website'        => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.website',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'map_url'        => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.map_url',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'twitter'        => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.twitter',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'facebook'       => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.facebook',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email'          => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.email',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email'
            ]
        ],
        'phone'          => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.phone',
            'config'    => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'logo'           => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.logo',
            'config'    => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'logo',
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
                'svg'
            ),
        ],
        'photos'         => [
            'exclude'   => false,
            'l10n_mode' => 'exclude',
            'label'     => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.photos',
            'config'    =>
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                    'photos',
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
                        'maxitems'      => 10
                    ],
                    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                ),

        ],
        'description'    => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_organization.description',
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

    ],
];
