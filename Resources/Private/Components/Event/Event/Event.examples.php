<?php

/**
 * Examples of an event with its parts
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
        'past' => [
            'label' => 'Past event',
            'arguments' => [
                'event' => ['record' => 'tx_twevents_event', 'uid' => 1],
                'parts' => ['basics', 'description', 'schedule', 'speakers', 'organizers', 'participations', 'location', 'coverage'],
                'hasPart' => ['location' => 1],
            ],
        ],
        'upcoming' => [
            'label' => 'Upcoming event',
            'group' => 'States',
            'arguments' => [
                'event' => ['record' => 'tx_twevents_event', 'uid' => 3],
                'parts' => ['basics', 'tickets', 'livestream', 'description', 'location', 'downloads'],
                'hasPart' => ['location' => 1],
            ],
        ],
    ],
];
