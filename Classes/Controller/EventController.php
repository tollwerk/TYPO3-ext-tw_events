<?php

/**
 * Lists events and shows an event or a session
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Controller;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Exception;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Session;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use Tollwerk\TwEvents\Page\RecordTitleProvider;
use Tollwerk\TwEvents\Page\RecordUris;
use Tollwerk\TwEvents\Page\ShownEvent;
use TYPO3\CMS\Core\Cache\CacheDataCollector;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use UnexpectedValueException;

/**
 * Lists events and shows an event or a session
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class EventController extends AbstractRecordController
{
    /**
     * Parts of an event in their default order
     *
     * @var list<string>
     */
    public const array PARTS = [
        'basics',
        'description',
        'tickets',
        'livestream',
        'schedule',
        'coverage',
        'downloads',
        'speakers',
        'location',
        'organizers',
        'participations',
    ];

    /**
     * Constructor
     *
     * @param RecordTitleProvider $recordTitleProvider Title of the page that shows a record
     * @param EventRepository     $eventRepository     Events
     * @param RecordUris          $recordUris          Addresses of records
     * @param ShownEvent          $shownEvent          Event and session the page shows
     * @param Context             $context             Context of the request, with its time
     */
    public function __construct(
        RecordTitleProvider $recordTitleProvider,
        private readonly EventRepository $eventRepository,
        private readonly RecordUris $recordUris,
        private readonly ShownEvent $shownEvent,
        private readonly Context $context,
    ) {
        parent::__construct($recordTitleProvider);
    }

    /**
     * List events
     *
     * The page stays in the cache until the list changes.
     *
     * @return ResponseInterface
     *
     * @throws InvalidQueryException If the query is invalid
     * @throws Exception If the time zones of the events cannot be read
     * @throws AspectNotFoundException If the context has no time
     */
    public function listAction(): ResponseInterface
    {
        // The page of the events shows a single event in place of the list
        $routing = $this->request->getAttribute('routing');
        if (($routing instanceof PageArguments) && isset($routing->getArguments()['tx_twevents_event'])) {
            return $this->htmlResponse('');
        }

        $scope = $this->setting('scope');
        $series = array_values(array_map(intval(...), GeneralUtility::intExplode(',', $this->setting('series'), true)));
        $limit = (int) $this->setting('limit');
        $this->view->assign('events', $this->eventRepository->findForList($scope, $series, $limit));
        $this->view->assign('scope', $scope);

        // The page stays cached until the list changes, when an event in it is over
        $change = $this->eventRepository->findNextListChange($scope, $series);
        if ($change instanceof DateTimeImmutable) {
            $this->cacheUntilChange($change);
        }

        return $this->htmlResponse();
    }

    /**
     * Show an event: the one of the route, or the one whose page this is
     *
     * An event with a page of its own redirects there while visitors can reach it; the page shows it with the same
     * plugin.
     *
     * @param Event|null $event Event of the route
     *
     * @return ResponseInterface
     *
     * @throws Exception If the query for the page of the events fails
     * @throws InvalidRouteArgumentsException If the address of the event cannot be built
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     * @throws AspectNotFoundException If the context has no time
     * @throws UnexpectedValueException If a page cannot be read
     */
    public function showAction(?Event $event = null): ResponseInterface
    {
        $page = $this->pageUid();
        if (!$event instanceof Event) {
            $event = $this->eventRepository->findOneByPage($page);
            if (!$event instanceof Event) {
                return $this->htmlResponse('');
            }
        } elseif (!\in_array($this->recordUris->ownPage($event), [0, $page], true)) {
            return $this->redirectToUri($this->recordUris->event($this->request, $event), null, 301);
        } else {
            $this->recordTitleProvider->setTitle($event->getTitle());
        }

        $now = new DateTimeImmutable();
        // Parts such as the tickets and the livestream depend on the phase, whether chosen or not
        $this->cacheUntilNextPhase($event, $now);
        $this->shownEvent->show($event);
        $parts = GeneralUtility::trimExplode(',', $this->setting('parts'), true);
        $parts = ($parts === []) ? $this->phaseParts($event, $now) : array_values(array_intersect(self::PARTS, $parts));
        $this->view->assignMultiple([
            'event' => $event,
            'parts' => $parts,
            // Parts such as the basics link others only if the page shows them
            'hasPart' => array_fill_keys($parts, true),
        ]);

        return $this->htmlResponse();
    }

    /**
     * Show a session
     *
     * @param Event   $event   Event
     * @param Session $session Session of the event
     *
     * @return ResponseInterface
     *
     * @throws PropagateResponseException If the session belongs to another event
     * @throws PageNotFoundException If there is no error page
     * @throws InvalidArgumentException If the request is invalid
     * @throws AspectNotFoundException If the context has no time
     */
    public function sessionAction(Event $event, Session $session): ResponseInterface
    {
        if ($session->getEvent()?->getUid() !== $event->getUid()) {
            $this->notFound('The session belongs to another event');
        }

        $this->recordTitleProvider->setTitle($session->getTitle());
        // The structured data of the event offer the tickets only until it starts
        $this->cacheUntilNextPhase($event, new DateTimeImmutable());
        $this->shownEvent->show($event, $session);
        $this->view->assignMultiple(['event' => $event, 'session' => $session]);

        return $this->htmlResponse();
    }

    /**
     * Parts of an event in its phase, for a plugin without a choice of parts
     *
     * The site settings "twEvents.parts.upcoming", "twEvents.parts.running" and "twEvents.parts.past" list them in
     * their order.
     *
     * @param Event             $event Event
     * @param DateTimeImmutable $now   Time of the request
     *
     * @return list<string> Parts in their order
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    private function phaseParts(Event $event, DateTimeImmutable $now): array
    {
        $site = $this->request->getAttribute('site');
        $key = 'twEvents.parts.' . $event->getPhase($now);
        $setting = ($site instanceof Site) ? $site->getSettings()->get($key) : null;
        if (!\is_array($setting)) {
            return self::PARTS;
        }

        $parts = [];
        foreach ($setting as $part) {
            $part = \is_string($part) ? trim($part) : '';
            if (!\in_array($part, self::PARTS, true) || \in_array($part, $parts, true)) {
                continue;
            }

            $parts[] = $part;
        }

        return $parts;
    }

    /**
     * Keep the page in the cache until the event enters its next phase at most
     *
     * @param Event             $event Event
     * @param DateTimeImmutable $now   Time of the request
     *
     * @return void
     *
     * @throws AspectNotFoundException If the context has no time
     */
    private function cacheUntilNextPhase(Event $event, DateTimeImmutable $now): void
    {
        $change = $event->getNextPhaseChange($now);
        if (!($change instanceof DateTimeImmutable)) {
            return;
        }

        $this->cacheUntilChange($change);
    }

    /**
     * Keep the page in the cache until a change at most, up to the second after it
     *
     * @param DateTimeInterface $change Point in time of the change
     *
     * @return void
     *
     * @throws AspectNotFoundException If the context has no time
     */
    private function cacheUntilChange(DateTimeInterface $change): void
    {
        $cache = $this->request->getAttribute('frontend.cache.collector');
        if (!$cache instanceof CacheDataCollector) {
            return;
        }

        // TYPO3 counts the lifetime from the start of the request
        $requestTime = $this->context->getPropertyFromAspect('date', 'timestamp');
        $requestTime = \is_int($requestTime) ? $requestTime : time();
        $cache->restrictMaximumLifetime(max(1, $change->getTimestamp() - $requestTime + 1));
    }
}
