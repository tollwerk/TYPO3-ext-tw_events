<?php

/**
 * Keeps the profile of a person who appears in events, and forgets a deleted person
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Person;

use Doctrine\DBAL\Exception as DBALException;
use Tollwerk\TwAccounts\Event\AccountDeletedEvent;
use Tollwerk\TwAccounts\Event\KeepProfileEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * Keeps the profile of a person who appears in events, and forgets a deleted person
 *
 * A person who speaks in a session, organises an event or wrote a coverage or a note appears in a programme, so the
 * profile stays when the person deletes the account, as a person without an account. This holds for hidden records
 * too, which the editors may still publish. If the profile goes, the references from deleted records go with it.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final readonly class PersonReferences
{
    /**
     * Tables that relate records to persons, with the table of the record
     *
     * @var array<string, string>
     */
    private const array RELATIONS = [
        'tx_twevents_session_speaker_mm' => 'tx_twevents_session',
        'tx_twevents_event_organizer_mm' => 'tx_twevents_event',
    ];

    /**
     * Tables whose field "author" names a person
     *
     * @var list<string>
     */
    private const array AUTHORED = ['tx_twevents_coverage', 'tx_twevents_note'];

    /**
     * Constructor
     *
     * @param ConnectionPool $connectionPool Database
     */
    public function __construct(private ConnectionPool $connectionPool) {}

    /**
     * Keep the profile of a person who appears in events
     *
     * @param KeepProfileEvent $event Question whether the profile stays
     *
     * @return void
     *
     * @throws DBALException If the database cannot be read
     */
    #[AsEventListener('tw-events/keep-profile')]
    public function keepProfile(KeepProfileEvent $event): void
    {
        $uid = $event->person['uid'] ?? null;
        if ($event->isKept() || !is_numeric($uid) || !$this->appears((int) $uid)) {
            return;
        }

        $event->keep();
    }

    /**
     * Forget a person whose profile went with the account
     *
     * @param AccountDeletedEvent $event Deleted account
     *
     * @return void
     *
     * @throws DBALException If the database cannot be written
     */
    #[AsEventListener('tw-events/forget-person')]
    public function forgetPerson(AccountDeletedEvent $event): void
    {
        if ($event->kept) {
            return;
        }

        foreach (array_keys(self::RELATIONS) as $relation) {
            $this->connectionPool->getConnectionForTable($relation)
                ->delete($relation, ['uid_foreign' => $event->uid]);
        }

        foreach (self::AUTHORED as $table) {
            $this->connectionPool->getConnectionForTable($table)
                ->update($table, ['author' => 0], ['author' => $event->uid]);
        }
    }

    /**
     * Whether a person appears in a record that is not deleted, hidden or not
     *
     * @param integer $uid UID of the person
     *
     * @return boolean
     *
     * @throws DBALException If the database cannot be read
     */
    private function appears(int $uid): bool
    {
        foreach (self::RELATIONS as $relation => $table) {
            $query = $this->query($table);
            $query->count('record.uid')->from($relation, 'relation')
                ->join('relation', $table, 'record', 'record.uid = relation.uid_local')
                ->where(
                    $query->expr()->eq('relation.uid_foreign', $query->createNamedParameter($uid)),
                    $query->expr()->eq('record.deleted', 0),
                );
            if ($query->executeQuery()->fetchOne() > 0) {
                return true;
            }
        }

        foreach (self::AUTHORED as $table) {
            $query = $this->query($table);
            $query->count('uid')->from($table)->where(
                $query->expr()->eq('author', $query->createNamedParameter($uid)),
                $query->expr()->eq('deleted', 0),
            );
            if ($query->executeQuery()->fetchOne() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Query without restrictions, which checks "deleted" itself
     *
     * @param string $table Table
     *
     * @return QueryBuilder
     */
    private function query(string $table): QueryBuilder
    {
        $query = $this->connectionPool->getQueryBuilderForTable($table);
        $query->getRestrictions()->removeAll();

        return $query;
    }
}
