<?php

/**
 * Examples of the profile of an organisation
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
            'label' => 'With parts in events and hosted events',
            'arguments' => [
                'organization' => ['record' => 'tx_twevents_organization', 'uid' => 1],
                'participations' => [['record' => 'tx_twevents_participation', 'uid' => 1]],
                'hostedEvents' => [['record' => 'tx_twevents_event', 'uid' => 1]],
            ],
        ],
        'plain' => [
            'label' => 'Without events',
            'group' => 'States',
            'arguments' => [
                'organization' => ['record' => 'tx_twevents_organization', 'uid' => 1],
            ],
        ],
    ],
];
