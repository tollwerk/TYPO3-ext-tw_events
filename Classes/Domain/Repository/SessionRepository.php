<?php

/**
 * Sessions
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Domain\Repository;

use Override;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Model\Session;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Sessions
 *
 * @extends Repository<Session>
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class SessionRepository extends Repository
{
    use TagsTables;

    /**
     * Sessions from the latest on
     *
     * @var array<non-empty-string, QueryInterface::ORDER_*>
     */
    #[Override]
    protected $defaultOrderings = ['start' => QueryInterface::ORDER_DESCENDING];

    /**
     * Find records in every folder
     *
     * @return void
     */
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Sessions a person speaks in, at events that are visible
     *
     * @param Person $person Person
     *
     * @return QueryResultInterface<integer, Session>
     *
     * @throws InvalidQueryException If a query is invalid
     */
    public function findBySpeaker(Person $person): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->tagTables($query, 'tx_twevents_event');

        return $query->matching($query->logicalAnd(
            $query->contains('speakers', $person),
            // The join of the event carries its enable fields, so a hidden event leaves no uid
            $query->greaterThan('event.uid', 0),
        ))->execute();
    }
}
