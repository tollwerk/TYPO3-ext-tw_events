<?php

use Tollwerk\TwEvents\Domain\Model\Sponsor;

return [
    'ctrl'      => [
        'title'         => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor',
        'label'         => 'organization',
        'tstamp'        => 'tstamp',
        'crdate'        => 'crdate',
        'cruser_id'     => 'cruser_id',
        'delete'        => 'deleted',
        'enablecolumns' => [
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
        ],
        'searchFields'  => '',
        'iconfile'      => 'EXT:tw_events/Resources/Public/Icons/Sponsor.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, organization',
    ],
    'types'     => [
        '1' => [
            'showitem' => '
            --palette--;;orgtype,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
        sys_language_uid, l10n_parent, l10n_diffsource, hidden, 
        starttime, endtime'
        ],
    ],
    'palettes'  => [
        'orgtype' => ['showitem' => 'label,organization,type', 'canNotCollapse' => true],
    ],
    'columns'   => [
        'sorting'   => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden'    => [
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
        'starttime' => [
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
        'endtime'   => [
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

        'label' => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.label',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],

        'organization' => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.organization',
            'config'  => [
                'type'          => 'select',
                'renderType'    => 'selectSingle',
                'foreign_table' => 'tx_twevents_domain_model_organization',
                'minitems'      => 0,
                'maxitems'      => 1,
            ],
        ],

        'type' => [
            'exclude' => false,
            'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.type',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.type.initiator',
                        Sponsor::TYPE_INITIATOR
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.type.sponsor',
                        Sponsor::TYPE_SPONSOR
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.type.organizer',
                        Sponsor::TYPE_ORGANIZER
                    ],
                    [
                        'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_twevents_domain_model_sponsor.type.supporter',
                        Sponsor::TYPE_SUPPORTER
                    ],
                ],
                'size'       => 1,
                'maxitems'   => 1,
                'eval'       => 'required'
            ],
        ],
    ],
];
