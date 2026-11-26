<?php

/**
 * A note on a session
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
 * A note on a session
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Note extends AbstractEntity
{
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
     * Content
     *
     * @var string
     */
    protected string $content = '';

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
     * Content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
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
