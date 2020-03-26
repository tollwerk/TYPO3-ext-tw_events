<?php

/**
 * data
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Domain\Repository
 * @author     Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright  Copyright © 2019 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2019 Joschi Kuphal <joschi@kuphal.net>
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
use Tollwerk\TwBase\Domain\Repository\Traits\DebuggableRepositoryTrait;
use Tollwerk\TwBase\Domain\Repository\Traits\StoragePidsIgnoringRepositoryTrait;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * The repository for Events
 */
class EventRepository extends AbstractRepository
{
    use DebuggableRepositoryTrait, StoragePidsIgnoringRepositoryTrait;

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
     *
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findFutureEvents(DateTimeInterface $date): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->greaterThanOrEqual('eventStart', $date->format('Y-m-d H:i:s')));
        $query->setOrderings(['eventStart' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
    }
}
