<?php

/**
 * An event with its programme, the people and organisations taking part and its documentation
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Domain\Model;

use DateTime;
use DateTimeImmutable;
use Tollwerk\TwEvents\Domain\LocalTime;
use TYPO3\CMS\Extbase\Attribute\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * An event with its programme, the people and organisations taking part and its documentation
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Event extends AbstractEntity
{
    /**
     * Phase before the event starts
     *
     * @var string
     */
    public const string PHASE_UPCOMING = 'upcoming';

    /**
     * Phase while the event lasts
     *
     * @var string
     */
    public const string PHASE_RUNNING = 'running';

    /**
     * Phase after the event ends
     *
     * @var string
     */
    public const string PHASE_PAST = 'past';

    /**
     * Status of a cancelled event
     *
     * @var string
     */
    public const string STATUS_CANCELLED = 'cancelled';

    /**
     * Status of a postponed event, which has no new date yet
     *
     * @var string
     */
    public const string STATUS_POSTPONED = 'postponed';

    /**
     * Title
     *
     * @var string
     */
    protected string $title = '';

    /**
     * Slug
     *
     * @var string
     */
    protected string $slug = '';

    /**
     * Series
     *
     * @var Series|null
     */
    protected ?Series $series = null;

    /**
     * Start as local time of the event
     *
     * @var DateTime|null
     */
    protected ?DateTime $start = null;

    /**
     * End as local time of the event
     *
     * @var DateTime|null
     */
    protected ?DateTime $end = null;

    /**
     * Time zone
     *
     * @var string
     */
    protected string $timezone = LocalTime::DEFAULT_TIMEZONE;

    /**
     * Main language, as a language tag of BCP 47
     *
     * @var string
     */
    protected string $mainLanguage = 'en';

    /**
     * Status: scheduled, rescheduled, postponed, moved_online or cancelled
     *
     * @var string
     */
    protected string $status = 'scheduled';

    /**
     * Attendance: offline, online or mixed
     *
     * @var string
     */
    protected string $attendanceMode = 'offline';

    /**
     * Summary as plain text
     *
     * @var string
     */
    protected string $summary = '';

    /**
     * Description
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Venue
     *
     * @var Organization|null
     */
    protected ?Organization $location = null;

    /**
     * Directions to the venue
     *
     * @var string
     */
    protected string $locationDescription = '';

    /**
     * People who organise the event
     *
     * @var ObjectStorage<Person>
     */
    #[Lazy]
    protected ObjectStorage $organizers;

    /**
     * Organisations taking part
     *
     * @var ObjectStorage<Participation>
     */
    #[Lazy]
    protected ObjectStorage $participations;

    /**
     * Programme
     *
     * @var ObjectStorage<Session>
     */
    #[Lazy]
    protected ObjectStorage $sessions;

    /**
     * Coverage
     *
     * @var ObjectStorage<Coverage>
     */
    #[Lazy]
    protected ObjectStorage $coverage;

    /**
     * Uid of the page of the event
     *
     * @var integer
     */
    protected int $page = 0;

    /**
     * Website
     *
     * @var string
     */
    protected string $website = '';

    /**
     * Hashtag
     *
     * @var string
     */
    protected string $hashtag = '';

    /**
     * Address of the tickets
     *
     * @var string
     */
    protected string $ticketUrl = '';

    /**
     * Whether the link to register shows
     *
     * @var boolean
     */
    protected bool $showTicketLink = true;

    /**
     * Description of the tickets
     *
     * @var string
     */
    protected string $ticketDescription = '';

    /**
     * Address of the livestream
     *
     * @var string
     */
    protected string $livestreamUrl = '';

    /**
     * Downloads
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $downloads;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeObject();
    }

    /**
     * Whether people can register: the link is released, the event has not started yet, and it is neither cancelled
     * nor postponed
     *
     * @param DateTimeImmutable|null $now Time, or NULL for now
     *
     * @return boolean
     */
    public function getIsRegistrationOpen(?DateTimeImmutable $now = null): bool
    {
        return $this->getShowTicketLink()
            && !\in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_POSTPONED], true)
            && ($this->getPhase($now) === self::PHASE_UPCOMING);
    }

    /**
     * Initialise the relations, also for objects the persistence creates without constructor
     *
     * @return void
     */
    public function initializeObject(): void
    {
        $this->organizers = new ObjectStorage();
        $this->participations = new ObjectStorage();
        $this->sessions = new ObjectStorage();
        $this->coverage = new ObjectStorage();
        $this->downloads = new ObjectStorage();
    }

    /**
     * Title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Series
     *
     * @return Series|null Series, or NULL without one
     */
    public function getSeries(): ?Series
    {
        return $this->series;
    }

    /**
     * Start as local time of the event
     *
     * @return DateTime|null Start as local time of the event, or NULL without one
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    /**
     * End as local time of the event
     *
     * @return DateTime|null End as local time of the event, or NULL without one
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * Time zone
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * Main language, the language most sessions are held in
     *
     * @return string Language tag of BCP 47, such as "en"
     */
    public function getMainLanguage(): string
    {
        return $this->mainLanguage;
    }

    /**
     * Status: scheduled, rescheduled, postponed, moved_online or cancelled
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Attendance: offline, online or mixed
     *
     * @return string
     */
    public function getAttendanceMode(): string
    {
        return $this->attendanceMode;
    }

    /**
     * Summary as plain text
     *
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Venue
     *
     * @return Organization|null Venue, or NULL without one
     */
    public function getLocation(): ?Organization
    {
        return $this->location;
    }

    /**
     * Directions to the venue
     *
     * @return string
     */
    public function getLocationDescription(): string
    {
        return $this->locationDescription;
    }

    /**
     * People who organise the event
     *
     * @return ObjectStorage<Person>
     */
    public function getOrganizers(): ObjectStorage
    {
        return $this->organizers;
    }

    /**
     * Organisations taking part
     *
     * @return ObjectStorage<Participation>
     */
    public function getParticipations(): ObjectStorage
    {
        return $this->participations;
    }

    /**
     * Programme
     *
     * @return ObjectStorage<Session>
     */
    public function getSessions(): ObjectStorage
    {
        return $this->sessions;
    }

    /**
     * Coverage
     *
     * @return ObjectStorage<Coverage>
     */
    public function getCoverage(): ObjectStorage
    {
        return $this->coverage;
    }

    /**
     * Uid of the page of the event
     *
     * @return integer
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Website
     *
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * Hashtag
     *
     * @return string
     */
    public function getHashtag(): string
    {
        return $this->hashtag;
    }

    /**
     * Address of the tickets
     *
     * @return string
     */
    public function getTicketUrl(): string
    {
        return $this->ticketUrl;
    }

    /**
     * Whether the link to register shows: the ticket information shows with an address of the tickets, the link
     * only once an editor releases it
     *
     * @return boolean
     */
    public function getShowTicketLink(): bool
    {
        return $this->showTicketLink && ($this->ticketUrl !== '');
    }

    /**
     * Description of the tickets
     *
     * @return string
     */
    public function getTicketDescription(): string
    {
        return $this->ticketDescription;
    }

    /**
     * Address of the livestream
     *
     * @return string
     */
    public function getLivestreamUrl(): string
    {
        return $this->livestreamUrl;
    }

    /**
     * Downloads
     *
     * @return ObjectStorage<FileReference>
     */
    public function getDownloads(): ObjectStorage
    {
        return $this->downloads;
    }

    /**
     * Start in the time zone of the event
     *
     * @return DateTimeImmutable|null Start, or NULL without one
     */
    public function getStartDate(): ?DateTimeImmutable
    {
        return LocalTime::of($this->start, $this->timezone);
    }

    /**
     * End in the time zone of the event
     *
     * @return DateTimeImmutable|null End, or NULL without one
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        return LocalTime::of($this->end, $this->timezone);
    }

    /**
     * Whether the event lasts more than one day
     *
     * @return boolean
     */
    public function getIsMultiDay(): bool
    {
        $start = $this->getStartDate();
        $end = $this->getEndDate();

        if (!$start instanceof DateTimeImmutable || !$end instanceof DateTimeImmutable) {
            return false;
        }

        return $start->format('Y-m-d') !== $end->format('Y-m-d');
    }

    /**
     * Whether the event is over
     *
     * @return boolean
     */
    public function getIsPast(): bool
    {
        return $this->getPhase() === self::PHASE_PAST;
    }

    /**
     * Phase of the event at a time: "upcoming" before it starts, "running" while it lasts and "past" after it ends
     *
     * An event without a start is upcoming, an event without an end ends when it starts.
     *
     * @param DateTimeImmutable|null $now Time, or NULL for now
     *
     * @return string Phase
     */
    public function getPhase(?DateTimeImmutable $now = null): string
    {
        $now ??= new DateTimeImmutable();
        $start = $this->getStartDate();
        $end = $this->getEndDate() ?? $start;
        if (($end instanceof DateTimeImmutable) && ($end < $now)) {
            return self::PHASE_PAST;
        }

        return (($start instanceof DateTimeImmutable) && ($start <= $now)) ? self::PHASE_RUNNING : self::PHASE_UPCOMING;
    }

    /**
     * Time the event enters its next phase
     *
     * @param DateTimeImmutable|null $now Time, or NULL for now
     *
     * @return DateTimeImmutable|null Time, or NULL if the event is over or has no start
     */
    public function getNextPhaseChange(?DateTimeImmutable $now = null): ?DateTimeImmutable
    {
        $now ??= new DateTimeImmutable();
        foreach ([$this->getStartDate(), $this->getEndDate() ?? $this->getStartDate()] as $change) {
            if (($change instanceof DateTimeImmutable) && ($change >= $now)) {
                return $change;
            }
        }

        return null;
    }

    /**
     * Whether the event is cancelled
     *
     * @return boolean
     */
    public function getIsCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Sessions in the order of the programme: by start, and by title if they start at the same time
     *
     * Titles compare as the legacy website compared them, in natural order and regardless of case. Sessions without a
     * start come last.
     *
     * @return list<Session>
     */
    public function getProgramme(): array
    {
        $sessions = $this->sessions->toArray();
        usort($sessions, static function (Session $first, Session $second): int {
            $firstStart = $first->getStart()?->getTimestamp() ?? PHP_INT_MAX;
            $secondStart = $second->getStart()?->getTimestamp() ?? PHP_INT_MAX;

            $order = $firstStart <=> $secondStart;

            return ($order !== 0) ? $order : strnatcasecmp($first->getTitle(), $second->getTitle());
        });

        return $sessions;
    }

    /**
     * Speakers of all sessions, in the order their sessions were created, as on the legacy website
     *
     * @return list<Person>
     */
    public function getSpeakers(): array
    {
        $sessions = $this->sessions->toArray();
        usort($sessions, static fn(Session $first, Session $second): int => $first->getUid() <=> $second->getUid());
        $speakers = [];
        foreach ($sessions as $session) {
            foreach ($session->getSpeakers() as $speaker) {
                $speakers[spl_object_id($speaker)] = $speaker;
            }
        }

        return array_values($speakers);
    }

    /**
     * Organisations taking part, by role: initiators, sponsors and supporters
     *
     * @return array<string, list<Participation>>
     */
    public function getParticipationsByRole(): array
    {
        $roles = ['initiator' => [], 'sponsor' => [], 'supporter' => []];
        foreach ($this->participations as $participation) {
            if (($participation->getOrganization() === null) || !isset($roles[$participation->getRole()])) {
                continue;
            }

            $roles[$participation->getRole()][] = $participation;
        }

        return array_filter($roles, static fn(array $participations): bool => $participations !== []);
    }

    /**
     * Status as a value of schema.org
     *
     * @return string
     */
    public function getSchemaStatus(): string
    {
        return 'https://schema.org/' . match ($this->status) {
            'rescheduled' => 'EventRescheduled',
            'postponed' => 'EventPostponed',
            'moved_online' => 'EventMovedOnline',
            'cancelled' => 'EventCancelled',
            default => 'EventScheduled',
        };
    }

    /**
     * Attendance mode as a value of schema.org
     *
     * @return string
     */
    public function getSchemaAttendanceMode(): string
    {
        return 'https://schema.org/' . match ($this->attendanceMode) {
            'online' => 'OnlineEventAttendanceMode',
            'mixed' => 'MixedEventAttendanceMode',
            default => 'OfflineEventAttendanceMode',
        };
    }
}
