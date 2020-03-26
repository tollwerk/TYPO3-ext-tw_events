<?php

namespace Tollwerk\TwEvents\Domain\Model;

use Tollwerk\TwEvents\Domain\Model\Traits\SlugTrait;

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
 * Person
 */
class Person extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    use SlugTrait;
    /**
     * Name
     *
     * @var string
     * @validate NotEmpty
     */
    protected $givenName = '';

    /**
     * Family Name
     *
     * @var string
     */
    protected $familyName = '';

    /**
     * photo
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @validate NotEmpty
     * @cascade  remove
     */
    protected $photo = null;

    /**
     * Summary
     *
     *
     * @var string
     */
    protected $summary = '';

    /**
     * Biography
     *
     *
     * @var string
     */
    protected $biography = '';

    /**
     * Teaser
     *
     *
     * @var string
     */
    protected $teaser = '';

    /**
     * Website URL
     *
     * @var string
     */
    protected $website = '';

    /**
     * Twitter Handle
     *
     * @var string
     */
    protected $twitter = '';

    /**
     * Github Handle
     *
     * @var string
     */
    protected $github = '';

    /**
     * Facebook Profile URL
     *
     * @var string
     */
    protected $facebook = '';

    /**
     * Email Address
     *
     * @var string
     */
    protected $email = '';

    /**
     * Phone Number
     *
     * @var string
     */
    protected $phone = '';

    /**
     * Returns the givenName
     *
     * @return string $givenName
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * Sets the givenName
     *
     * @param string $givenName
     *
     * @return void
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
    }

    /**
     * Returns the familyName
     *
     * @return string $familyName
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Sets the familyName
     *
     * @param string $familyName
     *
     * @return void
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     * Returns the photo
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Sets the photo
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $photo
     *
     * @return void
     */
    public function setPhoto(\TYPO3\CMS\Extbase\Domain\Model\FileReference $photo)
    {
        $this->photo = $photo;
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
     * Returns the biography
     *
     * @return string $biography
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Sets the biography
     *
     * @param string $biography
     *
     * @return void
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * Returns the teaser
     *
     * @return string $teaser
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Sets the teaser
     *
     * @param string $teaser
     *
     * @return void
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
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
     * Returns the twitter
     *
     * @return string $twitter
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Sets the twitter
     *
     * @param string $twitter
     *
     * @return void
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * Returns the github
     *
     * @return string $github
     */
    public function getGithub()
    {
        return $this->github;
    }

    /**
     * Sets the github
     *
     * @param string $github
     *
     * @return void
     */
    public function setGithub($github)
    {
        $this->github = $github;
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
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone
     *
     * @param string $phone
     *
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}
