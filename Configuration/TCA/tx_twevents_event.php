<?php

/**
 * TCA of events
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

use Tollwerk\TwEvents\Tca\LanguageItems;
use Tollwerk\TwEvents\Tca\TimeZoneItems;

return [
    'ctrl' => [
        'title' => 'tw_events.db:tx_twevents_event',
        'label' => 'title',
        'label_alt' => 'start',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'start DESC',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'actions-calendar',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;name, --palette--;;address, --palette--;;schedule, --palette--;;state,
                    summary, description,
                --div--;tw_events.db:tabs.location,
                    location, location_description,
                --div--;tw_events.db:tabs.organizers,
                    organizers, participations,
                --div--;tw_events.db:tabs.programme,
                    sessions,
                --div--;tw_events.db:tabs.tickets,
                    --palette--;;tickets,
                --div--;tw_events.db:tabs.links_coverage,
                    page, website, livestream_url, coverage, downloads,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'palettes' => [
        'name' => ['showitem' => 'title, series'],
        'address' => ['showitem' => 'slug, hashtag'],
        'schedule' => ['showitem' => 'start, end, timezone, main_language'],
        'state' => ['showitem' => 'status, attendance_mode'],
        'tickets' => ['showitem' => 'ticket_description, --linebreak--, ticket_url, show_ticket_link'],
    ],
    'columns' => [
        'title' => [
            'label' => 'tw_events.db:tx_twevents_event.title',
            'config' => [
                'type' => 'input',
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'slug' => [
            'label' => 'tw_events.db:slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['title'],
                    'replacements' => ['/' => '-'],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'unique',
            ],
        ],
        'series' => [
            'label' => 'tw_events.db:tx_twevents_event.series',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_twevents_series',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'default' => 0,
            ],
        ],
        'start' => [
            'label' => 'tw_events.db:tx_twevents_event.start',
            'description' => 'tw_events.db:local_time',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'required' => true,
            ],
        ],
        'end' => [
            'label' => 'tw_events.db:tx_twevents_event.end',
            'description' => 'tw_events.db:local_time',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'nullable' => true,
            ],
        ],
        'timezone' => [
            'label' => 'tw_events.db:tx_twevents_event.timezone',
            'description' => 'tw_events.db:tx_twevents_event.timezone.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => TimeZoneItems::class . '->addItems',
                'default' => 'Europe/Berlin',
            ],
        ],
        'main_language' => [
            'label' => 'tw_events.db:tx_twevents_event.main_language',
            'description' => 'tw_events.db:tx_twevents_event.main_language.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => LanguageItems::class . '->addItems',
                'default' => 'en',
            ],
        ],
        'status' => [
            'label' => 'tw_events.db:tx_twevents_event.status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'tw_events.db:tx_twevents_event.status.scheduled', 'value' => 'scheduled'],
                    ['label' => 'tw_events.db:tx_twevents_event.status.rescheduled', 'value' => 'rescheduled'],
                    ['label' => 'tw_events.db:tx_twevents_event.status.postponed', 'value' => 'postponed'],
                    ['label' => 'tw_events.db:tx_twevents_event.status.moved_online', 'value' => 'moved_online'],
                    ['label' => 'tw_events.db:tx_twevents_event.status.cancelled', 'value' => 'cancelled'],
                ],
                'default' => 'scheduled',
            ],
        ],
        'attendance_mode' => [
            'label' => 'tw_events.db:tx_twevents_event.attendance_mode',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'tw_events.db:tx_twevents_event.attendance_mode.offline', 'value' => 'offline'],
                    ['label' => 'tw_events.db:tx_twevents_event.attendance_mode.online', 'value' => 'online'],
                    ['label' => 'tw_events.db:tx_twevents_event.attendance_mode.mixed', 'value' => 'mixed'],
                ],
                'default' => 'offline',
            ],
        ],
        'summary' => [
            'label' => 'tw_events.db:summary',
            'config' => [
                'type' => 'text',
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'label' => 'tw_events.db:description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'location' => [
            'label' => 'tw_events.db:tx_twevents_event.location',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_twevents_organization',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'default' => 0,
            ],
        ],
        'location_description' => [
            'label' => 'tw_events.db:tx_twevents_event.location_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'organizers' => [
            'label' => 'tw_events.db:tx_twevents_event.organizers',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_twevents_event_organizer_mm',
            ],
        ],
        'participations' => [
            'label' => 'tw_events.db:tx_twevents_event.participations',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_twevents_participation',
                'foreign_field' => 'event',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                ],
            ],
        ],
        'sessions' => [
            'label' => 'tw_events.db:tx_twevents_event.sessions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_twevents_session',
                'foreign_field' => 'event',
                'foreign_default_sortby' => 'start ASC, title ASC',
                'appearance' => [
                    'collapseAll' => true,
                ],
            ],
        ],
        'coverage' => [
            'label' => 'tw_events.db:coverage',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_twevents_coverage',
                'foreign_field' => 'event',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                ],
            ],
        ],
        'page' => [
            'label' => 'tw_events.db:tx_twevents_event.page',
            'description' => 'tw_events.db:tx_twevents_event.page.description',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'relationship' => 'manyToOne',
                'maxitems' => 1,
            ],
        ],
        'website' => [
            'label' => 'tw_events.db:website',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'hashtag' => [
            'label' => 'tw_events.db:tx_twevents_event.hashtag',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 100,
            ],
        ],
        'ticket_url' => [
            'label' => 'tw_events.db:tx_twevents_event.ticket_url',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        // Without the link, the ticket information can announce tickets before registration opens
        'show_ticket_link' => [
            'label' => 'tw_events.db:tx_twevents_event.show_ticket_link',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 1,
            ],
        ],
        'ticket_description' => [
            'label' => 'tw_events.db:tx_twevents_event.ticket_description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'livestream_url' => [
            'label' => 'tw_events.db:tx_twevents_event.livestream_url',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'downloads' => [
            'label' => 'tw_events.db:tx_twevents_event.downloads',
            'config' => [
                'type' => 'file',
            ],
        ],
    ],
];
