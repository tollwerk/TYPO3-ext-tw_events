<?php

/**
 * Tests of the examples of the components
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

use Dom\Element;
use Dom\HTMLDocument;
use InvalidArgumentException;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use Tollwerk\TwBase\Component\Example\ExampleEndpoint;
use Tollwerk\TwBase\Component\Example\Examples;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use UnexpectedValueException;

/**
 * Tests of the examples of the components
 *
 * Every example renders with the records of the fixtures on a page of a site, as in the component library.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class ComponentExamplesTest extends FunctionalTestCase
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
     * Records of the fixtures and a site
     *
     * @return void
     *
     * @throws RuntimeException If the folder of the site cannot be created
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Frontend.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Components.csv');
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
        file_put_contents($sites . '/setup.typoscript', "page = PAGE\n");
    }

    /**
     * Every example of every component
     *
     * @return iterable<string, array{string, string}>
     *
     * @throws UnexpectedValueException If a file with examples is invalid
     */
    public static function examples(): iterable
    {
        foreach (Examples::find(\dirname(__DIR__, 2) . '/Resources/Private/Components') as $component => $file) {
            foreach (array_keys(Examples::fromFile($file)->examples) as $example) {
                yield $component . '#' . $example => [$component, $example];
            }
        }
    }

    /**
     * An example renders content: links have a target and a text, images an alternative, ids are unique, and no
     * expression of Fluid is left
     *
     * @param string $component Component
     * @param string $example   Example
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws SiteNotFoundException If the site is missing
     * @throws UnexpectedValueException If an example or a record is missing
     * @throws InvalidArgumentException If the address of the example is invalid
     */
    #[Test]
    #[DataProvider('examples')]
    public function exampleRenders(string $component, string $example): void
    {
        $site = $this->get(SiteFinder::class)->getSiteByIdentifier('events');
        self::assertInstanceOf(Site::class, $site);
        $uri = (string) $this->get(ExampleEndpoint::class)->uri($site, $component, $example);
        $response = $this->executeFrontendSubRequest(new InternalRequest($uri));
        $html = (string) $response->getBody();
        self::assertSame(200, $response->getStatusCode(), $html);
        $document = HTMLDocument::createFromString('<!DOCTYPE html><html lang="en"><body>' . $html, LIBXML_NOERROR);
        $body = $document->body;

        self::assertNotNull($body);
        self::assertNotSame('', trim((string) $body->textContent), 'The example renders no text');
        self::assertStringNotContainsString('{', (string) $body->textContent);
        foreach ($document->querySelectorAll('a') as $link) {
            self::assertInstanceOf(Element::class, $link);
            self::assertNotSame('', (string) $link->getAttribute('href'), 'Link without target');
            self::assertNotSame('', trim((string) $link->textContent), 'Link without text');
        }

        foreach ($document->querySelectorAll('img') as $image) {
            self::assertInstanceOf(Element::class, $image);
            self::assertTrue($image->hasAttribute('alt'), 'Image without alternative');
        }

        $ids = array_map(
            static fn(Element $element): string => (string) $element->getAttribute('id'),
            iterator_to_array($document->querySelectorAll('[id]')),
        );
        self::assertSame(array_unique($ids), $ids);
    }
}
