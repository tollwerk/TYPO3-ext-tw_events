<?php

/**
 * data
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Domain\Repository
 * @author     Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2020 Joschi Kuphal <joschi@kuphal.net>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Tollwerk\TwEvents\Domain\Repository;

use DateTimeInterface;
use Tollwerk\TwEvents\Domain\Repository\Traits\DebuggableRepositoryTrait;
use Tollwerk\TwEvents\Domain\Model\Event;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * The repository for Events
 */
class EventRepository extends AbstractRepository
{
    use DebuggableRepositoryTrait;

    /**
     * Define TYPO3 Query Settings
     *
     * @return void
     */
    public function initializeObject()
    {
        /**
         * TYPO3 query settings
         *
         * @var Typo3QuerySettings $querySettings Typo3QuerySettings
         */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Return all dates before a particular date
     *
     * @param DateTimeInterface $date Date
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findPastEvents(DateTimeInterface $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->lessThan('eventStart', $date->format('Y-m-d H:i:s')));
        $query->setOrderings(['eventStart' => QueryInterface::ORDER_DESCENDING]);

        return $query->execute();
    }

    /**
     * Return all dates after a particular date
     *
     * @param DateTimeInterface $date Date
     * @param bool $excludeCancelled  Exclude cancelled events
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findFutureEvents(DateTimeInterface $date, bool $excludeCancelled = true): QueryResultInterface
    {
        $query       = $this->createQuery();
        $constraints = [$query->greaterThanOrEqual('eventStart', $date->format('Y-m-d H:i:s'))];
        if ($excludeCancelled) {
            $constraints[] = $query->lessThan('status', Event::STATUS_CANCELLED);
        }
        $query->matching($query->logicalAnd($constraints));
        $query->setOrderings(['eventStart' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
    }

    /**
     * Return all cancelled dates after a particular date
     *
     * @param DateTimeInterface $date Date
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findCancelledEvents(DateTimeInterface $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('eventStart', $date->format('Y-m-d H:i:s')),
                $query->equals('status', Event::STATUS_CANCELLED)
            )
        );
        $query->setOrderings(['eventStart' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
    }
}
