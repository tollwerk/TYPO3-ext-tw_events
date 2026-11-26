<?php

/**
 * Tests of the pages that the plugins of tw-events render with their default templates
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
use Dom\HTMLDocument;
use InvalidArgumentException;
use Override;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * The plugins render events, sessions, people and organisations in a site without templates of its own
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class FrontendTest extends FunctionalTestCase
{
    /**
     * Extensions of the core to load
     *
     * @var array<non-empty-string>
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin', 'fluid_styled_content'];

    /**
     * Extensions to load
     *
     * @var array<non-empty-string>
     */
    #[Override]
    protected array $testExtensionsToLoad = ['tollwerk/tw-base', 'tollwerk/tw-accounts', 'tollwerk/tw-events'];

    /**
     * Set up the records and a site that renders the content of its pages with the sets of tw-events
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
              - typo3/fluid-styled-content
              - tollwerk/tw-events
            languages:
              - title: English
                languageId: 0
                base: /
                locale: en_US.UTF-8
            YAML);
        // Plugins render as content elements, whose TypoScript fluid_styled_content brings
        file_put_contents($sites . '/setup.typoscript', "page = PAGE\npage.10 < styles.content.get\n");
    }

    /**
     * The list shows every event, and the event shows its programme, hosts and sponsors
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testEvents(): void
    {
        $list = $this->page('/event');
        self::assertStringContainsString('Meetup #8', $list->body->textContent ?? '');
        self::assertStringContainsString('Summit 2099', $list->body->textContent ?? '');

        $event = $this->page('/event/meetup-8');
        $text = $event->body->textContent ?? '';
        foreach (['Accessible forms', 'Ada Lovelace', 'tollwerk', 'Recap'] as $expected) {
            self::assertStringContainsString($expected, $text);
        }

        self::assertSame('Meetup #8', $this->itemName($event));
        self::assertNotNull($event->querySelector('script[type="application/ld+json"]'));
    }

    /**
     * A session shows its speakers, notes and coverage; a session of another event is not found
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testSession(): void
    {
        $session = $this->page('/event/meetup-8/accessible-forms');
        self::assertSame('Accessible forms', $this->itemName($session));
        $text = $session->body->textContent ?? '';
        foreach (['Meetup #8', 'Grace Hopper', 'Use labels.', 'Slides'] as $expected) {
            self::assertStringContainsString($expected, $text);
        }

        self::assertSame(404, $this->request('/event/summit/accessible-forms')->getStatusCode());
    }

    /**
     * The list shows the public people, and a profile shows the events and sessions of the person
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testPeople(): void
    {
        $text = $this->page('/people')->body->textContent ?? '';
        self::assertStringContainsString('Ada Lovelace', $text);
        self::assertStringContainsString('Grace Hopper', $text);

        self::assertStringContainsString('Meetup #8', $this->page('/people/ada-lovelace')->body->textContent ?? '');
        $grace = $this->page('/people/grace-hopper')->body->textContent ?? '';
        self::assertStringContainsString('Accessible forms', $grace);
    }

    /**
     * Without events or sessions, a person with a private profile has no page
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     */
    public function testPrivateProfile(): void
    {
        $this->getConnectionPool()->getConnectionForTable('fe_users')
            ->update('fe_users', ['tx_twaccounts_public' => 0], ['uid' => 1]);
        $this->getConnectionPool()->getConnectionForTable('tx_twevents_event')
            ->update('tx_twevents_event', ['hidden' => 1], ['uid' => 1]);

        self::assertSame(404, $this->request('/people/ada-lovelace')->getStatusCode());
        self::assertStringNotContainsString('Ada Lovelace', $this->page('/people')->body->textContent ?? '');
    }

    /**
     * The list shows the organisations, and a profile shows the events the organisation took part in
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testOrganizations(): void
    {
        self::assertStringContainsString('tollwerk', $this->page('/organizations')->body->textContent ?? '');
        $text = $this->page('/organizations/tollwerk')->body->textContent ?? '';
        self::assertStringContainsString('Meetup #8', $text);
    }

    /**
     * Chosen people and organisations appear in their order, beside a list of cancelled events
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testChosenRecords(): void
    {
        $text = $this->page('/initiative')->body->textContent ?? '';
        self::assertLessThan(strpos($text, 'Ada Lovelace'), strpos($text, 'Grace Hopper'));
        self::assertStringContainsString('tollwerk', $text);
        self::assertStringContainsString('Summit 2099', $text);
    }

    /**
     * An event on a page of its own shows the parts the plugin chooses
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testEventOnAPageOfItsOwn(): void
    {
        $event = $this->page('/event/summit-2099');
        self::assertSame('Summit 2099', $this->itemName($event));
        self::assertStringContainsString('Opening', $event->body->textContent ?? '');
    }

    /**
     * Name of the event or session that a page shows, from its microformat
     *
     * @param HTMLDocument $page Page
     *
     * @return string|null Name, or null if the page shows none
     */
    private function itemName(HTMLDocument $page): ?string
    {
        return $page->querySelector('article > data.p-name')?->getAttribute('value');
    }

    /**
     * Response to a request
     *
     * @param string $path Path
     *
     * @return ResponseInterface
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    private function request(string $path): ResponseInterface
    {
        return $this->executeFrontendSubRequest(new InternalRequest('http://localhost' . $path));
    }

    /**
     * A page, which must be found
     *
     * @param string $path Path
     *
     * @return HTMLDocument
     *
     * @throws InvalidArgumentException If the request is invalid
     */
    private function page(string $path): HTMLDocument
    {
        $response = $this->request($path);
        self::assertSame(200, $response->getStatusCode(), $path . ': ' . substr((string) $response->getBody(), 0, 500));

        return HTMLDocument::createFromString((string) $response->getBody(), LIBXML_NOERROR);
    }
}
