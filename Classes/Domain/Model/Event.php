<?php

namespace Tollwerk\TwEvents\Domain\Model;

use Tollwerk\TwEvents\Domain\Model\Traits\SlugTrait;
use Tollwerk\TwEvents\Utility\DatetimeUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***
 *
 * This file is part of the "tollwerk Event Calendar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  © 2020
 *
 ***/

/**
 * Event
 */
class Event extends AbstractEntity
{
    use SlugTrait;
    /**
     * Event doktype
     *
     * @var int
     */
    const DOKTYPE = 150;
    /**
     * Past event
     *
     * @var string
     */
    const STAGE_PAST = 'past';
    /**
     * Running event
     *
     * @var string
     */
    const STAGE_RUNNING = 'running';
    /**
     * Future event
     *
     * @var string
     */
    const STAGE_FUTURE = 'future';
    /**
     * Unknown event stage
     *
     * @var string
     */
    const STAGE_UNKNOWN = 'unknown';
    /**
     * Attendance modes
     *
     * @var int
     */
    const ATTENDANCE_OFFLINE = 0;
    const ATTENDANCE_ONLINE = 1;
    const ATTENDANCE_MIXED = 2;
    /**
     * Event status
     *
     * @var int
     */
    const STATUS_SCHEDULED = 0;
    const STATUS_RESCHEDULED = 1;
    const STATUS_POSTPONED = 2;
    const STATUS_MOVEDONLINE = 3;
    const STATUS_CANCELLED = 4;
    /**
     * Presentation page
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Page
     */
    protected $page = null;
    /**
     * Name
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';
    /**
     * Event Start
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $eventStart = null;
    /**
     * Event End
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $eventEnd = null;
    /**
     * Event status
     *
     * @var int
     */
    protected $status = self::STATUS_SCHEDULED;
    /**
     * Attendance mode
     *
     * @var int
     */
    protected $attendanceMode = self::ATTENDANCE_OFFLINE;
    /**
     * description
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $description = '';
    /**
     * Summary
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $summary = '';
    /**
     * Location Description
     *
     * @var string
     */
    protected $locationDescription = '';
    /**
     * Ticket Description
     *
     * @var string
     */
    protected $ticketDescription = '';
    /**
     * Ticket URL
     *
     * @var string
     */
    protected $ticketUrl = '';
    /**
     * Website URL
     *
     * @var string
     */
    protected $website = '';
    /**
     * Facebook URL
     *
     * @var string
     */
    protected $facebook = '';
    /**
     * Colloq URL
     *
     * @var string
     */
    protected $colloq = '';
    /**
     * Hashtag
     *
     * @var string
     */
    protected $hashtag = '';
    /**
     * Livestream URL
     *
     * @var string
     */
    protected $livestreamUrl = '';
    /**
     * Livestream Embed Code
     *
     * @var string
     */
    protected $livestreamEmbed = '';
    /**
     * Livestream Start
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $livestreamStart = null;
    /**
     * Livestream End
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $livestreamEnd = null;
    /**
     * Downloads
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $downloads = null;
    /**
     * Location
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Organization
     */
    protected $location = null;
    /**
     * Sponsors & Supporters
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Sponsor>
     */
    protected $sponsors = null;
    /**
     * Organizers
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Person>
     */
    protected $organizers = null;
    /**
     * Presentations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Presentation>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $presentations = null;
    /**
     * Coverage
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Coverage>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $coverage = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->downloads     = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->sponsors      = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->organizers    = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->presentations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->coverage      = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the eventStart
     *
     * @return \DateTimeInterface $eventStart
     */
    public function getEventStart(): \DateTime
    {
        return DatetimeUtility::fixTimezone($this->eventStart);
    }

    /**
     * Sets the eventStart
     *
     * @param \DateTimeInterface $eventStart
     *
     * @return void
     */
    public function setEventStart(\DateTimeInterface $eventStart): void
    {
        $this->eventStart = $eventStart;
    }

