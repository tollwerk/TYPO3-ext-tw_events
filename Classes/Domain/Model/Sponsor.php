<?php

namespace Tollwerk\TwEvents\Domain\Model;


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
 * Sponsor
 */
class Sponsor extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    const TYPE_INITIATOR = 0;
    const TYPE_SPONSOR = 1;
    const TYPE_ORGANIZER = 2;

    // Sponsor types
    const TYPE_SUPPORTER = 3;
    /**
     * Alternative label
     *
     * @var string
     */
    protected $label;
    /**
     * Organization
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Organization
     */
    protected $organization = null;
    /**
     * Sponsor type
     *
     * @var int
     */
    protected $type;

    /**
     * Return the alternative label
     *
     * @return string Alternative label
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set the alternativge label
     *
     * @param string $label Alternative label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Returns the organization
     *
     * @return \Tollwerk\TwEvents\Domain\Model\Organization $organization
     */
    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    /**
     * Sets the organization
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Organization $organization
     *
     * @return void
     */
    public function setOrganization(Organization $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * Return the type
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Set the type
     *
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }
}
