<?php

/**
 * Examples of the addresses of a person or an organisation
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
        'person' => [
            'label' => 'Person',
            'arguments' => [
                'record' => ['record' => 'fe_users', 'uid' => 1],
                'contacts' => ['website', 'github', 'instagram'],
            ],
        ],
        'organization' => [
            'label' => 'Organisation',
            'arguments' => [
                'record' => ['record' => 'tx_twevents_organization', 'uid' => 1],
                'contacts' => ['mastodon', 'website'],
            ],
        ],
    ],
];
