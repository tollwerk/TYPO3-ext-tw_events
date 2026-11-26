<?php

/**
 * Participations of organisations in events
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
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Participation;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Participations of organisations in events
 *
 * @extends Repository<Participation>
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class ParticipationRepository extends Repository
{
    use TagsTables;

    /**
     * Participations from the latest event on
     *
     * @var array<non-empty-string, QueryInterface::ORDER_*>
     */
    #[Override]
    protected $defaultOrderings = ['event.start' => QueryInterface::ORDER_DESCENDING];

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
     * Participations of an organisation in events that are visible
     *
     * @param Organization $organization Organisation
     *
     * @return QueryResultInterface<integer, Participation>
     *
     * @throws InvalidQueryException If a query is invalid
     */
    public function findByOrganization(Organization $organization): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->tagTables($query, 'tx_twevents_event');

        return $query->matching($query->logicalAnd(
            $query->equals('organization', $organization),
            // The join of the event carries its enable fields, so a hidden event leaves no uid
            $query->greaterThan('event.uid', 0),
        ))->execute();
    }
}
