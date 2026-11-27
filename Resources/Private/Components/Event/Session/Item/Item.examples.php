<?php

/**
 * Examples of a session in the programme of an event
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
        'talk' => [
            'label' => 'Talk in another language',
            'arguments' => [
                'session' => ['record' => 'tx_twevents_session', 'uid' => 1],
            ],
        ],
        'break' => [
            'label' => 'Break',
            'group' => 'Variants',
            'arguments' => [
                'session' => ['record' => 'tx_twevents_session', 'uid' => 2],
            ],
        ],
    ],
];