    /**
     * Returns the eventEnd
     *
     * @return \DateTimeInterface $eventEnd
     */
    public function getEventEnd(): \DateTime
    {
        return DatetimeUtility::fixTimezone($this->eventEnd);
    }

    /**
     * Sets the eventEnd
     *
     * @param \DateTime $eventEnd
     *
     * @return void
     */
    public function setEventEnd(\DateTime $eventEnd): void
    {
        $this->eventEnd = $eventEnd;
    }

    /**
     * Return the status
     *
     * @return int status
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set the status
     *
     * @param int $status Status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * Return the attendance mode
     *
     * @return int Attendance mode
     */
    public function getAttendanceMode(): int
    {
        return $this->attendanceMode;
    }

    /**
     * Set the attendance mode
     *
     * @param int $attendanceMode Attendance mode
     */
    public function setAttendanceMode(int $attendanceMode): void
    {
        $this->attendanceMode = $attendanceMode;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the summary
     *
     * @return string $summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Sets the summary
     *
     * @param string $summary
     *
     * @return void
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * Returns the locationDescription
     *
     * @return string $locationDescription
     */
    public function getLocationDescription()
    {
        return $this->locationDescription;
    }

    /**
     * Sets the locationDescription
     *
     * @param string $locationDescription
     *
     * @return void
     */
    public function setLocationDescription($locationDescription)
    {
        $this->locationDescription = $locationDescription;
    }

    /**
     * Returns the ticketDescription
     *
     * @return string $ticketDescription
     */
    public function getTicketDescription()
    {
        return $this->ticketDescription;
    }

    /**
     * Sets the ticketDescription
     *
     * @param string $ticketDescription
     *
     * @return void
     */
    public function setTicketDescription($ticketDescription)
    {
        $this->ticketDescription = $ticketDescription;
    }

    /**
     * Returns the ticketUrl
     *
     * @return string $ticketUrl
     */
    public function getTicketUrl()
    {
        return $this->ticketUrl;
    }

    /**
     * Sets the ticketUrl
     *
     * @param string $ticketUrl
     *
     * @return void
     */
    public function setTicketUrl($ticketUrl)
    {
        $this->ticketUrl = $ticketUrl;
    }

    /**
     * Returns the website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Sets the website
     *
     * @param string $website
     *
     * @return void
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * Returns the facebook
     *
     * @return string $facebook
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Sets the facebook
     *
     * @param string $facebook
     *
     * @return void
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * Returns the colloq
     *
     * @return string $colloq
     */
    public function getColloq()
    {
        return $this->colloq;
    }

    /**
     * Sets the colloq
     *
     * @param string $colloq
     *
     * @return void
     */
    public function setColloq($colloq)
    {
        $this->colloq = $colloq;
    }

    /**
     * Returns the hashtag
     *
     * @return string $hashtag
     */
    public function getHashtag()
    {
        return $this->hashtag;
    }

    /**
     * Sets the hashtag
     *
     * @param string $hashtag
     *
     * @return void
     */
    public function setHashtag($hashtag)
    {
        $this->hashtag = $hashtag;
    }

    /**
     * Returns the livestreamUrl
     *
     * @return string $livestreamUrl
     */
    public function getLivestreamUrl()
    {
        return $this->livestreamUrl;
    }

    /**
     * Sets the livestreamUrl
     *
     * @param string $livestreamUrl
     *
     * @return void
     */
    public function setLivestreamUrl($livestreamUrl)
    {
        $this->livestreamUrl = $livestreamUrl;
    }

    /**
     * Returns the livestreamEmbed
     *
     * @return string $livestreamEmbed
     */
    public function getLivestreamEmbed()
    {
        return $this->livestreamEmbed;
    }

    /**
     * Sets the livestreamEmbed
     *
     * @param string $livestreamEmbed
     *
     * @return void
     */
    public function setLivestreamEmbed($livestreamEmbed)
    {
        $this->livestreamEmbed = $livestreamEmbed;
    }

    /**
     * Returns the livestreamStart
     *
     * @return \DateTime $livestreamStart
     */
    public function getLivestreamStart()
    {
        return $this->livestreamStart;
    }

    /**
     * Sets the livestreamStart
     *
     * @param \DateTime $livestreamStart
     *
     * @return void
     */
    public function setLivestreamStart(\DateTime $livestreamStart)
    {
        $this->livestreamStart = $livestreamStart;
    }

    /**
     * Returns the livestreamEnd
     *
     * @return \DateTime $livestreamEnd
     */
    public function getLivestreamEnd()
    {
        return $this->livestreamEnd;
    }

    /**
     * Sets the livestreamEnd
     *
     * @param \DateTime $livestreamEnd
     *
     * @return void
     */
    public function setLivestreamEnd(\DateTime $livestreamEnd)
    {
        $this->livestreamEnd = $livestreamEnd;
    }

    /**
     * Adds a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $download
     *
     * @return void
     */
    public function addDownload(\TYPO3\CMS\Extbase\Domain\Model\FileReference $download)
    {
        $this->downloads->attach($download);
    }

    /**
     * Removes a FileReference
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $downloadToRemove The FileReference to be removed
     *
     * @return void
     */
    public function removeDownload(\TYPO3\CMS\Extbase\Domain\Model\FileReference $downloadToRemove)
    {
        $this->downloads->detach($downloadToRemove);
    }

    /**
     * Returns the downloads
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $downloads
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    /**
     * Sets the downloads
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $downloads
     *
     * @return void
     */
    public function setDownloads(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $downloads)
    {
        $this->downloads = $downloads;
    }

    /**
     * Returns the location
     *
     * @return \Tollwerk\TwEvents\Domain\Model\Organization $location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets the location
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Organization $location
     *
     * @return void
     */
    public function setLocation(\Tollwerk\TwEvents\Domain\Model\Organization $location)
    {
        $this->location = $location;
    }

    /**
     * Adds a Person
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Person $organizer
     *
     * @return void
     */
    public function addOrganizer(\Tollwerk\TwEvents\Domain\Model\Person $organizer)
    {
        $this->organizers->attach($organizer);
    }

    /**
     * Removes a Person
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Person $organizerToRemove The Person to be removed
     *
     * @return void
     */
    public function removeOrganizer(\Tollwerk\TwEvents\Domain\Model\Person $organizerToRemove)
    {
        $this->organizers->detach($organizerToRemove);
    }

    /**
     * Returns the organizers
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Person> $organizers
     */
    public function getOrganizers()
    {
        return $this->organizers;
    }

    /**
     * Sets the organizers
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Person> $organizers
     *
     * @return void
     */
    public function setOrganizers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $organizers)
    {
        $this->organizers = $organizers;
    }

