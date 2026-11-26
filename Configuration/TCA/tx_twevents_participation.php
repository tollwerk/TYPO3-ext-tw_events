<?php

/**
 * TCA of the part an organisation plays in an event, such as sponsor or supporter
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'tw_events.db:tx_twevents_participation',
        'label' => 'organization',
        'label_alt' => 'role, label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'hideTable' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'actions-bookmark',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    organization, --palette--;;role,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'palettes' => [
        'role' => ['showitem' => 'role, label'],
    ],
    'columns' => [
        // The parent event; a relation instead of passthrough, so the record knows its event in the frontend
        'event' => [
            'label' => 'tw_events.db:tx_twevents_participation.event',
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
        'organization' => [
            'label' => 'tw_events.db:tx_twevents_participation.organization',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_twevents_organization',
                'required' => true,
                'minitems' => 1,
            ],
        ],
        'role' => [
            'label' => 'tw_events.db:tx_twevents_participation.role',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'tw_events.db:tx_twevents_participation.role.initiator', 'value' => 'initiator'],
                    ['label' => 'tw_events.db:tx_twevents_participation.role.sponsor', 'value' => 'sponsor'],
                    ['label' => 'tw_events.db:tx_twevents_participation.role.supporter', 'value' => 'supporter'],
                ],
                'default' => 'sponsor',
            ],
        ],
        'label' => [
            'label' => 'tw_events.db:tx_twevents_participation.label',
            'description' => 'tw_events.db:tx_twevents_participation.label.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
    ],
];
