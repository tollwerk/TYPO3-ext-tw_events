<?php

/**
 * Examples of the start and end of an event or a session
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
        'sameDay' => [
            'label' => 'On one day',
            'group' => 'Variants',
            'arguments' => [
                'start' => new DateTimeImmutable('2019-05-15 18:30', new DateTimeZone('Europe/Berlin')),
                'end' => new DateTimeImmutable('2019-05-15 22:00', new DateTimeZone('Europe/Berlin')),
            ],
        ],
        'severalDays' => [
            'label' => 'Over several days',
            'group' => 'Variants',
            'arguments' => [
                'start' => new DateTimeImmutable('2099-06-01 09:00', new DateTimeZone('Europe/Berlin')),
                'end' => new DateTimeImmutable('2099-06-02 18:00', new DateTimeZone('Europe/Berlin')),
            ],
        ],
        'timeOnly' => [
            'label' => 'Times only',
            'group' => 'Variants',
            'arguments' => [
                'start' => new DateTimeImmutable('2019-05-15 19:00', new DateTimeZone('Europe/Berlin')),
                'end' => new DateTimeImmutable('2019-05-15 19:30', new DateTimeZone('Europe/Berlin')),
                'timeOnly' => true,
            ],
        ],
        'startOnly' => [
            'label' => 'Without an end',
            'group' => 'States',
            'arguments' => [
                'start' => new DateTimeImmutable('2019-05-15 18:30', new DateTimeZone('Europe/Berlin')),
            ],
        ],
    ],
];
