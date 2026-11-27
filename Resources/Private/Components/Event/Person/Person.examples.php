<?php

/**
 * Examples of the profile of a person
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
        'speaker' => [
            'label' => 'Speaker',
            'arguments' => [
                'person' => ['record' => 'fe_users', 'uid' => 2],
                'sessions' => [['record' => 'tx_twevents_session', 'uid' => 1]],
            ],
        ],
        'organizer' => [
            'label' => 'Organiser',
            'group' => 'Variants',
            'arguments' => [
                'person' => ['record' => 'fe_users', 'uid' => 1],
                'organizedEvents' => [['record' => 'tx_twevents_event', 'uid' => 1]],
            ],
        ],
    ],
];
