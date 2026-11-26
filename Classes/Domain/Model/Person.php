<?php

/**
 * A person who organises events or speaks in sessions: a frontend user with the fields of tw-accounts
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

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A person who organises events or speaks in sessions: a frontend user with the fields of tw-accounts
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Person extends AbstractEntity
{
    /**
     * Given name
     *
     * @var string
     */
    protected string $givenName = '';

    /**
     * Family name
     *
     * @var string
     */
    protected string $familyName = '';

    /**
     * Slug
     *
     * @var string
     */
    protected string $slug = '';

    /**
     * Whether the profile is public on its own, without events or sessions
     *
     * @var boolean
     */
    protected bool $public = false;

    /**
     * Photo
     *
     * @var FileReference|null
     */
    protected ?FileReference $photo = null;

    /**
     * Short biography as plain text
     *
     * @var string
     */
    protected string $summary = '';

    /**
     * Teaser
     *
     * @var string
     */
    protected string $teaser = '';

    /**
     * Biography
     *
     * @var string
     */
    protected string $biography = '';

    /**
     * Website
     *
     * @var string
     */
    protected string $website = '';

    /**
     * Email address
     *
     * @var string
     */
    protected string $email = '';

    /**
     * Mastodon profile
     *
     * @var string
     */
    protected string $mastodon = '';

    /**
     * GitHub profile
     *
     * @var string
     */
    protected string $github = '';

    /**
     * LinkedIn profile
     *
     * @var string
     */
    protected string $linkedin = '';

    /**
     * Twitter profile
     *
     * @var string
     */
    protected string $twitter = '';

    /**
     * Facebook profile
     *
     * @var string
     */
    protected string $facebook = '';

    /**
     * Instagram profile
     *
     * @var string
     */
    protected string $instagram = '';

    /**
     * Given name
     *
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * Family name
     *
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
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
     * Photo
     *
     * @return FileReference|null Photo, or NULL without one
     */
    public function getPhoto(): ?FileReference
    {
        return $this->photo;
    }

    /**
     * Short biography as plain text
     *
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Teaser
     *
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Biography
     *
     * @return string
     */
    public function getBiography(): string
    {
        return $this->biography;
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
     * Email address
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Mastodon profile
     *
     * @return string
     */
    public function getMastodon(): string
    {
        return $this->mastodon;
    }

    /**
     * GitHub profile
     *
     * @return string
     */
    public function getGithub(): string
    {
        return $this->github;
    }

    /**
     * LinkedIn profile
     *
     * @return string
     */
    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    /**
     * Twitter profile
     *
     * @return string
     */
    public function getTwitter(): string
    {
        return $this->twitter;
    }

    /**
     * Facebook profile
     *
     * @return string
     */
    public function getFacebook(): string
    {
        return $this->facebook;
    }

    /**
     * Instagram profile
     *
     * @return string
     */
    public function getInstagram(): string
    {
        return $this->instagram;
    }

    /**
     * Whether the profile is public on its own, without events or sessions
     *
     * @return boolean
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Full name
     *
     * @return string
     */
    public function getName(): string
    {
        return trim($this->givenName . ' ' . $this->familyName);
    }

    /**
     * Profiles on other websites, by network
     *
     * @return array<string, string> Addresses, such as ['mastodon' => 'https://…']
     */
    public function getProfiles(): array
    {
        return array_filter(
            [
                'mastodon' => $this->mastodon,
                'github' => $this->github,
                'linkedin' => $this->linkedin,
                'twitter' => $this->twitter,
                'facebook' => $this->facebook,
                'instagram' => $this->instagram,
            ],
            static fn(string $url): bool => $url !== '',
        );
    }
}
