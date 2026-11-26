<?php

/**
 * An organisation that takes part in events or hosts them
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

use Locale;
use TYPO3\CMS\Extbase\Attribute\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * An organisation that takes part in events or hosts them
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class Organization extends AbstractEntity
{
    /**
     * Name
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Short name
     *
     * @var string
     */
    protected string $shortName = '';

    /**
     * Slug
     *
     * @var string
     */
    protected string $slug = '';

    /**
     * Logo
     *
     * @var FileReference|null
     */
    protected ?FileReference $logo = null;

    /**
     * Description
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Street address
     *
     * @var string
     */
    protected string $streetAddress = '';

    /**
     * Postal code
     *
     * @var string
     */
    protected string $postalCode = '';

    /**
     * Locality
     *
     * @var string
     */
    protected string $locality = '';

    /**
     * Region
     *
     * @var string
     */
    protected string $region = '';

    /**
     * Country as ISO 3166 code
     *
     * @var string
     */
    protected string $country = '';

    /**
     * Latitude
     *
     * @var float|null
     */
    protected ?float $latitude = null;

    /**
     * Longitude
     *
     * @var float|null
     */
    protected ?float $longitude = null;

    /**
     * Address of a map
     *
     * @var string
     */
    protected string $mapUrl = '';

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
     * Phone number
     *
     * @var string
     */
    protected string $phone = '';

    /**
     * Mastodon profile
     *
     * @var string
     */
    protected string $mastodon = '';

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
     * Photos, such as of a venue
     *
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $photos;

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
        $this->photos = new ObjectStorage();
    }

    /**
     * Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Short name
     *
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * Fragment of the organisation as venue on the page of an event, from its short name or its name
     *
     * @return string Fragment, such as "the-valley"
     */
    public function getAnchor(): string
    {
        $name = ($this->shortName !== '') ? $this->shortName : $this->name;

        return trim(strtolower((string) preg_replace('/\W+/u', '-', $name)), '-');
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
     * Logo
     *
     * @return FileReference|null Logo, or NULL without one
     */
    public function getLogo(): ?FileReference
    {
        return $this->logo;
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
     * Street address
     *
     * @return string
     */
    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    /**
     * Postal code
     *
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * Locality
     *
     * @return string
     */
    public function getLocality(): string
    {
        return $this->locality;
    }

    /**
     * Region
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * Country as ISO 3166 code
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Latitude
     *
     * @return float|null Latitude, or NULL without one
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Longitude
     *
     * @return float|null Longitude, or NULL without one
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Address of a map
     *
     * @return string
     */
    public function getMapUrl(): string
    {
        return $this->mapUrl;
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
     * Phone number
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
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
     * Photos, such as of a venue
     *
     * @return ObjectStorage<FileReference>
     */
    public function getPhotos(): ObjectStorage
    {
        return $this->photos;
    }

    /**
     * Whether the organisation has an address, as a venue does
     *
     * @return boolean
     */
    public function getHasAddress(): bool
    {
        return ($this->streetAddress !== '') || ($this->locality !== '');
    }

    /**
     * Whether the organisation has coordinates
     *
     * @return boolean
     */
    public function getHasGeo(): bool
    {
        return ($this->latitude !== null) && ($this->longitude !== null);
    }

    /**
     * Name of the country in English
     *
     * @return string Name, or an empty string without country
     */
    public function getCountryName(): string
    {
        $name = ($this->country === '') ? '' : Locale::getDisplayRegion('-' . $this->country, 'en');

        return \is_string($name) ? $name : '';
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
                'linkedin' => $this->linkedin,
                'twitter' => $this->twitter,
                'facebook' => $this->facebook,
            ],
            static fn(string $url): bool => $url !== '',
        );
    }
}
