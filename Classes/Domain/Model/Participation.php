<?php

/**
 * The part an organisation takes in an event
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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * The part an organisation takes in an event
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Participation extends AbstractEntity
{
    /**
     * Event
     *
     * @var Event|null
     */
    protected ?Event $event = null;

    /**
     * Organisation
     *
     * @var Organization|null
     */
    protected ?Organization $organization = null;

    /**
     * Role: initiator, sponsor or supporter
     *
     * @var string
     */
    protected string $role = '';

    /**
     * Label, such as "Caption Sponsor"
     *
     * @var string
     */
    protected string $label = '';

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
     * Organisation
     *
     * @return Organization|null Organisation, or NULL without one
     */
    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    /**
     * Role: initiator, sponsor or supporter
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Label, such as "Caption Sponsor"
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
