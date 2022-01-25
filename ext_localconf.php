<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Events',
            [\Tollwerk\TwEvents\Controller\EventController::class => 'list, show'],
            [\Tollwerk\TwEvents\Controller\EventController::class => '']
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Event',
            [\Tollwerk\TwEvents\Controller\EventController::class => 'block'],
            [\Tollwerk\TwEvents\Controller\EventController::class => '']
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Organizations',
            [\Tollwerk\TwEvents\Controller\OrganizationController::class => 'list, location'],
            [\Tollwerk\TwEvents\Controller\OrganizationController::class => '',]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Sponsors',
            [
                \Tollwerk\TwEvents\Controller\OrganizationController::class => 'list, show',
                \Tollwerk\TwEvents\Controller\EventController::class        => 'list, show',
                \Tollwerk\TwEvents\Controller\PersonController::class       => 'list, show',
                \Tollwerk\TwEvents\Controller\CoverageController::class     => 'list, show',
                \Tollwerk\TwEvents\Controller\PresentationController::class     => 'list, show',
            ],
            // non-cacheable actions
            [
                \Tollwerk\TwEvents\Controller\OrganizationController::class => '',
                \Tollwerk\TwEvents\Controller\EventController::class        => '',
                \Tollwerk\TwEvents\Controller\PersonController::class       => '',
                \Tollwerk\TwEvents\Controller\CoverageController::class     => '',
                \Tollwerk\TwEvents\Controller\PresentationController::class     => '',
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Presentations',
            [\Tollwerk\TwEvents\Controller\PresentationController::class     => 'list, show'],
            [\Tollwerk\TwEvents\Controller\PresentationController::class     => '']
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Coverage',
            [\Tollwerk\TwEvents\Controller\CoverageController::class     => 'list, show'],
            [\Tollwerk\TwEvents\Controller\CoverageController::class     => '']
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Note',
            [
                'Note' => 'list, show',
            ],
            // non-cacheable actions
            [
                'Note' => '',
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'TwEvents',
            'Persons',
            [\Tollwerk\TwEvents\Controller\PersonController::class => 'list, show'],
            [\Tollwerk\TwEvents\Controller\PersonController::class => '']
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    events {
                        iconIdentifier = tw_events-plugin-events
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_events.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_events.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_events
                        }
                    }
                    event {
                        iconIdentifier = tw_events-plugin-events
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_event.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_event.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_event
                        }
                    }
                    organizations {
                        iconIdentifier = tw_events-plugin-organizations
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_organizations.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_organizations.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_organizations
                        }
                    }
                    sponsors {
                        iconIdentifier = tw_events-plugin-sponsors
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_sponsors.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_sponsors.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_sponsors
                        }
                    }
                    presentations {
                        iconIdentifier = tw_events-plugin-presentations
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_presentations.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_presentations.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_presentations
                        }
                    }
                    coverage {
                        iconIdentifier = tw_events-plugin-coverage
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_coverage.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_coverage.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_coverage
                        }
                    }
                    note {
                        iconIdentifier = tw_events-plugin-note
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_note.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_note.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_note
                        }
                    }
                    persons {
                        iconIdentifier = tw_events-plugin-persons
                        title = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_persons.name
                        description = LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:tx_tw_events_persons.description
                        tt_content_defValues {
                            CType = list
                            list_type = twevents_persons
                        }
                    }
                }
                show = *
            }
       }'
        );

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-events',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Event.svg']
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-organizations',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Organization.svg']
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-sponsors',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Sponsor.svg']
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-presentations',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Presentation.svg']
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-coverage',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Coverage.svg']
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-note',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Note.svg']
        );

        $iconRegistry->registerIcon(
            'tw_events-plugin-persons',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/Person.svg']
        );

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['evt']  = ['Tollwerk\\TwEvents\\ViewHelpers'];

        // Treat all date & time values as UTC
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['phpTimeZone'] = 'UTC';
    }
);
