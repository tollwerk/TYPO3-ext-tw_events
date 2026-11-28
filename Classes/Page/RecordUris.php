<?php

/**
 * Addresses of events, sessions, people and organisations
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Page;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Model\Session;
use Tollwerk\TwEvents\Domain\Repository\PersonRepository;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use UnexpectedValueException;

/**
 * Addresses of events, sessions, people and organisations
 *
 * Addresses are absolute and in the language of the request, or of the caller. An event with a page of its own lives
 * there while visitors can reach the page; everything else lives on the page that contains its plugin.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final readonly class RecordUris
{
    /**
     * Constructor
     *
     * @param PluginPages      $pluginPages      Pages that show the records
     * @param PageRepository   $pageRepository   Pages that visitors can reach
     * @param PersonRepository $personRepository People, for whether a profile is public
     */
    public function __construct(
        private PluginPages $pluginPages,
        private PageRepository $pageRepository,
        private PersonRepository $personRepository,
    ) {}

    /**
     * Page of its own that an event lives on
     *
     * A page that is hidden, scheduled, restricted or deleted does not count: the event lives on the page of the events
     * then, as an event without a page does.
     *
     * @param Event $event Event
     *
     * @return integer Uid of the page, or 0 if visitors cannot reach one
     *
     * @throws UnexpectedValueException If the page cannot be read
     */
    public function ownPage(Event $event): int
    {
        return $this->reachablePage($event->getPage());
    }

    /**
     * A page if visitors can reach it, such as the page of an event
     *
     * @param integer $page Uid of the page, or 0 for none
     *
     * @return integer Uid of the page, or 0 if visitors cannot reach it
     *
     * @throws UnexpectedValueException If the page cannot be read
     */
    public function reachablePage(int $page): int
    {
        return (($page > 0) && ($this->pageRepository->getPage($page) !== [])) ? $page : 0;
    }

    /**
     * Address of an event: its own page, or the page of the events
     *
     * @param ServerRequestInterface $request Current request
     * @param Event                  $event   Event
     *
     * @return string Address, or an empty string without a page to show the event
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     * @throws UnexpectedValueException If the page cannot be read
     */
    public function event(ServerRequestInterface $request, Event $event): string
    {
        $site = $request->getAttribute('site');

        return ($site instanceof Site)
            ? $this->eventByUid($site, (int) $event->getUid(), $event->getPage(), $this->language($request))
            : '';
    }

    /**
     * Address of an event by its uid and page, for callers without the model, such as redirects of old addresses
     *
     * @param Site              $site     Site
     * @param integer           $event    Uid of the event
     * @param integer           $page     Its own page, or 0 if it has none
     * @param SiteLanguage|null $language Language, or NULL for the default language of the site
     *
     * @return string Address, or an empty string without a page to show the event
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     * @throws UnexpectedValueException If the page cannot be read
     */
    public function eventByUid(Site $site, int $event, int $page, ?SiteLanguage $language = null): string
    {
        // The router builds the address of the root page for a page that visitors cannot reach
        $page = $this->reachablePage($page);
        if ($page > 0) {
            return (string) $site->getRouter()->generateUri($page, $this->languageParameter($language));
        }

        return $this->uri($site, PluginPages::EVENTS, 'Event', 'show', ['event' => $event], $language);
    }

    /**
     * Address of a session, on the page of the events
     *
     * @param ServerRequestInterface $request Current request
     * @param Session                $session Session
     *
     * @return string Address, or an empty string without a page to show the session
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function session(ServerRequestInterface $request, Session $session): string
    {
        $site = $request->getAttribute('site');
        $event = $session->getEvent();
        if (!$site instanceof Site || (!$event instanceof Event)) {
            return '';
        }

        return $this->sessionByUid($site, (int) $event->getUid(), (int) $session->getUid(), $this->language($request));
    }

    /**
     * Address of a session by its uid and that of its event, for callers without the model
     *
     * @param Site              $site     Site
     * @param integer           $event    Uid of the event
     * @param integer           $session  Uid of the session
     * @param SiteLanguage|null $language Language, or NULL for the default language of the site
     *
     * @return string Address, or an empty string without a page to show the session
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function sessionByUid(Site $site, int $event, int $session, ?SiteLanguage $language = null): string
    {
        $arguments = ['event' => $event, 'session' => $session];

        return $this->uri($site, PluginPages::EVENTS, 'Event', 'session', $arguments, $language);
    }

    /**
     * Address of the profile of a person
     *
     * @param ServerRequestInterface $request Current request
     * @param Person                 $person  Person
     *
     * @return string Address, or an empty string without a page for people or a public profile
     *
     * @throws Exception If the query for the page or the profile fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function person(ServerRequestInterface $request, Person $person): string
    {
        $site = $request->getAttribute('site');

        return ($site instanceof Site) && $this->personRepository->hasPublicProfile($person)
            ? $this->personByUid($site, (int) $person->getUid(), $this->language($request))
            : '';
    }

    /**
     * Address of the profile of a person by its uid, such as for the sitemap
     *
     * @param Site              $site     Site
     * @param integer           $person   Uid of the person
     * @param SiteLanguage|null $language Language, or NULL for the default language of the site
     *
     * @return string Address, or an empty string without a page for people
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function personByUid(Site $site, int $person, ?SiteLanguage $language = null): string
    {
        return $this->uri($site, PluginPages::PEOPLE, 'Person', 'show', ['person' => $person], $language);
    }

    /**
     * Address of the profile of an organisation
     *
     * @param ServerRequestInterface $request      Current request
     * @param Organization           $organization Organisation
     *
     * @return string Address, or an empty string without a page for organisations
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function organization(ServerRequestInterface $request, Organization $organization): string
    {
        $site = $request->getAttribute('site');

        return ($site instanceof Site)
            ? $this->organizationByUid($site, (int) $organization->getUid(), $this->language($request))
            : '';
    }

    /**
     * Address of the profile of an organisation by its uid, such as for the sitemap
     *
     * @param Site              $site         Site
     * @param integer           $organization Uid of the organisation
     * @param SiteLanguage|null $language     Language, or NULL for the default language of the site
     *
     * @return string Address, or an empty string without a page for organisations
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function organizationByUid(Site $site, int $organization, ?SiteLanguage $language = null): string
    {
        $arguments = ['organization' => $organization];

        return $this->uri($site, PluginPages::ORGANIZATIONS, 'Organization', 'show', $arguments, $language);
    }

    /**
     * Address of an action of a plugin on the page that contains it
     *
     * @param Site                               $site       Site
     * @param string                             $module     Value of "Contains plugin" of the page
     * @param string                             $controller Controller
     * @param string                             $action     Action
     * @param array<string, integer|string|null> $arguments  Arguments
     * @param SiteLanguage|null                  $language   Language, or NULL for the default language of the site
     *
     * @return string Address, or an empty string without such a page
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    private function uri(
        Site $site,
        string $module,
        string $controller,
        string $action,
        array $arguments,
        ?SiteLanguage $language,
    ): string {
        $page = $this->pluginPages->find($site, $module);
        if ($page === 0) {
            return '';
        }

        $plugin = 'tx_twevents_' . strtolower($controller);
        $parameters = [
            $plugin => ['controller' => $controller, 'action' => $action, ...$arguments],
            ...$this->languageParameter($language),
        ];

        return (string) $site->getRouter()->generateUri($page, $parameters);
    }

    /**
     * Language of a request
     *
     * @param ServerRequestInterface $request Request
     *
     * @return SiteLanguage|null Language, or NULL without one
     */
    private function language(ServerRequestInterface $request): ?SiteLanguage
    {
        $language = $request->getAttribute('language');

        return ($language instanceof SiteLanguage) ? $language : null;
    }

    /**
     * Parameter of the router for a language, which without it builds addresses in the default language of the site
     *
     * @param SiteLanguage|null $language Language, or NULL for the default language
     *
     * @return array{_language?: SiteLanguage}
     */
    private function languageParameter(?SiteLanguage $language): array
    {
        return ($language instanceof SiteLanguage) ? ['_language' => $language] : [];
    }
}
