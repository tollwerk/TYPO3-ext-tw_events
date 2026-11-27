<?php

/**
 * Addresses of records on a site with several languages
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tests\Functional;

use Doctrine\DBAL\Exception as DBALException;
use InvalidArgumentException;
use Override;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use Tollwerk\TwEvents\Page\RecordUris;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use UnexpectedValueException;

/**
 * Addresses of records on a site with several languages
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class RecordUrisTest extends FunctionalTestCase
{
    /**
     * System extensions tw_base needs
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin', 'rte_ckeditor'];

    /**
     * Extensions of the project
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $testExtensionsToLoad = [
        'tollwerk/tw-base',
        'tollwerk/tw-fields',
        'tollwerk/tw-antibot',
        'tollwerk/tw-accounts',
        'tollwerk/tw-events',
    ];

    /**
     * Set up the records, and a site in English and in German at "/de/" with the routes of tw-events
     *
     * @return void
     *
     * @throws RuntimeException If the folder of the site configuration cannot be created
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Frontend.csv');
        $sites = $this->instancePath . '/typo3conf/sites/events';
        GeneralUtility::mkdir_deep($sites);
        file_put_contents($sites . '/config.yaml', <<<'YAML'
            rootPageId: 1
            base: 'http://localhost/'
            dependencies:
              - tollwerk/tw-events
            languages:
              - title: English
                languageId: 0
                base: /
                locale: en_US.UTF-8
              - title: German
                languageId: 1
                base: /de/
                locale: de_DE.UTF-8
                fallbackType: fallback
                fallbacks: '0'
            YAML);
    }

    /**
     * Records link the pages in the language of the request, or in the language the caller names
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws SiteNotFoundException If the site is missing
     * @throws DBALException If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If the request is invalid
     * @throws UnexpectedValueException If a page cannot be read
     */
    public function testAddressesAreInTheLanguageOfThePage(): void
    {
        $site = $this->get(SiteFinder::class)->getSiteByIdentifier('events');
        self::assertInstanceOf(Site::class, $site);
        $german = $site->getLanguageById(1);
        $request = new ServerRequest('http://localhost/de/event')
            ->withAttribute('site', $site)
            ->withAttribute('language', $german);
        $event = $this->get(EventRepository::class)->findByUid(1);
        self::assertInstanceOf(Event::class, $event);
        $uris = $this->get(RecordUris::class);

        self::assertSame('http://localhost/de/event/meetup-8', $uris->event($request, $event));
        self::assertSame('http://localhost/de/event/summit-2099', $uris->eventByUid($site, 2, 5, $german));
        self::assertSame('http://localhost/de/people/ada-lovelace', $uris->personByUid($site, 1, $german));
        self::assertSame('http://localhost/people/ada-lovelace', $uris->personByUid($site, 1));
    }
}
