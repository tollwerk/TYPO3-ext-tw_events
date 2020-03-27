<?php

namespace Tollwerk\TwEvents\Domain\Model;

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
 * Coverage
 */
class Coverage extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    const TYPE_GALLERY = 0;
    const TYPE_BLOG = 1;
    const TYPE_TWEET = 2;
    const TYPE_VIDEO = 3;
    const TYPE_TALK = 4;
    /**
     * Title
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    // Coverage types
    /**
     * Type
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $type = 0;
    /**
     * URL
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $url = '';
    /**
     * Author (person)
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Person|null
     */
    protected $author = null;
    /**
     * Author (name)
     *
     * @var string
     */
    protected $authorName = '';

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
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
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the type
     *
     * @return int $type
     */
    public function getType()
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
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url
     *
     * @param string $url
     *
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Returns the author
     *
     * @return \Tollwerk\TwEvents\Domain\Model\Person $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the author
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Person|null $author
     *
     * @return void
     */
    public function setAuthor(\Tollwerk\TwEvents\Domain\Model\Person $author = null)
    {
        $this->author = $author;
    }

    /**
     * Returns the authorName
     *
     * @return string $authorName
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Sets the authorName
     *
     * @param string $authorName
     *
     * @return void
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }
}
