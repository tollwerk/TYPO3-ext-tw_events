<?php

/**
 * TCA of event series, which group recurring events for filtering
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
        'title' => 'tw_events.db:tx_twevents_series',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'title',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'actions-calendar-alternative',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    title, slug,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'columns' => [
        'title' => [
            'label' => 'tw_events.db:tx_twevents_series.title',
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
    ],
];
