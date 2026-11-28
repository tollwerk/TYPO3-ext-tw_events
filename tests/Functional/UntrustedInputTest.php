<?php

/**
 * What people and editors enter stays text, and what a person keeps private stays private
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
use Dom\Element;
use Dom\HTMLDocument;
use InvalidArgumentException;
use JsonException;
use Override;
use RuntimeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * What people and editors enter stays text, and what a person keeps private stays private
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class UntrustedInputTest extends FunctionalTestCase
{
    /**
     * Extensions of the core to load
     *
     * @var array<non-empty-string>
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin', 'rte_ckeditor', 'fluid_styled_content'];

    /**
     * Extensions to load
     *
     * @var array<non-empty-string>
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
     * Set up the records and a site whose speakers and sponsors show every kind of address, the mail address too
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
            settings:
              twEvents:
                speakers:
                  contacts: [website, email, twitter]
                sponsors:
                  contacts: [website, phone, email]
            languages:
              - title: English
                languageId: 0
                base: /
                locale: en_US.UTF-8
            YAML);
        file_put_contents($sites . '/setup.typoscript', "page = PAGE\npage.10 < styles.content.get\n");
    }

    /**
     * Quotes and brackets in an address stay in the address, a link goes only to the web, and no mail address of a
     * person appears
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     */
    public function testAddressesOfAProfileStayText(): void
    {
        $twitter = 'https://x.example/"><svg/onload=alert(1)>';
        $this->update('fe_users', 2, [
            'www' => 'javascript:alert(1)',
            'tx_twaccounts_twitter' => $twitter,
            'email' => 'grace@example.org',
        ]);

        $page = $this->page('/event/meetup-8/accessible-forms');
        self::assertNull($page->querySelector('svg'));
        self::assertNull($page->querySelector('a[href^="javascript:"]'));
        self::assertSame($twitter, $page->querySelector('.Contacts__item--twitter a')?->getAttribute('href'));
        self::assertNull($page->querySelector('a[href^="mailto:"]'), 'The mail address of an account is no contact');
        self::assertStringNotContainsString('grace@example.org', $page->saveHtml());
    }

    /**
     * A phone number links its digits and shows as it was entered; an organisation shows its mail address
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     */
    public function testPhoneNumberLinksItsDigits(): void
    {
        $this->update('tx_twevents_organization', 1, [
            'phone' => '+49 911 / 123 456-0',
            'email' => 'hello@example.org',
        ]);

        $page = $this->page('/event/meetup-8');
        $phone = $page->querySelector('.Contacts__item--phone a');
        self::assertInstanceOf(Element::class, $phone);
        self::assertSame('tel:+499111234560', $phone->getAttribute('href'));
        self::assertSame('+49 911 / 123 456-0', $phone->textContent);
        self::assertSame('mailto:hello@example.org', $page->querySelector('a.u-email')?->getAttribute('href'));
    }

    /**
     * A title with markup in it shows as text in a list
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     */
    public function testTitleOfAnEventStaysText(): void
    {
        $this->update('tx_twevents_event', 1, ['title' => 'The <dialog> element & "more"']);

        $list = $this->page('/event');
        self::assertNull($list->querySelector('dialog'));
        self::assertStringContainsString('The <dialog> element & "more"', $list->body->textContent ?? '');
    }

    /**
     * Coverage whose link TYPO3 cannot resolve, such as to a deleted file, is left out instead of an empty link
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     */
    public function testCoverageWithoutAnAddressIsLeftOut(): void
    {
        $this->update('tx_twevents_coverage', 2, ['url' => 't3://file?uid=999']);

        $page = $this->page('/event/meetup-8');
        self::assertNull($page->querySelector('.CoverageList a[href=""]'));
        self::assertStringNotContainsString('Recap', $page->body->textContent ?? '');
    }

    /**
     * Of an author without a public profile, the structured data give only the name
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     * @throws JsonException If the structured data cannot be decoded
     */
    public function testPrivateAuthorIsOnlyAName(): void
    {
        $this->getConnectionPool()->getConnectionForTable('fe_users')->insert('fe_users', [
            'uid' => 3,
            'pid' => 6,
            'username' => 'person-0011223344556677',
            'first_name' => 'Carol',
            'last_name' => 'Shaw',
            'tx_twaccounts_slug' => 'carol-shaw',
            'tx_twaccounts_summary' => 'Private summary',
            'www' => 'https://private.example.org/carol',
        ]);
        $this->update('tx_twevents_coverage', 2, ['author' => 3]);

        $carol = $this->person($this->page('/event/meetup-8'), 'Carol Shaw');
        self::assertSame(['@type', '@id', 'name'], array_keys($carol));
        $id = $carol['@id'] ?? null;
        self::assertStringNotContainsString('/people/', \is_string($id) ? $id : '');
    }

    /**
     * A chosen person without a public profile shows by name, without a link to a profile that is not found
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If a record cannot be changed
     */
    public function testChosenPrivatePersonIsNotLinked(): void
    {
        $this->update('fe_users', 1, ['tx_twaccounts_public' => 0]);
        $this->update('tx_twevents_event', 1, ['hidden' => 1]);

        $page = $this->page('/initiative');
        self::assertStringContainsString('Ada Lovelace', $page->body->textContent ?? '');
        self::assertNull($page->querySelector('a[href*="/people/ada-lovelace"]'));
        self::assertNotNull($page->querySelector('a[href*="/people/grace-hopper"]'), 'A public profile is linked');
    }

    /**
     * Change a record
     *
     * @param string               $table  Table
     * @param integer              $uid    UID
     * @param array<string, mixed> $fields Fields
     *
     * @return void
     *
     * @throws DBALException If the record cannot be changed
     */
    private function update(string $table, int $uid, array $fields): void
    {
        $this->getConnectionPool()->getConnectionForTable($table)->update($table, $fields, ['uid' => $uid]);
    }

    /**
     * A person in the structured data of a page
     *
     * @param HTMLDocument $page Page
     * @param string       $name Name of the person
     *
     * @return array<array-key, mixed> Description of the person
     *
     * @throws JsonException If the structured data cannot be decoded
     */
    private function person(HTMLDocument $page, string $name): array
    {
        $json = (string) $page->querySelector('script[type="application/ld+json"]')?->textContent;
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $graph = \is_array($data) && \is_array($data['@graph'] ?? null) ? $data['@graph'] : [];
        foreach ($graph as $node) {
            if (\is_array($node) && 'Person' === ($node['@type'] ?? null) && $name === ($node['name'] ?? null)) {
                return $node;
            }
        }

        self::fail('No person ' . $name . ' in ' . $json);
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
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost' . $path));
        self::assertSame(200, $response->getStatusCode(), $path . ': ' . substr((string) $response->getBody(), 0, 500));

        return HTMLDocument::createFromString((string) $response->getBody(), LIBXML_NOERROR);
    }
}
