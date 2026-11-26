<?php

/**
 * Times of events, which are stored as local times of their time zone
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Domain;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Times of events, which are stored as local times of their time zone
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class LocalTime
{
    /**
     * Time zone of events that name none or an unknown one
     *
     * @var string
     */
    public const string DEFAULT_TIMEZONE = 'Europe/Berlin';

    /**
     * A stored time in the time zone of its event
     *
     * The database holds the wall-clock time of the event without zone, so the time is read as it is and placed in
     * the zone of the event.
     *
     * @param DateTime|null $time     Stored time
     * @param string        $timezone Time zone of the event
     *
     * @return DateTimeImmutable|null Time, or NULL without one
     */
    public static function of(?DateTime $time, string $timezone): ?DateTimeImmutable
    {
        if (!$time instanceof DateTime) {
            return null;
        }

        $format = 'Y-m-d H:i:s';
        $local = DateTimeImmutable::createFromFormat($format, $time->format($format), self::zone($timezone));

        return ($local === false) ? null : $local;
    }

    /**
     * A time as the database stores it: its wall-clock time in its own time zone, without zone
     *
     * TYPO3 writes times to the database in the time zone of the server, so the wall-clock time is placed there.
     *
     * @param DateTimeImmutable $time Time in the time zone of its event
     *
     * @return DateTime Time to compare with stored times
     */
    public static function stored(DateTimeImmutable $time): DateTime
    {
        $format = 'Y-m-d H:i:s';
        $stored = DateTime::createFromFormat($format, $time->format($format));

        return ($stored === false) ? DateTime::createFromImmutable($time) : $stored;
    }

    /**
     * A time zone, or the default for an unknown one
     *
     * @param string $timezone Identifier of the time zone
     *
     * @return DateTimeZone
     */
    public static function zone(string $timezone): DateTimeZone
    {
        $zone = \in_array($timezone, DateTimeZone::listIdentifiers(), true) ? timezone_open($timezone) : false;

        return ($zone instanceof DateTimeZone) ? $zone : new DateTimeZone(self::DEFAULT_TIMEZONE);
    }
}
