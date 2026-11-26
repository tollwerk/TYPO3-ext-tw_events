<?php

/**
 * TCA of coverage, which documents an event or a session afterwards
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
        'title' => 'tw_events.db:tx_twevents_coverage',
        'label' => 'title',
        'label_alt' => 'type, url',
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
            'default' => 'actions-camera',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;kind, url, --palette--;;author,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'palettes' => [
        'kind' => ['showitem' => 'type, title'],
        'author' => ['showitem' => 'author, author_name'],
    ],
    'columns' => [
        'event' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'session' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'type' => [
            'label' => 'tw_events.db:tx_twevents_coverage.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'tw_events.db:tx_twevents_coverage.type.blog', 'value' => 'blog'],
                    ['label' => 'tw_events.db:tx_twevents_coverage.type.gallery', 'value' => 'gallery'],
                    ['label' => 'tw_events.db:tx_twevents_coverage.type.video', 'value' => 'video'],
                    ['label' => 'tw_events.db:tx_twevents_coverage.type.talk', 'value' => 'talk'],
                    ['label' => 'tw_events.db:tx_twevents_coverage.type.post', 'value' => 'post'],
                ],
                'default' => 'blog',
            ],
        ],
        'title' => [
            'label' => 'tw_events.db:tx_twevents_coverage.title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'url' => [
            'label' => 'tw_events.db:tx_twevents_coverage.url',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url', 'file'],
                'required' => true,
            ],
        ],
        'author' => [
            'label' => 'tw_events.db:author',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'default' => 0,
            ],
        ],
        'author_name' => [
            'label' => 'tw_events.db:author_name',
            'description' => 'tw_events.db:author_name.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
    ],
];
