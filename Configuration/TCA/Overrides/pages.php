<?php


if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function($extKey, $table) {
        $eventDoktype                                    = \Tollwerk\TwEvents\Domain\Model\Event::DOKTYPE;
        $GLOBALS['TCA']['pages']['types'][$eventDoktype] = $GLOBALS['TCA']['pages']['types'][1];

        $columns = [
            'tx_twevents_layout_past'    => [
                'exclude' => true,
                'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:pages.tx_twevents_layout.past',
                'config'  => [
                    'type'       => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'items'      => \Tollwerk\TwEvents\Utility\Tca::layoutBlockItems(),
                    'size'       => 10,
                    'default'    => 'basics,description,performers,schedule,tickets,location,coverage,downloads,hosts,sponsors'
                ]
            ],
            'tx_twevents_layout_running' => [
                'exclude' => true,
                'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:pages.tx_twevents_layout.running',
                'config'  => [
                    'type'       => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'items'      => \Tollwerk\TwEvents\Utility\Tca::layoutBlockItems(),
                    'size'       => 10,
                    'default'    => 'basics,live,coverage,description,performers,schedule,tickets,location,downloads,hosts,sponsors'
                ]
            ],
            'tx_twevents_layout_future'  => [
                'exclude' => true,
                'label'   => 'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:pages.tx_twevents_layout.future',
                'config'  => [
                    'type'       => 'select',
                    'renderType' => 'selectMultipleSideBySide',
                    'items'      => \Tollwerk\TwEvents\Utility\Tca::layoutBlockItems(),
                    'size'       => 10,
                    'default'    => 'basics,coverage,description,performers,schedule,location,hosts,sponsors'
                ]
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $columns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'pages',
            'tx_twevents_layout_past, tx_twevents_layout_running, tx_twevents_layout_future',
            $eventDoktype,
            'after:subtitle'
        );

        // Add new page type as possible select item:
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            $table,
            'doktype',
            [
                'LLL:EXT:'.$extKey.'/Resources/Private/Language/locallang_db.xlf:page.event',
                $eventDoktype,
                'EXT:'.$extKey.'/Resources/Public/Icons/Extension/apps-pagetree-page-event.svg'
            ],
            '1',
            'after'
        );

        // Add icon for new page type:
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TCA']['pages'],
            [
                'ctrl' => [
                    'typeicon_classes' => [
                        $eventDoktype => 'apps-pagetree-page-event',
                    ],
                ],
            ]
        );
    },
    'tw_events',
    'pages'
);
