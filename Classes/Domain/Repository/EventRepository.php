<?php

namespace Tollwerk\TwEvents\Domain\Repository;


use Tollwerk\TwBase\Domain\Repository\Traits\DebuggableRepositoryTrait;
use Tollwerk\TwBase\Domain\Repository\Traits\StoragePidsIgnoringRepositoryTrait;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
 * The repository for Events
 */
class EventRepository extends AbstractRepository
{
    use DebuggableRepositoryTrait, StoragePidsIgnoringRepositoryTrait;

    /**
     * Return all dates before a particular date
     *
     * @param \DateTimeInterface $date Date
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findPastEvents(\DateTimeInterface $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->lessThan('eventStart', $date->format('Y-m-d H:i:s')));
        $query->setOrderings(['eventStart' => QueryInterface::ORDER_DESCENDING]);

        return $query->execute();
    }

    /**
     * Return all dates after a particular date
     *
     * @param \DateTimeInterface $date Date
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findFutureEvents(\DateTimeInterface $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->greaterThanOrEqual('eventStart', $date->format('Y-m-d H:i:s')));
        $query->setOrderings(['eventStart' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
    }
}
