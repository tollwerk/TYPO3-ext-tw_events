<?php

/**
 * TCA of notes on a session
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
        'title' => 'tw_events.db:tx_twevents_note',
        'label' => 'author_name',
        'label_alt' => 'author',
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
            'default' => 'actions-message',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;author, content,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'palettes' => [
        'author' => ['showitem' => 'author, author_name'],
    ],
    'columns' => [
        'session' => [
            'config' => [
                'type' => 'passthrough',
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
        'content' => [
            'label' => 'tw_events.db:tx_twevents_note.content',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'required' => true,
            ],
        ],
    ],
];
