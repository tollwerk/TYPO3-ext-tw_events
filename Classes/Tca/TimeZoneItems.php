<?php

/**
 * Time zones to choose from for an event
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tca;

use DateTimeZone;

/**
 * Time zones to choose from for an event
 *
 * The start and end of an event are local times at the event; its IANA time zone makes them unambiguous. Neither
 * TYPO3 nor PHP offers the time zones as TCA items, so this adds them to the select field.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class TimeZoneItems
{
    /**
     * Add every IANA time zone to the items of the field
     *
     * @param array{items: list<array{label: string, value: string}>} $params Parameters of the items processor
     *
     * @return void
     */
    public function addItems(array &$params): void
    {
        foreach (DateTimeZone::listIdentifiers() as $timeZone) {
            $params['items'][] = ['label' => $timeZone, 'value' => $timeZone];
        }
    }
}
