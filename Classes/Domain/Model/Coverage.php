<?php

/**
 * Documentation of an event or a session
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
 * Documentation of an event or a session
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Coverage extends AbstractEntity
{
    /**
     * Type: blog, gallery, video, talk or post
     *
     * @var string
     */
    protected string $type = '';

    /**
     * Title
     *
     * @var string
     */
    protected string $title = '';

    /**
     * Address
     *
     * @var string
     */
    protected string $url = '';

    /**
     * Author
     *
     * @var Person|null
     */
    protected ?Person $author = null;

    /**
     * Name of an author without record
     *
     * @var string
     */
    protected string $authorName = '';

    /**
     * Type: blog, gallery, video, talk or post
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
     * Address
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Author
     *
     * @return Person|null Author, or NULL without one
     */
    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    /**
     * Name of an author without record
     *
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * Name of the author
     *
     * @return string Name, or an empty string without author
     */
    public function getAuthorDisplayName(): string
    {
        return $this->author?->getName() ?? $this->authorName;
    }
}
