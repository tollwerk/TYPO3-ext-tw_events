<?php


if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


// Add new columns
$newColumnsTtContent = [
    'tx_twevents_slug'   => [
        'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tt_content.slug',
        'exclude' => false,
        'config'  => [
            'type'              => 'slug',
            'generatorOptions'  => [
                'fields'               => ['header'],
                'fieldSeparator'       => '-',
                'prefixParentPageSlug' => false,
            ],
            'fallbackCharacter' => '-',
        ],
    ],
    'tx_twevents_stages' => [
        'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tt_content.stages',
        'exclude' => false,
        'config'  => [
            'type'    => 'check',
            'items'   => [
                ['LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tt_content.stages.past', 0],
                ['LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tt_content.stages.running', 1],
                ['LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tt_content.stages.future', 2],
            ],
            'default' => 7,
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $newColumnsTtContent);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'tx_twevents_slug, tx_twevents_stages',
    '',
    'after:header'
);

$GLOBALS['TCA']['tx_twevents_domain_model_event']['columns']['categories'] = [
    'config' => [
        'type' => 'category'
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_twevents_domain_model_event',
    'categories'
);
