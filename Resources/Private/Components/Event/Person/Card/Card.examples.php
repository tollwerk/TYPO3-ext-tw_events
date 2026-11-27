<?php

/**
 * Examples of a person as card
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
            'label' => 'Name only',
            'arguments' => [
                'person' => ['record' => 'fe_users', 'uid' => 2],
            ],
        ],
        'details' => [
            'label' => 'With summary and contacts',
            'group' => 'Variants',
            'arguments' => [
                'person' => ['record' => 'fe_users', 'uid' => 1],
                'details' => ['texts' => ['summary'], 'contacts' => ['website', 'github', 'instagram']],
                'class' => 'p-performer',
            ],
        ],
    ],
];
