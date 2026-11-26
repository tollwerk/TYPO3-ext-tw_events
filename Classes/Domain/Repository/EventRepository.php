<?php

/**
 * Events
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

use DateTimeImmutable;
use Doctrine\DBAL\Exception as DBALException;
use Override;
use Tollwerk\TwEvents\Domain\LocalTime;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Person;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Events
 *
 * @extends Repository<Event>
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class EventRepository extends Repository
{
    use TagsTables;

    /**
     * Events from the latest on
     *
     * @var array<non-empty-string, QueryInterface::ORDER_*>
     */
    #[Override]
    protected $defaultOrderings = ['start' => QueryInterface::ORDER_DESCENDING];

    /**
     * Constructor
     *
     * @param ConnectionPool $connectionPool Connections, for the time zones of the events
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
     * Events of a list
     *
     * An event is over once its end, or without one its start, has passed in its time zone, as for its phase.
     *
     * @param string                 $scope  "upcoming" for events that are not over and not cancelled, soonest first;
     *                                       "cancelled" for cancelled events that are not over, soonest first; "past"
     *                                       for events that are over, latest first; anything else for all events,
     *                                       latest first
     * @param list<integer>          $series Uids of the series to show, or an empty list for all series
     * @param integer                $limit  Maximum number of events, or 0 for all
     * @param DateTimeImmutable|null $now    Time, or NULL for now
     *
     * @return QueryResultInterface<integer, Event>
     *
     * @throws InvalidQueryException If the query is invalid
     * @throws DBALException If the time zones of the events cannot be read
     */
    public function findForList(
        string $scope,
        array $series = [],
        int $limit = 0,
        ?DateTimeImmutable $now = null,
    ): QueryResultInterface {
        $query = $this->createQuery();
        $this->tagTables($query);
        $now ??= new DateTimeImmutable();
        $constraints = [];
        if (($scope === 'upcoming') || ($scope === 'cancelled')) {
            $constraints[] = $this->overOrNot($query, $now, false);
            $constraints[] = ($scope === 'cancelled')
                ? $query->equals('status', Event::STATUS_CANCELLED)
                : $query->logicalNot($query->equals('status', Event::STATUS_CANCELLED));
            $query->setOrderings(['start' => QueryInterface::ORDER_ASCENDING]);
        } elseif ($scope === 'past') {
            $constraints[] = $this->overOrNot($query, $now, true);
        }

        if ($series !== []) {
            $constraints[] = $query->in('series', $series);
        }

        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        if ($limit > 0) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * When a list changes next because an event in it is over
     *
     * An upcoming or cancelled event leaves its list once it is over, and joins the past events. Other lists do not
     * change by time.
     *
     * @param string                 $scope  Scope of the list, as for findForList()
     * @param list<integer>          $series Uids of the series to show, or an empty list for all series
     * @param DateTimeImmutable|null $now    Time, or NULL for now
     *
     * @return DateTimeImmutable|null End of the event that is over next, in its time zone, or NULL if the list does not
     *                                change by time
     *
     * @throws InvalidQueryException If the query is invalid
     * @throws DBALException If the time zones of the events cannot be read
     */
    public function findNextListChange(
        string $scope,
        array $series = [],
        ?DateTimeImmutable $now = null,
    ): ?DateTimeImmutable {
        if (!\in_array($scope, ['upcoming', 'cancelled', 'past'], true)) {
            return null;
        }

        $query = $this->createQuery();
        $this->tagTables($query);
        $now ??= new DateTimeImmutable();
        $constraints = [$this->overOrNot($query, $now, false)];
        if ($scope === 'upcoming') {
            $constraints[] = $query->logicalNot($query->equals('status', Event::STATUS_CANCELLED));
        } elseif ($scope === 'cancelled') {
            $constraints[] = $query->equals('status', Event::STATUS_CANCELLED);
        }

        if ($series !== []) {
            $constraints[] = $query->in('series', $series);
        }

        // Ends in different time zones only compare as points in time
        $change = null;
        foreach ($query->matching($query->logicalAnd(...$constraints))->execute() as $event) {
            $end = $event->getEndDate() ?? $event->getStartDate();
            if ((!($end instanceof DateTimeImmutable)) || ($change instanceof DateTimeImmutable && ($end >= $change))) {
                continue;
            }

            $change = $end;
        }

        return $change;
    }

    /**
     * Event that has a page of its own
     *
     * @param integer $page Uid of the page
     *
     * @return Event|null Event, or NULL if no event has this page
     */
    public function findOneByPage(int $page): ?Event
    {
        $query = $this->createQuery();
        $this->tagTables($query);
        $event = $query->matching($query->equals('page', $page))->setLimit(1)->execute()->getFirst();

        return ($event instanceof Event) ? $event : null;
    }

    /**
     * Events a person organises
     *
     * @param Person $person Person
     *
     * @return QueryResultInterface<integer, Event>
     *
     * @throws InvalidQueryException If a query is invalid
     */
    public function findByOrganizer(Person $person): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->tagTables($query);

        return $query->matching($query->contains('organizers', $person))->execute();
    }

    /**
     * Events at a venue
     *
     * @param Organization $location Venue
     *
     * @return QueryResultInterface<integer, Event>
     *
     * @throws InvalidQueryException If a query is invalid
     */
    public function findByLocation(Organization $location): QueryResultInterface
    {
        $query = $this->createQuery();
        $this->tagTables($query);

        return $query->matching($query->equals('location', $location))->execute();
    }

    /**
     * Events that are over, or that are not: their end, or without one their start, has passed in their time zone
     *
     * The database holds the wall-clock times of the events, so the time compares with them in each time zone in use.
     * Both conditions name the missing end, as the negation of a comparison with NULL matches nothing.
     *
     * @param QueryInterface<Event> $query Query
     * @param DateTimeImmutable     $now   Time
     * @param boolean               $over  Whether the events are over
     *
     * @return ConstraintInterface
     *
     * @throws InvalidQueryException If the query is invalid
     * @throws DBALException If the time zones of the events cannot be read
     */
    private function overOrNot(QueryInterface $query, DateTimeImmutable $now, bool $over): ConstraintInterface
    {
        $zones = [];
        foreach ($this->timezones() as $timezone) {
            $local = LocalTime::stored($now->setTimezone(LocalTime::zone($timezone)));
            $zones[] = $query->logicalAnd(
                $query->equals('timezone', $timezone),
                $query->logicalOr(
                    $query->logicalAnd(
                        $query->logicalNot($query->equals('end', null)),
                        $over ? $query->lessThan('end', $local) : $query->greaterThanOrEqual('end', $local),
                    ),
                    $query->logicalAnd(
                        $query->equals('end', null),
                        $over ? $query->lessThan('start', $local) : $query->greaterThanOrEqual('start', $local),
                    ),
                ),
            );
        }

        // Without events, no event is over or not
        return ($zones === []) ? $query->equals('uid', 0) : $query->logicalOr(...$zones);
    }

    /**
     * Time zones of the events
     *
     * @return list<string>
     *
     * @throws DBALException If the query fails
     */
    private function timezones(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_twevents_event');
        $queryBuilder->getRestrictions()->removeAll();
        $timezones = $queryBuilder->selectLiteral('DISTINCT timezone')->from('tx_twevents_event')
            ->executeQuery()
            ->fetchFirstColumn();

        return array_values(array_map(strval(...), array_filter($timezones, \is_scalar(...))));
    }
}
