<?php

/**
 * TCA of the sessions of an event
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

return [
    'ctrl' => [
        'title' => 'tw_events.db:tx_twevents_session',
        'label' => 'title',
        'label_alt' => 'start',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'start ASC, title ASC',
        'hideTable' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'actions-microphone',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;name, --palette--;;schedule, --palette--;;address, speakers, summary, description,
                --div--;tw_events.db:tabs.links_coverage,
                    video, notes, coverage,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'palettes' => [
        'name' => ['showitem' => 'title, type, language'],
        'schedule' => ['showitem' => 'start, duration'],
        'address' => ['showitem' => 'slug, hashtag'],
    ],
    'columns' => [
        // The parent event; a relation instead of passthrough, so the record knows its event in the frontend
        'event' => [
            'label' => 'tw_events.db:tx_twevents_session.event',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_twevents_event',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'default' => 0,
                'readOnly' => true,
            ],
        ],
        'title' => [
            'label' => 'tw_events.db:tx_twevents_session.title',
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
                // Title and start, such as "exclusive-design-2019-11-16-1500"; the start comes from the database as
                // "2019-11-16 15:00:00" and from the form as "2019-11-16T15:00:00". Imported sessions keep the
                // addresses of the legacy website, which put the time before the date.
                'generatorOptions' => [
                    'fields' => ['title', 'start'],
                    'fieldSeparator' => '-',
                    'regexReplacements' => [
                        '/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})'
                        . '(?::\d{2})?(?:Z|[+-]\d{2}:?\d{2})?$/' => '$1-$2-$3-$4$5',
                    ],
                    'replacements' => ['/' => '-'],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'unique',
            ],
        ],
        'type' => [
            'label' => 'tw_events.db:tx_twevents_session.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'tw_events.db:tx_twevents_session.type.talk', 'value' => 'talk'],
                    ['label' => 'tw_events.db:tx_twevents_session.type.session', 'value' => 'session'],
                    ['label' => 'tw_events.db:tx_twevents_session.type.workshop', 'value' => 'workshop'],
                    ['label' => 'tw_events.db:tx_twevents_session.type.panel', 'value' => 'panel'],
                    ['label' => 'tw_events.db:tx_twevents_session.type.break', 'value' => 'break'],
                    ['label' => 'tw_events.db:tx_twevents_session.type.other', 'value' => 'other'],
                ],
                'default' => 'talk',
            ],
        ],
        'language' => [
            'label' => 'tw_events.db:tx_twevents_session.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'tw_events.db:tx_twevents_session.language.event', 'value' => ''],
                ],
                'itemsProcFunc' => LanguageItems::class . '->addItems',
                'default' => '',
            ],
        ],
        'start' => [
            'label' => 'tw_events.db:tx_twevents_session.start',
            'description' => 'tw_events.db:local_time',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'nullable' => true,
            ],
        ],
        'duration' => [
            'label' => 'tw_events.db:tx_twevents_session.duration',
            'description' => 'tw_events.db:tx_twevents_session.duration.description',
            'config' => [
                'type' => 'number',
                'range' => [
                    'lower' => 0,
                ],
                'default' => 0,
            ],
        ],
        'speakers' => [
            'label' => 'tw_events.db:tx_twevents_session.speakers',
            'description' => 'tw_events.db:tx_twevents_session.speakers.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_twevents_session_speaker_mm',
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
        'hashtag' => [
            'label' => 'tw_events.db:tx_twevents_session.hashtag',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 100,
            ],
        ],
        'video' => [
            'label' => 'tw_events.db:tx_twevents_session.video',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'notes' => [
            'label' => 'tw_events.db:tx_twevents_session.notes',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_twevents_note',
                'foreign_field' => 'session',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                ],
            ],
        ],
        'coverage' => [
            'label' => 'tw_events.db:coverage',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_twevents_coverage',
                'foreign_field' => 'session',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => true,
                ],
            ],
        ],
    ],
];
