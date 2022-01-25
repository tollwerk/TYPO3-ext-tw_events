<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function() {

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'tw_events',
            'Configuration/TypoScript/Static',
            'Events'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
            'tw_events',
            'Configuration/TypoScript/Main/TSconfig/page.tsconfig',
            'Events: Page Settings'
        );

        ExtensionUtility::registerPlugin(
            'Tollwerk.TwEvents',
            'Events',
            'Events',
            'EXT:tw_events/Resources/Public/Icons/Event.svg'
        );
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['twevents_events'] = 'pi_flexform';
        ExtensionManagementUtility::addPiFlexFormValue(
            'twevents_events',
            'FILE:EXT:tw_events/Configuration/FlexForms/Events.xml'
        );
        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Event',
            'Event detail',
            'EXT:tw_events/Resources/Public/Icons/Event.svg'
        );
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['twevents_event'] = 'pi_flexform';
        ExtensionManagementUtility::addPiFlexFormValue(
            'twevents_event',
            'FILE:EXT:tw_events/Configuration/FlexForms/Event.xml'
        );

        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Organizations',
            'Organizations',
            'EXT:tw_events/Resources/Public/Icons/Organization.svg'
        );
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['twevents_organizations'] = 'pi_flexform';
        ExtensionManagementUtility::addPiFlexFormValue(
            'twevents_organizations',
            'FILE:EXT:tw_events/Configuration/FlexForms/Organizations.xml'
        );

        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Sponsors',
            'Sponsors',
            'EXT:tw_events/Resources/Public/Icons/Sponsor.svg'
        );

        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Presentations',
            'Presentations',
            'EXT:tw_events/Resources/Public/Icons/Presentation.svg'
        );

        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Coverage',
            'Coverage',
            'EXT:tw_events/Resources/Public/Icons/Coverage.svg'
        );

        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Note',
            'Note',
            'EXT:tw_events/Resources/Public/Icons/Note.svg'
        );

        ExtensionUtility::registerPlugin(
            'TwEvents',
            'Persons',
            'Persons',
            'EXT:tw_events/Resources/Public/Icons/Person.svg'
        );
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['twevents_persons'] = 'pi_flexform';
        ExtensionManagementUtility::addPiFlexFormValue(
            'twevents_persons',
            'FILE:EXT:tw_events/Configuration/FlexForms/Persons.xml'
        );

        ExtensionManagementUtility::addStaticFile('tw_events', 'Configuration/TypoScript', 'tollwerk Event Calendar');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_organization',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_organization.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_organization');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_event',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_event.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_event');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_person',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_person.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_person');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_sponsor',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_sponsor.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_sponsor');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_coverage',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_coverage.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_coverage');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_note',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_note.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_note');

        ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_twevents_domain_model_presentation',
            'EXT:tw_events/Resources/Private/Language/locallang_csh_tx_twevents_domain_model_presentation.xlf'
        );
        ExtensionManagementUtility::allowTableOnStandardPages('tx_twevents_domain_model_presentation');

        // Add the event page doktype
        $eventDoktype                          = \Tollwerk\TwEvents\Domain\Model\Event::DOKTYPE;
        $GLOBALS['PAGES_TYPES'][$eventDoktype] = [
            'type'          => 'web',
            'allowedTables' => '*',
        ];
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList('.$eventDoktype.')'
        );
        // Register icons in icon factory
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        // TODO: Refactor the .svg file because the dimensions aren't right - see any page inside the page tree.
        $iconRegistry->registerIcon(
            'apps-pagetree-page-event',
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:tw_events/Resources/Public/Icons/apps-pagetree-page-event.svg',]
        );
    }
);
