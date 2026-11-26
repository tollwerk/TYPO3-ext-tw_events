<?php

/**
 * People: frontend users with the fields of tw-accounts
 *
 * A profile is public if the person organises a visible event, speaks in a visible session of a visible event or
 * makes the profile public on their own.
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

use Doctrine\DBAL\Exception as DBALException;
use InvalidArgumentException;
use Override;
use Tollwerk\TwEvents\Domain\Model\Person;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * People: frontend users with the fields of tw-accounts
 *
 * A profile is public if the person organises a visible event, speaks in a visible session of a visible event or
 * makes the profile public on their own.
 *
 * @extends Repository<Person>
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class PersonRepository extends Repository
{
    /**
     * Alphabetical order
     *
     * @var array<non-empty-string, QueryInterface::ORDER_*>
     */
    #[Override]
    protected $defaultOrderings = [
        'familyName' => QueryInterface::ORDER_ASCENDING,
        'givenName' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Constructor
     *
     * @param ConnectionPool $connectionPool Connections, for the people with a public profile
     */
    public function __construct(private readonly ConnectionPool $connectionPool)
    {
        parent::__construct();
    }

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
     * People with a public profile
     *
     * @return QueryResultInterface<integer, Person>
     *
     * @throws DBALException If the database cannot be read
     * @throws InvalidArgumentException If a query cannot be built
     * @throws InvalidQueryException If the query is invalid
     */
    public function findPublic(): QueryResultInterface
    {
        $uids = $this->publicUids();
        $query = $this->createQuery();

        return $query->matching($query->in('uid', [0, ...$uids]))->execute();
    }

    /**
     * UIDs of the people with a public profile, without regard to whether the person is visible
     *
     * @return list<integer>
     *
     * @throws DBALException If the database cannot be read
     */
    public function publicUids(): array
    {
        $flagged = $this->connectionPool->getQueryBuilderForTable('fe_users');
        $flagged->getRestrictions()->removeAll();
        $flagged->select('uid')->from('fe_users')->where(
            $flagged->expr()->eq('deleted', 0),
            $flagged->expr()->eq('tx_twaccounts_public', 1),
        );

        // The joined event and session carry their enable fields
        $speakers = $this->connectionPool->getQueryBuilderForTable('tx_twevents_session');
        $speakers->select('mm.uid_foreign AS uid')->from('tx_twevents_session_speaker_mm', 'mm')
            ->join('mm', 'tx_twevents_session', 'session', 'session.uid = mm.uid_local')
            ->join('session', 'tx_twevents_event', 'event', 'event.uid = session.event');
        $organizers = $this->connectionPool->getQueryBuilderForTable('tx_twevents_event');
        $organizers->select('mm.uid_foreign AS uid')->from('tx_twevents_event_organizer_mm', 'mm')
            ->join('mm', 'tx_twevents_event', 'event', 'event.uid = mm.uid_local');

        $uids = [];
        foreach ([$flagged, $speakers, $organizers] as $query) {
            foreach ($query->executeQuery()->fetchFirstColumn() as $uid) {
                $uids[] = is_numeric($uid) ? (int) $uid : 0;
            }
        }

        $uids = array_unique(array_filter($uids, static fn(int $uid): bool => $uid > 0));
        sort($uids);

        return $uids;
    }
}