    /**
     * Adds a Sponsor
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Sponsor $sponsor
     *
     * @return void
     */
    public function addSponsor(\Tollwerk\TwEvents\Domain\Model\Sponsor $sponsor)
    {
        $this->sponsors->attach($sponsor);
    }

    /**
     * Removes a Sponsor
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Sponsor $sponsorToRemove The Sponsor to be removed
     *
     * @return void
     */
    public function removeSponsor(\Tollwerk\TwEvents\Domain\Model\Sponsor $sponsorToRemove)
    {
        $this->sponsors->detach($sponsorToRemove);
    }

    /**
     * Returns the sponsors
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Sponsor> $sponsors
     */
    public function getSponsors()
    {
        return $this->sponsors;
    }

    /**
     * Sets the sponsors
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Sponsor> $sponsors
     *
     * @return void
     */
    public function setSponsors(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $sponsors)
    {
        $this->sponsors = $sponsors;
    }

    /**
     * Adds a Coverage
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Coverage $coverage
     *
     * @return void
     */
    public function addCoverage(\Tollwerk\TwEvents\Domain\Model\Coverage $coverage)
    {
        $this->coverage->attach($coverage);
    }

    /**
     * Removes a Coverage
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Coverage $coverageToRemove The Coverage to be removed
     *
     * @return void
     */
    public function removeCoverage(\Tollwerk\TwEvents\Domain\Model\Coverage $coverageToRemove)
    {
        $this->coverage->detach($coverageToRemove);
    }

