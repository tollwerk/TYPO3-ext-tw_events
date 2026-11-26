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

namespace Tollwerk\TwEvents\Tests\Unit\Tca;

use Tollwerk\TwEvents\Tca\TimeZoneItems;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

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
final class TimeZoneItemsTest extends UnitTestCase
{
    /**
     * The time zones of past events are offered, each with its identifier as label and value
     *
     * @return void
     */
    public function testTimeZonesAreOfferedByTheirIdentifier(): void
    {
        $params = ['items' => []];

        new TimeZoneItems()->addItems($params);

        self::assertContains(['label' => 'Europe/Berlin', 'value' => 'Europe/Berlin'], $params['items']);
        self::assertContains(['label' => 'Europe/Helsinki', 'value' => 'Europe/Helsinki'], $params['items']);
    }

    /**
     * Items the field configures itself stay in front of the time zones
     *
     * @return void
     */
    public function testConfiguredItemsArePreserved(): void
    {
        $params = ['items' => [['label' => 'UTC', 'value' => 'UTC']]];

        new TimeZoneItems()->addItems($params);

        self::assertSame([['label' => 'UTC', 'value' => 'UTC']], \array_slice($params['items'], 0, 1));
        self::assertGreaterThan(1, \count($params['items']));
    }
}
