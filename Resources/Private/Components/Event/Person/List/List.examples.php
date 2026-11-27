<?php

/**
 * Examples of a list of people
 *
 * The records come from the fixtures of the tests, "Frontend.csv" and "Components.csv".
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
    'examples' => [
        'default' => [
            'label' => 'Default',
            'arguments' => [
                'people' => [['record' => 'fe_users', 'uid' => 1], ['record' => 'fe_users', 'uid' => 2]],
                'details' => ['texts' => ['summary']],
            ],
        ],
        'anchors' => [
            'label' => 'With anchors',
            'group' => 'Variants',
            'arguments' => [
                'people' => [['record' => 'fe_users', 'uid' => 1], ['record' => 'fe_users', 'uid' => 2]],
                'class' => 'p-performer',
                'anchors' => true,
            ],
        ],
    ],
];