    /**
     * Returns the coverage
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Coverage> $coverage
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * Sets the coverage
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Coverage> $coverage
     *
     * @return void
     */
    public function setCoverage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $coverage)
    {
        $this->coverage = $coverage;
    }

    /**
     * Adds a Presentation
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Presentation $presentation
     *
     * @return void
     */
    public function addPresentation(\Tollwerk\TwEvents\Domain\Model\Presentation $presentation)
    {
        $this->presentations->attach($presentation);
    }

    /**
     * Removes a Presentation
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Presentation $presentationToRemove The Presentation to be removed
     *
     * @return void
     */
    public function removePresentation(\Tollwerk\TwEvents\Domain\Model\Presentation $presentationToRemove)
    {
        $this->presentations->detach($presentationToRemove);
    }

    /**
     * Returns the presentations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Presentation> $presentations
     */
    public function getPresentations()
    {
        return $this->presentations;
    }

    /**
     * Sets the presentations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Presentation> $presentations
     *
     * @return void
     */
    public function setPresentations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $presentations)
    {
        $this->presentations = $presentations;
    }

    /**
     * Return all performers
     *
     * @return Person[] Performers
     */
    public function getPerformers(): array
    {
        $performers = [];
        foreach ($this->presentations as $presentation) {
            foreach ($presentation->getPerformers() as $performer) {
                $performers[$performer->getUid()] = $performer;
            }
        }

        return array_values($performers);
    }

    /**
     * Return the current event stage
     *
     * @return string Event stage
     * @throws \Exception
     */
    public function getStage(): string
    {
        if ($this->isPast()) {
            return self::STAGE_PAST;
        }
        if ($this->isFuture()) {
            return self::STAGE_FUTURE;
        }
        if ($this->isRunning()) {
            return self::STAGE_RUNNING;
        }

        return self::STAGE_UNKNOWN;
    }

    /**
     * Return whether this is a past event
     *
     * @return bool Is past event
     * @throws \Exception
     */
    public function isPast(): bool
    {
        return $this->eventEnd < (new \DateTimeImmutable('now'));
    }

    /**
     * Return whether this is a future event
     *
     * @return bool Is future event
     * @throws \Exception
     */
    public function isFuture(): bool
    {
        return $this->eventStart > (new \DateTimeImmutable('now'));
    }

    /**
     * Return whether this is a running event
     *
     * @return bool Is running event
     * @throws \Exception
     */
    public function isRunning(): bool
    {
        $now = new \DateTimeImmutable('now');

        return ($this->eventStart <= $now) && ($this->eventEnd > $now);
    }

    /**
     * Return the alternative presentation page
     *
     * @return \Tollwerk\TwEvents\Domain\Model\Page Alternative presentation page
     */
    public function getPage(): ?Page
    {
        return $this->page;
    }

    /**
     * Set the alternative presentation page
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Page $page Alternative presentation page
     */
    public function setPage(Page $page): void
    {
        $this->page = $page;
    }

    /**
     * Return whether this event offers tickets
     *
     * @return bool Offers tickets
     */
    public function getOffersTickets(): bool
    {
        return strlen($this->getTicketUrl()) && in_array($this->getStatus(), [
                self::STATUS_SCHEDULED,
                self::STATUS_RESCHEDULED,
                self::STATUS_MOVEDONLINE,
            ]);
    }
}
