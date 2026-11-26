<?php

/**
 * Details of the people and organisations of an event by the site settings
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tests\Unit\Page;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Tollwerk\TwEvents\Page\Details;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Settings\Settings;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteSettings;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Details of the people and organisations of an event by the site settings
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class DetailsTest extends UnitTestCase
{
    /**
     * Without settings, the details are those of the legacy website
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     * @throws InvalidArgumentException If the address of the request is invalid
     */
    public function testDefaults(): void
    {
        $details = new Details();

        self::assertSame(Details::PEOPLE['hosts'], $details->of(null, 'hosts'));
        self::assertSame(Details::PEOPLE['speakers'], $details->of($this->request([]), 'speakers'));
        self::assertSame(Details::ORGANIZATIONS['sponsors'], $details->of($this->request([]), 'sponsors'));
        self::assertSame([], $details->of(null, 'audience'));
    }

    /**
     * The settings choose the details in their order, without unknown values and duplicates
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     * @throws InvalidArgumentException If the address of the request is invalid
     */
    public function testSettings(): void
    {
        $request = $this->request([
            'twEvents.hosts.photo' => false,
            'twEvents.hosts.texts' => ['biography', 'unknown', 'summary'],
            'twEvents.hosts.contacts' => [],
            'twEvents.sponsors.address' => false,
            'twEvents.sponsors.contacts' => ['phone', 'website', 'phone'],
        ]);
        $details = new Details();

        self::assertSame(
            ['photo' => false, 'texts' => ['biography', 'summary'], 'contacts' => []],
            $details->of($request, 'hosts'),
        );
        self::assertSame(
            ['logo' => true, 'address' => false, 'contacts' => ['phone', 'website'], 'description' => true],
            $details->of($request, 'sponsors'),
        );
    }

    /**
     * Request for a site with settings
     *
     * @param array<string, mixed> $settings Settings by their full names, as a site with definitions has them
     *
     * @return ServerRequest
     *
     * @throws InvalidArgumentException If the address of the request is invalid
     */
    private function request(array $settings): ServerRequest
    {
        $site = new Site('test', 1, [], SiteSettings::create(new Settings($settings)));

        return new ServerRequest('https://example.org/')->withAttribute('site', $site);
    }
}
