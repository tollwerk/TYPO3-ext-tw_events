<?php

/**
 * Describes events, sessions, people and organisations in the JSON-LD graph of the page
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\StructuredData;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwBase\StructuredData\StructuredDataGraph;
use Tollwerk\TwEvents\Domain\Model\Coverage;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Participation;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Model\Series;
use Tollwerk\TwEvents\Domain\Model\Session;
use Tollwerk\TwEvents\Page\RecordUris;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Typolink\LinkFactory;
use TYPO3\CMS\Frontend\Typolink\UnableToLinkException;
use UnexpectedValueException;

/**
 * Describes events, sessions, people and organisations in the JSON-LD graph of the page
 *
 * People and organisations are nodes of their own, so a person speaking in several sessions appears once. Records with
 * a page of their own take its address as id; others get an id on the current page. Only what the records hold is
 * described: there is no price without one, and no image of an event.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final readonly class SchemaBuilder
{
    /**
     * Types of creative works by the kind of coverage
     *
     * @var array<string, string>
     */
    private const array COVERAGE_TYPES = [
        'blog' => 'BlogPosting',
        'gallery' => 'ImageGallery',
        'post' => 'SocialMediaPosting',
        'talk' => 'PresentationDigitalDocument',
        'video' => 'VideoObject',
    ];

    /**
     * Constructor
     *
     * @param StructuredDataGraph $graph       JSON-LD graph of the page
     * @param RecordUris          $recordUris  Addresses of records
     * @param LinkFactory         $linkFactory Links, such as to files
     */
    public function __construct(
        private StructuredDataGraph $graph,
        private RecordUris $recordUris,
        private LinkFactory $linkFactory,
    ) {}

    /**
     * Describe a list of events, people or organisations, each by its name and address
     *
     * The list does not describe its records in full: their own pages do, and search engines follow the addresses.
     *
     * @param ServerRequestInterface $request Current request
     * @param iterable<mixed>        $records Events, people or organisations
     * @param string                 $name    Name of the list, or an empty string without one
     *
     * @return string|null Id of the list, or NULL for a list without entries
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     * @throws UnexpectedValueException If a page cannot be read
     */
    public function itemList(ServerRequestInterface $request, iterable $records, string $name = ''): ?string
    {
        $items = [];
        foreach ($records as $record) {
            [$itemName, $url] = match (true) {
                $record instanceof Event => [$record->getTitle(), $this->recordUris->event($request, $record)],
                $record instanceof Person => [$record->getName(), $this->recordUris->person($request, $record)],
                $record instanceof Organization => [
                    $record->getName(),
                    $this->recordUris->organization($request, $record),
                ],
                default => ['', ''],
            };
            if ($url === '') {
                continue;
            }

            $items[] = ['@type' => 'ListItem', 'position' => \count($items) + 1, 'name' => $itemName, 'url' => $url];
        }

        if ($items === []) {
            return null;
        }

        // Lists are numbered by their order on the page
        $page = (string) $request->getUri()->withQuery('')->withFragment('');
        $id = $page . '#list-' . (\count($this->graph->ofType('ItemList')) + 1);
        $this->graph->register('ItemList', $id, array_filter([
            'name' => $name,
            'numberOfItems' => \count($items),
            'itemListElement' => $items,
        ], static fn(mixed $value): bool => $value !== ''));

        return $id;
    }

    /**
     * Describe an event with its programme, the people and the organisations taking part
     *
     * @param ServerRequestInterface $request Current request
     * @param Event                  $event   Event
     *
     * @return string Id of the event
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     * @throws UnexpectedValueException If a page cannot be read
     */
    public function event(ServerRequestInterface $request, Event $event): string
    {
        $url = $this->recordUris->event($request, $event);
        $id = $this->id($request, $url, 'event-' . $event->getUid());
        $roles = $event->getParticipationsByRole();
        $series = $event->getSeries();
        $this->graph->register('Event', $id, array_filter([
            'name' => $event->getTitle(),
            'url' => $url,
            'description' => $this->text($event->getSummary()),
            'startDate' => $this->date($event->getStartDate()),
            'endDate' => $this->date($event->getEndDate()),
            'eventStatus' => $event->getSchemaStatus(),
            'eventAttendanceMode' => $event->getSchemaAttendanceMode(),
            'location' => $this->locations($request, $event),
            'organizer' => [
                ...$this->people($request, $event->getOrganizers()),
                ...$this->organizations($request, $roles['initiator'] ?? []),
            ],
            'performer' => $this->people($request, $event->getSpeakers()),
            'sponsor' => $this->organizations($request, $roles['sponsor'] ?? []),
            'contributor' => $this->organizations($request, $roles['supporter'] ?? []),
            'superEvent' => ($series instanceof Series) ? ['@id' => $this->series($request, $series)] : null,
            'offers' => ($event->getTicketUrl() === '') ? null : array_filter([
                '@type' => 'Offer',
                // The address only while the website shows the link to register
                'url' => $event->getIsRegistrationOpen() ? $event->getTicketUrl() : null,
                'description' => $this->text($event->getTicketDescription()),
            ], $this->filled(...)),
            'keywords' => $this->keywords($event->getHashtag()),
            'subjectOf' => [
                ...$this->coverage($request, $event->getCoverage()),
                ...$this->downloads($request, $event),
            ],
            'subEvent' => array_map(
                fn(Session $session): array => ['@id' => $this->session($request, $session, $id)],
                array_values(array_filter(
                    iterator_to_array($event->getSessions(), false),
                    static fn(Session $session): bool => !$session->getIsBreak(),
                )),
            ),
        ], $this->filled(...)));

        return $id;
    }

    /**
     * Describe a session as part of its event
     *
     * @param ServerRequestInterface $request Current request
     * @param Session                $session Session
     * @param string|null            $parent  Id of the event, or NULL to describe the event, too
     *
     * @return string Id of the session
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     * @throws UnexpectedValueException If a page cannot be read
     */
    public function session(ServerRequestInterface $request, Session $session, ?string $parent = null): string
    {
        $event = $session->getEvent();
        if (($parent === null) && ($event instanceof Event)) {
            $parent = $this->event($request, $event);
        }

        $url = $this->recordUris->session($request, $session);
        $id = $this->id($request, $url, 'session-' . $session->getUid());
        $video = $session->getVideo();
        $this->graph->register(($session->getType() === 'workshop') ? 'EducationEvent' : 'Event', $id, array_filter([
            'name' => $session->getTitle(),
            'url' => $url,
            'description' => $this->text($session->getSummary()),
            'startDate' => $this->date($session->getStartDate()),
            'endDate' => $this->date($session->getEndDate()),
            // A session takes place where and as its event does
            'eventStatus' => $event?->getSchemaStatus(),
            'eventAttendanceMode' => $event?->getSchemaAttendanceMode(),
            'location' => ($event instanceof Event) ? $this->locations($request, $event) : null,
            'performer' => $this->people($request, $session->getSpeakers()),
            'superEvent' => ($parent === null) ? null : ['@id' => $parent],
            'keywords' => $this->keywords($session->getHashtag()),
            'recordedIn' => ($video === '') ? null : array_filter([
                '@type' => 'VideoObject',
                'name' => $session->getTitle(),
                'url' => $video,
                'thumbnailUrl' => $this->videoThumbnail($video),
            ], $this->filled(...)),
            'subjectOf' => $this->coverage($request, $session->getCoverage()),
        ], $this->filled(...)));

        return $id;
    }

    /**
     * Describe a person
     *
     * @param ServerRequestInterface $request Current request
     * @param Person                 $person  Person
     *
     * @return string Id of the person
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    public function person(ServerRequestInterface $request, Person $person): string
    {
        $url = $this->recordUris->person($request, $person);
        $id = $this->id($request, $url, 'person-' . $person->getUid());
        $this->graph->register('Person', $id, array_filter([
            'name' => $person->getName(),
            'givenName' => $person->getGivenName(),
            'familyName' => $person->getFamilyName(),
            'url' => $url,
            'description' => $this->text($person->getSummary()),
            'image' => $this->image($request, $person->getPhoto()),
            'sameAs' => array_values(array_filter(
                [$person->getWebsite(), ...array_values($person->getProfiles())],
                static fn(string $url): bool => $url !== '',
            )),
        ], $this->filled(...)));

        return $id;
    }

    /**
     * Describe an organisation
     *
     * @param ServerRequestInterface $request      Current request
     * @param Organization           $organization Organisation
     *
     * @return string Id of the organisation
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    public function organization(ServerRequestInterface $request, Organization $organization): string
    {
        $url = $this->recordUris->organization($request, $organization);
        $id = $this->id($request, $url, 'organization-' . $organization->getUid());
        $this->graph->register('Organization', $id, array_filter([
            'name' => $organization->getName(),
            'alternateName' => $organization->getShortName(),
            'url' => ($organization->getWebsite() !== '') ? $organization->getWebsite() : $url,
            'description' => $this->text($organization->getDescription()),
            'logo' => $this->image($request, $organization->getLogo()),
            'address' => $this->address($organization),
            'email' => $organization->getEmail(),
            'telephone' => $organization->getPhone(),
            'sameAs' => array_values($organization->getProfiles()),
        ], $this->filled(...)));

        return $id;
    }

    /**
     * Describe an organisation as a venue
     *
     * @param ServerRequestInterface $request      Current request
     * @param Organization           $organization Organisation
     *
     * @return string Id of the place
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    public function place(ServerRequestInterface $request, Organization $organization): string
    {
        $url = $this->recordUris->organization($request, $organization);
        $id = $this->id($request, $url, 'place-' . $organization->getUid());
        $photos = [];
        foreach ($organization->getPhotos() as $photo) {
            $photo = $this->image($request, $photo);
            if ($photo === null) {
                continue;
            }

            $photos[] = $photo;
        }

        $this->graph->register('Place', $id, array_filter([
            'name' => $organization->getName(),
            'url' => $url,
            'image' => $photos,
            'address' => $this->address($organization),
            'geo' => $organization->getHasGeo()
                ? [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $organization->getLatitude(),
                    'longitude' => $organization->getLongitude(),
                ]
                : null,
            'hasMap' => $organization->getMapUrl(),
        ], $this->filled(...)));

        return $id;
    }

    /**
     * Describe a series of events
     *
     * @param ServerRequestInterface $request Current request
     * @param Series                 $series  Series
     *
     * @return string Id of the series
     *
     * @throws InvalidArgumentException If an id cannot be built
     */
    public function series(ServerRequestInterface $request, Series $series): string
    {
        $id = (string) $request->getUri()->withPath('/')->withQuery('')->withFragment('series-' . $series->getSlug());
        $this->graph->register('EventSeries', $id, ['name' => $series->getTitle()]);

        return $id;
    }

    /**
     * Where an event takes place: its venue, and its live stream if people take part online
     *
     * @param ServerRequestInterface $request Current request
     * @param Event                  $event   Event
     *
     * @return array<mixed>|null Place, places, or NULL without any
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    private function locations(ServerRequestInterface $request, Event $event): ?array
    {
        $location = $event->getLocation();
        $locations = [];
        if ($location instanceof Organization) {
            $locations[] = ['@id' => $this->place($request, $location)];
        }

        if (($event->getLivestreamUrl() !== '') && ($event->getAttendanceMode() !== 'offline')) {
            $locations[] = ['@type' => 'VirtualLocation', 'url' => $event->getLivestreamUrl()];
        }

        return match (\count($locations)) {
            0 => null,
            1 => $locations[0],
            default => $locations,
        };
    }

    /**
     * References to organisations taking part in an event, which are described, too
     *
     * @param ServerRequestInterface  $request        Current request
     * @param iterable<Participation> $participations Parts of the organisations
     *
     * @return list<array{"@id": string}>
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    private function organizations(ServerRequestInterface $request, iterable $participations): array
    {
        $references = [];
        foreach ($participations as $participation) {
            $organization = $participation->getOrganization();
            if (!($organization instanceof Organization)) {
                continue;
            }

            $references[] = ['@id' => $this->organization($request, $organization)];
        }

        return $references;
    }

    /**
     * Coverage as creative works about an event or a session
     *
     * @param ServerRequestInterface $request  Current request
     * @param iterable<Coverage>     $coverage Coverage
     *
     * @return list<array<string, mixed>>
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    private function coverage(ServerRequestInterface $request, iterable $coverage): array
    {
        $works = [];
        foreach ($coverage as $item) {
            $url = $this->link($request, $item->getUrl());
            if ($url === '') {
                continue;
            }

            $author = $item->getAuthor();
            $name = $item->getAuthorName();
            $works[] = array_filter([
                '@type' => self::COVERAGE_TYPES[$item->getType()] ?? 'CreativeWork',
                'name' => $item->getTitle(),
                'url' => $url,
                'author' => ($author instanceof Person)
                    ? ['@id' => $this->person($request, $author)]
                    : (($name === '') ? null : ['@type' => 'Person', 'name' => $name]),
            ], $this->filled(...));
        }

        return $works;
    }

    /**
     * Downloads of an event as digital documents
     *
     * @param ServerRequestInterface $request Current request
     * @param Event                  $event   Event
     *
     * @return list<array<string, mixed>>
     *
     * @throws InvalidArgumentException If an address cannot be built
     */
    private function downloads(ServerRequestInterface $request, Event $event): array
    {
        $documents = [];
        foreach ($event->getDownloads() as $download) {
            $url = $this->image($request, $download);
            if ($url === null) {
                continue;
            }

            $file = $download->getOriginalResource();
            $documents[] = array_filter([
                '@type' => 'DigitalDocument',
                'name' => ($file->getTitle() !== '') ? $file->getTitle() : $file->getName(),
                'url' => $url,
                'encodingFormat' => $file->getMimeType(),
            ], $this->filled(...));
        }

        return $documents;
    }

    /**
     * Absolute address of a link that an editor chose, such as to a file
     *
     * @param ServerRequestInterface $request   Current request
     * @param string                 $parameter Link, such as "t3://file?uid=1" or an address
     *
     * @return string Address, or an empty string if the link leads nowhere, as to a file that is gone
     *
     * @throws InvalidArgumentException If the link cannot be built
     */
    private function link(ServerRequestInterface $request, string $parameter): string
    {
        if ($parameter === '') {
            return '';
        }

        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->setRequest($request);
        try {
            $link = ['parameter' => $parameter, 'forceAbsoluteUrl' => true];

            return $this->linkFactory->create('', $link, $contentObject)->getUrl();
        } catch (UnableToLinkException) {
            return '';
        }
    }

    /**
     * Postal address of an organisation
     *
     * @param Organization $organization Organisation
     *
     * @return array<string, string>|null Address, or NULL without one
     */
    private function address(Organization $organization): ?array
    {
        $address = array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $organization->getStreetAddress(),
            'postalCode' => $organization->getPostalCode(),
            'addressLocality' => $organization->getLocality(),
            'addressRegion' => $organization->getRegion(),
            'addressCountry' => $organization->getCountry(),
        ], $this->filled(...));

        return (\count($address) > 1) ? $address : null;
    }

    /**
     * Keywords from a list of hashtags, such as "dsndevcollab,htmlfirst"
     *
     * @param string $hashtags Hashtags, separated by commas
     *
     * @return list<string>
     */
    private function keywords(string $hashtags): array
    {
        return array_values(array_filter(
            array_map(static fn(string $tag): string => ltrim(trim($tag), '#'), explode(',', $hashtags)),
            static fn(string $tag): bool => $tag !== '',
        ));
    }

    /**
     * Preview image of a video on YouTube, which follows from its id
     *
     * @param string $url Address of the video
     *
     * @return string|null Address of the image, or NULL for other videos
     */
    private function videoThumbnail(string $url): ?string
    {
        $match = preg_match('%(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/)|youtu\.be/)([\w-]{11})%', $url, $parts);

        return ($match === 1) ? 'https://i.ytimg.com/vi/' . $parts[1] . '/hqdefault.jpg' : null;
    }

    /**
     * Plain text of a text that may hold markup
     *
     * @param string $text Text
     *
     * @return string
     */
    private function text(string $text): string
    {
        // Line breaks and paragraphs separate words
        $text = strip_tags(str_replace(['<br', '</p>'], [' <br', ' </p>'], $text));
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);

        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * References to people, who are described, too
     *
     * @param ServerRequestInterface $request Current request
     * @param iterable<Person>       $people  People
     *
     * @return list<array{"@id": string}>
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     */
    private function people(ServerRequestInterface $request, iterable $people): array
    {
        $references = [];
        foreach ($people as $person) {
            $references[] = ['@id' => $this->person($request, $person)];
        }

        return $references;
    }

    /**
     * Id of a record: its address, or an address on the current page without one
     *
     * @param ServerRequestInterface $request Current request
     * @param string                 $url     Address of the record
     * @param string                 $name    Name of the record on the current page
     *
     * @return string
     *
     * @throws InvalidArgumentException If the address cannot be built
     */
    private function id(ServerRequestInterface $request, string $url, string $name): string
    {
        if ($url !== '') {
            return $url . '#' . explode('-', $name, 2)[0];
        }

        return (string) $request->getUri()->withQuery('')->withFragment($name);
    }

    /**
     * Absolute address of an image
     *
     * @param ServerRequestInterface $request Current request
     * @param FileReference|null     $image   Image
     *
     * @return string|null Address, or NULL without image
     *
     * @throws InvalidArgumentException If the address cannot be built
     */
    private function image(ServerRequestInterface $request, ?FileReference $image): ?string
    {
        $url = $image?->getOriginalResource()->getPublicUrl();
        if (($url === null) || ($url === '')) {
            return null;
        }

        return str_starts_with($url, '/')
            ? (string) $request->getUri()->withPath($url)->withQuery('')->withFragment('')
            : $url;
    }

    /**
     * Date in the format of schema.org
     *
     * @param DateTimeImmutable|null $date Date
     *
     * @return string|null Date, or NULL without one
     */
    private function date(?DateTimeImmutable $date): ?string
    {
        return $date?->format('c');
    }

    /**
     * Whether a property has a value
     *
     * @param mixed $value Value
     *
     * @return boolean
     */
    private function filled(mixed $value): bool
    {
        return !in_array($value, [null, '', []], true);
    }
}
