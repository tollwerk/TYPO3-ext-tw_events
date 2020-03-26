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
 * Note
 */
class Note extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Content
     *
     * @var string
     */
    protected $content = '';

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
     * Presentation
     *
     * @var \Tollwerk\TwEvents\Domain\Model\Presentation
     */
    protected $presentation;

    /**
     * Returns the content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content
     *
     * @param string $content
     *
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
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

    /**
     * Return the presentation
     *
     * @return Presentation Presentation
     */
    public function getPresentation(): Presentation
    {
        return $this->presentation;
    }
}
