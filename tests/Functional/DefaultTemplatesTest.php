<?php

/**
 * Default templates of tw-events, which sites without templates of their own use
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
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwBase\StructuredData\StructuredDataGraph;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use Tollwerk\TwEvents\Domain\Repository\OrganizationRepository;
use Tollwerk\TwEvents\Domain\Repository\PersonRepository;
use Tollwerk\TwEvents\Domain\Repository\SessionRepository;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Default templates of tw-events, which sites without templates of their own use
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class DefaultTemplatesTest extends FunctionalTestCase
{
    /**
     * Templates of tw-events
     *
     * @var string
     */
    private const string TEMPLATES = __DIR__ . '/../../Resources/Private/';

    /**
     * System extensions tw_base needs
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin'];

    /**
     * Extensions of the project
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $testExtensionsToLoad = ['tollwerk/tw-base', 'tollwerk/tw-accounts', 'tollwerk/tw-events'];

    /**
     * Set up the events, and a session of the Summit 2099 by Grace Hopper that has no start yet
     *
     * @return void
     *
     * @throws DBALException If the session cannot be added
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Frontend.csv');
        $this->getConnectionPool()->getConnectionForTable('tx_twevents_session')->insert('tx_twevents_session', [
            'uid' => 10,
            'pid' => 6,
            'event' => 2,
            'title' => 'Closing',
            'slug' => 'closing',
            'type' => 'talk',
            'start' => null,
            'speakers' => 1,
        ]);
        $this->getConnectionPool()->getConnectionForTable('tx_twevents_session_speaker_mm')->insert(
            'tx_twevents_session_speaker_mm',
            ['uid_local' => 10, 'uid_foreign' => 2, 'sorting' => 1],
        );
        $this->getConnectionPool()->getConnectionForTable('tx_twevents_event')
            ->update('tx_twevents_event', ['sessions' => 2], ['uid' => 2]);
    }

    /**
     * A programme of several days lists sessions without a start under a heading of their own
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testSessionsWithoutStartHaveAHeadingOfTheirOwn(): void
    {
        $event = $this->get(EventRepository::class)->findByUid(2);

        $page = $this->render('Partials/Event/Part/Schedule.html', ['event' => $event]);

        self::assertSame(['Schedule', 'Monday, June 1', 'Not scheduled yet'], $this->texts($page, 'h1, h2, h3'));
        self::assertSame(
            ['schedule-2099-06-01', 'schedule-unscheduled'],
            array_map(
                static fn(Element $day): string => (string) $day->getAttribute('id'),
                iterator_to_array($page->querySelectorAll('.Schedule__day')),
            ),
        );
    }

    /**
     * The profile of a speaker gives no date for a session without a start
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     * @throws InvalidQueryException If a query is invalid
     */
    public function testProfileGivesNoDateWithoutStart(): void
    {
        $person = $this->get(PersonRepository::class)->findByUid(2);
        self::assertInstanceOf(Person::class, $person);
        $sessions = $this->get(SessionRepository::class)->findBySpeaker($person);

        $page = $this->render('Templates/Person/Show.html', [
            'person' => $person,
            'sessions' => $sessions,
            'organizedEvents' => [],
        ]);

        self::assertSame(
            ['Accessible forms, Meetup #8, May 15, 2019', 'Closing, Summit 2099'],
            $this->texts($page, '.SessionList__item'),
        );
        self::assertCount(0, $page->querySelectorAll('time[datetime=""]'));
    }

    /**
     * The basics link the location only if the page shows it
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testBasicsLinkTheLocationOnlyIfThePageShowsIt(): void
    {
        $event = $this->get(EventRepository::class)->findByUid(1);
        self::assertInstanceOf(Event::class, $event);
        $template = 'Templates/Event/Show.html';

        $without = $this->render($template, [
            'event' => $event,
            'parts' => ['basics'],
            'hasPart' => ['basics' => true],
        ]);
        $with = $this->render($template, [
            'event' => $event,
            'parts' => ['basics', 'location'],
            'hasPart' => ['basics' => true, 'location' => true],
        ]);

        self::assertSame(['At tollwerk, Nürnberg'], $this->texts($without, '.Event__where'));
        self::assertCount(0, $without->querySelectorAll('.Event__where a'));
        self::assertSame('#location', $with->querySelector('.Event__where a')?->getAttribute('href'));
        self::assertNotNull($with->querySelector('#location'));
    }

    /**
     * Cards show the details chosen for them, texts and addresses in their order
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testCardsShowTheChosenDetails(): void
    {
        $person = $this->render('Partials/Person/Card.html', [
            'person' => $this->get(PersonRepository::class)->findByUid(1),
            'details' => ['photo' => false, 'texts' => ['summary'], 'contacts' => ['github', 'website']],
        ]);
        $organization = $this->render('Partials/Organization/Card.html', [
            'organization' => $this->get(OrganizationRepository::class)->findByUid(1),
            'details' => [
                'logo' => false,
                'address' => false,
                'contacts' => ['mastodon', 'website'],
                'description' => true,
            ],
        ]);

        self::assertSame(['Writes the first programs.'], $this->texts($person, '.PersonCard__summary'));
        self::assertSame(['GitHub', 'Website'], $this->texts($person, '.Contacts a'));
        self::assertCount(0, $organization->querySelectorAll('.Address'));
        self::assertSame(['Mastodon', 'Website'], $this->texts($organization, '.Contacts a'));
    }

    /**
     * The hosts of an event show the details of the site settings, or those of the legacy website without settings
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testHostsShowTheDetailsOfTheSiteSettings(): void
    {
        $event = $this->get(EventRepository::class)->findByUid(1);

        $page = $this->render('Partials/Event/Part/Organizers.html', ['event' => $event]);

        self::assertSame(['Writes the first programs.'], $this->texts($page, '.PersonCard__summary'));
        self::assertSame(['Website', 'GitHub'], $this->texts($page, '.Contacts a'));
    }

    /**
     * A record described inside a part with a main entity of its own leaves that part as it was, and stays the main
     * entity of the page after it
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     */
    public function testRecordBecomesTheMainEntityOfThePage(): void
    {
        $graph = $this->get(StructuredDataGraph::class);
        $graph->reset();
        $source = '<base:structuredData.entityContext.wrap id="https://example.org/#part">'
            . '<events:structuredData record="{event}" />'
            . '<span class="inside">{base:structuredData.entityContext.get()}</span>'
            . '</base:structuredData.entityContext.wrap>';
        $event = $this->get(EventRepository::class)->findByUid(2);

        $page = $this->render($source, ['event' => $event], true);

        self::assertSame(['https://example.org/#part'], $this->texts($page, '.inside'));
        // The request has no site, so the event has no address and gets an id on the page
        self::assertSame('http://localhost/event#event-2', $graph->mainEntity());
    }

    /**
     * Render a template or partial of tw-events, with its partials
     *
     * @param string               $template  Path below "Resources/Private", such as "Templates/Event/Show.html"
     * @param array<string, mixed> $variables Variables
     * @param boolean              $isSource  Whether the template is the source itself instead of a path
     *
     * @return HTMLDocument
     *
     * @throws ContainerExceptionInterface If a service is missing
     * @throws InvalidArgumentException If the request is invalid
     */
    private function render(string $template, array $variables, bool $isSource = false): HTMLDocument
    {
        $parameters = new ExtbaseRequestParameters()->setControllerExtensionName('TwEvents');
        $request = new Request(
            new ServerRequest('http://localhost/event')
                ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
                ->withAttribute('language', new SiteLanguage(0, 'en_US.UTF-8', new Uri('http://localhost/'), []))
                ->withAttribute('extbase', $parameters),
        );
        $context = $this->get(RenderingContextFactory::class)->create([], $request);
        $context->setAttribute(ServerRequestInterface::class, $request);
        $paths = $context->getTemplatePaths();
        $paths->setPartialRootPaths([self::TEMPLATES . 'Partials/']);
        if ($isSource) {
            $paths->setTemplateSource($template);
        } else {
            $paths->setTemplatePathAndFilename(self::TEMPLATES . $template);
        }

        $view = new TemplateView($context);
        $view->assignMultiple($variables);
        $output = $view->render();
        self::assertIsString($output);

        return HTMLDocument::createFromString('<!doctype html>' . $output, LIBXML_NOERROR);
    }

    /**
     * Texts of the elements that match a selector, without surrounding and repeated white space
     *
     * @param HTMLDocument $page     Page
     * @param string       $selector Selector
     *
     * @return list<string>
     */
    private function texts(HTMLDocument $page, string $selector): array
    {
        return array_values(array_map(
            static fn(Element $element): string => trim(
                (string) preg_replace('/\s+/', ' ', (string) $element->textContent),
            ),
            iterator_to_array($page->querySelectorAll($selector)),
        ));
    }
}
