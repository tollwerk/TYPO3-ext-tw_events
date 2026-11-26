<?php

/**
 * A part of the programme of an event, such as a talk or a break
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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A part of the programme of an event, such as a talk or a break
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Session extends AbstractEntity
{
    /**
     * Event
     *
     * @var Event|null
     */
    protected ?Event $event = null;

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
     * Type: talk, session, workshop, panel, break or other
     *
     * @var string
     */
    protected string $type = '';

    /**
     * Start as local time of the event
     *
     * @var DateTime|null
     */
    protected ?DateTime $start = null;

    /**
     * Duration in minutes
     *
     * @var integer
     */
    protected int $duration = 0;

    /**
     * Language, as a language tag of BCP 47, or an empty string for the language of the event
     *
     * @var string
     */
    protected string $language = '';

    /**
     * Speakers, including the moderation of a panel
     *
     * @var ObjectStorage<Person>
     */
    #[Lazy]
    protected ObjectStorage $speakers;

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
     * Hashtag
     *
     * @var string
     */
    protected string $hashtag = '';

    /**
     * Address of a recording
     *
     * @var string
     */
    protected string $video = '';

    /**
     * Notes
     *
     * @var ObjectStorage<Note>
     */
    #[Lazy]
    protected ObjectStorage $notes;

    /**
     * Coverage
     *
     * @var ObjectStorage<Coverage>
     */
    #[Lazy]
    protected ObjectStorage $coverage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeObject();
    }

    /**
     * Initialise the relations, also for objects the persistence creates without constructor
     *
     * @return void
     */
    public function initializeObject(): void
    {
        $this->speakers = new ObjectStorage();
        $this->notes = new ObjectStorage();
        $this->coverage = new ObjectStorage();
    }

    /**
     * Event
     *
     * @return Event|null Event, or NULL without one
     */
    public function getEvent(): ?Event
    {
        return $this->event;
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
     * Type: talk, session, workshop, panel, break or other
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
     * Duration in minutes
     *
     * @return integer
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * Language
     *
     * @return string Language tag of BCP 47, such as "de", or an empty string for the language of the event
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Speakers, including the moderation of a panel
     *
     * @return ObjectStorage<Person>
     */
    public function getSpeakers(): ObjectStorage
    {
        return $this->speakers;
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
     * Hashtag
     *
     * @return string
     */
    public function getHashtag(): string
    {
        return $this->hashtag;
    }

    /**
     * Address of a recording
     *
     * @return string
     */
    public function getVideo(): string
    {
        return $this->video;
    }

    /**
     * Notes
     *
     * @return ObjectStorage<Note>
     */
    public function getNotes(): ObjectStorage
    {
        return $this->notes;
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
     * Start in the time zone of the event
     *
     * @return DateTimeImmutable|null Start, or NULL without one
     */
    public function getStartDate(): ?DateTimeImmutable
    {
        return LocalTime::of($this->start, $this->event?->getTimezone() ?? LocalTime::DEFAULT_TIMEZONE);
    }

    /**
     * End in the time zone of the event
     *
     * @return DateTimeImmutable|null End, or NULL without start or duration
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        $start = $this->getStartDate();

        if (!$start instanceof DateTimeImmutable || ($this->duration <= 0)) {
            return null;
        }

        return $start->setTimestamp($start->getTimestamp() + ($this->duration * 60));
    }

    /**
     * Day of the session, to group a programme of several days
     *
     * @return string Day as "Y-m-d", or an empty string without start
     */
    public function getDay(): string
    {
        return $this->getStartDate()?->format('Y-m-d') ?? '';
    }

    /**
     * Whether the session is a break, which has no page of its own
     *
     * @return boolean
     */
    public function getIsBreak(): bool
    {
        return $this->type === 'break';
    }
}
