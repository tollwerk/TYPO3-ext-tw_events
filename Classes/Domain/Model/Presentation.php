<?php

namespace Tollwerk\TwEvents\Domain\Model;


use Tollwerk\TwEvents\Domain\Model\Traits\SlugTrait;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***
 *
 * This file is part of the "tollwerk Event Calendar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
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
    /**
     * Title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';
    /**
     * Type
     *
     * @var int
     * @validate NotEmpty
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
     * @validate NotEmpty
     */
    protected $description = '';
    /**
     * Hashtag
     *
     * @var string
     * @validate NotEmpty
     */
    protected $hashtag = '';

    // Presentation types
    /**
     * Start
     *
     * @var \DateTime
     * @validate NotEmpty
     */
    protected $start = null;
    /**
     * Duration (minutes)
     *
     * @var int
     * @validate NotEmpty
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
     * @cascade remove
     * @lazy
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
    protected function initStorageObjects(): void
    {
        $this->performers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->coverage   = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
    public function getHashtag(): string
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
    public function setHashtag($hashtag): void
    {
        $this->hashtag = $hashtag;
    }

    /**
     * Returns the start
     *
     * @return \DateTime $start
     */
    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    /**
     * Sets the start
     *
     * @param \DateTime $start
     *
     * @return void
     */
    public function setStart(\DateTime $start): void
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
     * @return \DateTimeImmutable End time
     * @throws \Exception
     */
    public function getEnd(): ?\DateTimeImmutable
    {
        if ($this->start instanceof \DateTimeInterface) {
            $endTimstamp = $this->start->format('U') + $this->duration * 60;

            return new \DateTimeImmutable('@'.$endTimstamp);
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
}
