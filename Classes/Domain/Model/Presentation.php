<?php

namespace Tollwerk\TwEvents\Domain\Model;

use Tollwerk\TwEvents\Domain\Model\Traits\SlugTrait;
use Tollwerk\TwEvents\Utility\DatetimeUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
 * Presentation
 */
class Presentation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    use SlugTrait;
    const TYPE_TALK = 0;
    const TYPE_SESSION = 1;
    const TYPE_PANEL = 2;
    const TYPE_BREAK = 3;
    const TYPE_WORKSHOP = 4;
    const TYPE_OTHER = 99;
    /**
     * Title
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';
    /**
     * Type
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $type = 0;
    /**
     * Summary
     *
     * @var string
     */
    protected $summary = '';
    /**
     * description
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $description = '';
    /**
     * Hashtag
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $hashtag = '';

    // Presentation types
    /**
     * Start
     *
     * @var \DateTime
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $start = null;
    /**
     * Duration (minutes)
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $duration = 0;
    /**
     * Performers
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Person>
     */
    protected $performers = null;
    /**
     * Coverage
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Coverage>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $coverage = null;
    /**
     * Video recordings
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $video = null;
    /**
     * Note
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Note>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade remove
     */
    protected $note = null;
    /**
     * Event
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Event
     */
    protected $event;
    /**
     * Presentation page
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Page
     */
    protected $page = null;

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
    protected function initStorageObjects(): void
    {
        $this->performers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->coverage   = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->video      = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->note       = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the type
     *
     * @return int $type
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param int $type
     *
     * @return void
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * Returns the summary
     *
     * @return string $summary
     */
    public function getSummary(): string
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
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription(): string
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
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the hashtag
     *
     * @return string $hashtag
     */
    public function getHashtag(): array
    {
        return GeneralUtility::trimExplode(',', $this->hashtag, true);
    }

    /**
     * Sets the hashtag
     *
     * @param array $hashtag
     *
     * @return void
     */
    public function setHashtag(array $hashtag): void
    {
        $this->hashtag = implode(',', $hashtag);
    }

    /**
     * Returns the start
     *
     * @return \DateTimeInterface $start
     */
    public function getStart(): ?\DateTime
    {
        return DatetimeUtility::fixTimezone($this->start);
    }

    /**
     * Sets the start
     *
     * @param \DateTimeInterface $start
     *
     * @return void
     */
    public function setStart(\DateTimeInterface $start): void
    {
        $this->start = $start;
    }

    /**
     * Returns the duration
     *
     * @return int $duration
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * Sets the duration
     *
     * @param int $duration
     *
     * @return void
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * Return the end time
     *
     * @return \DateTimeInterface End time
     * @throws \Exception
     */
    public function getEnd(): ?\DateTimeInterface
    {
        $start = $this->getStart();
        if ($start instanceof \DateTimeInterface) {
            return (clone $start)->modify("+{$this->duration} minutes");
        }

        return null;
    }

    /**
     * Adds a Person
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Person $performer
     *
     * @return void
     */
    public function addPerformer(\Tollwerk\TwEvents\Domain\Model\Person $performer): void
    {
        $this->performers->attach($performer);
    }

    /**
     * Removes a Person
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Person $performerToRemove The Person to be removed
     *
     * @return void
     */
    public function removePerformer(\Tollwerk\TwEvents\Domain\Model\Person $performerToRemove): void
    {
        $this->performers->detach($performerToRemove);
    }

    /**
     * Returns the performers
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Person> $performers
     */
    public function getPerformers(): ObjectStorage
    {
        return $this->performers;
    }

    /**
     * Sets the performers
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Person> $performers
     *
     * @return void
     */
    public function setPerformers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $performers): void
    {
        $this->performers = $performers;
    }

    /**
     * Adds a Coverage
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Coverage $coverage
     *
     * @return void
     */
    public function addCoverage(\Tollwerk\TwEvents\Domain\Model\Coverage $coverage): void
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
    public function removeCoverage(\Tollwerk\TwEvents\Domain\Model\Coverage $coverageToRemove): void
    {
        $this->coverage->detach($coverageToRemove);
    }

    /**
     * Returns the coverage
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Coverage> $coverage
     */
    public function getCoverage(): ObjectStorage
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
    public function setCoverage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $coverage): void
    {
        $this->coverage = $coverage;
    }

    /**
     * Adds a Video
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $video
     *
     * @return void
     */
    public function addVideo(\TYPO3\CMS\Extbase\Domain\Model\FileReference $video): void
    {
        $this->video->attach($video);
    }

    /**
     * Removes a Video
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $videoToRemove The Video to be removed
     *
     * @return void
     */
    public function removeVideo(\TYPO3\CMS\Extbase\Domain\Model\FileReference $videoToRemove): void
    {
        $this->video->detach($videoToRemove);
    }

    /**
     * Returns the video
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $video
     */
    public function getVideo(): ObjectStorage
    {
        return $this->video;
    }

    /**
     * Sets the video
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $video
     *
     * @return void
     */
    public function setVideo(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $video): void
    {
        $this->video = $video;
    }

    /**
     * Adds a Note
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Note $note
     *
     * @return void
     */
    public function addNote(\Tollwerk\TwEvents\Domain\Model\Note $note): void
    {
        $this->note->attach($note);
    }

    /**
     * Removes a Note
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Note $noteToRemove The Note to be removed
     *
     * @return void
     */
    public function removeNote(\Tollwerk\TwEvents\Domain\Model\Note $noteToRemove): void
    {
        $this->note->detach($noteToRemove);
    }

    /**
     * Returns the note
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Note> $note
     */
    public function getNote(): ObjectStorage
    {
        return $this->note;
    }

    /**
     * Sets the note
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Tollwerk\TwEvents\Domain\Model\Note> $note
     *
     * @return void
     */
    public function setNote(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $note): void
    {
        $this->note = $note;
    }

    /**
     * Return whether this presentation has documentation available
     *
     * @return bool Documentation available
     */
    public function isDocumented(): bool
    {
        return $this->note->count() || $this->coverage->count();
    }

    /**
     * Return the presentation event
     *
     * @return Event Event
     */
    public function getEvent(): Event
    {
        return $this->event;
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
}
