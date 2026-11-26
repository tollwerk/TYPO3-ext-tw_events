<?php

/**
 * Events of the lists and when the lists change
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tests\Functional;

use DateInvalidTimeZoneException;
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Exception as DBALException;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Container\ContainerExceptionInterface;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Events of the lists and when the lists change
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class EventRepositoryTest extends FunctionalTestCase
{
    /**
     * System extensions the extensions need
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin'];

    /**
     * Extensions of the project
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $testExtensionsToLoad = ['tollwerk/tw-base', 'tollwerk/tw-accounts', 'tollwerk/tw-events'];

    /**
     * Set up the events: Meetup #8 is over, Summit 2099 is cancelled and ends on 2 June 2099 at 18:00
     *
     * @return void
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        // The repository runs outside of a request; a request left by another test would make it a frontend query
        unset($GLOBALS['TYPO3_REQUEST']);
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Frontend.csv');
    }

    /**
     * Lists change when an upcoming or cancelled event is over
     *
     * @return array<string, array{string, list<integer>, string|null}>
     */
    public static function listChanges(): array
    {
        return [
            'upcoming without the cancelled events' => ['upcoming', [], null],
            'cancelled' => ['cancelled', [], '2099-06-02 18:00'],
            'cancelled of another series' => ['cancelled', [1], null],
            'past' => ['past', [], '2099-06-02 18:00'],
            'all' => ['all', [], null],
        ];
    }

    /**
     * A list changes when the next event in it is over, or not by time
     *
     * @param string        $scope  Scope of the list
     * @param list<integer> $series Uids of the series
     * @param string|null   $change Expected change
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the repository is not available
     * @throws InvalidQueryException If the query is invalid
     * @throws DBALException If a query fails
     */
    #[DataProvider('listChanges')]
    public function testNextListChange(string $scope, array $series, ?string $change): void
    {
        self::assertSame(
            $change,
            $this->get(EventRepository::class)->findNextListChange($scope, $series)?->format('Y-m-d H:i'),
        );
    }

    /**
     * Times, and the upcoming and past events then, by title
     *
     * @return array<string, array{string, string, list<string>, list<string>}>
     */
    public static function listsInTimeZones(): array
    {
        return [
            'before both' => [
                '2030-03-01 17:00',
                'Europe/Berlin',
                ['Without end', 'In New York'],
                ['Meetup #8'],
            ],
            'after the start of an event without end' => [
                '2030-03-01 18:30',
                'Europe/Berlin',
                ['In New York'],
                ['Without end', 'Meetup #8'],
            ],
            'after the end in New York by the clock of Berlin' => [
                '2030-03-02 21:30',
                'Europe/Berlin',
                ['In New York'],
                ['Without end', 'Meetup #8'],
            ],
            'after the end in New York' => [
                '2030-03-02 21:30',
                'America/New_York',
                [],
                ['In New York', 'Without end', 'Meetup #8'],
            ],
        ];
    }

    /**
     * An event is over once its end, or without one its start, has passed in its time zone, as for its phase
     *
     * @param string       $now      Time
     * @param string       $timezone Time zone of the time
     * @param list<string> $upcoming Upcoming events
     * @param list<string> $past     Past events
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the repository is not available
     * @throws InvalidQueryException If the query is invalid
     * @throws DBALException If the events cannot be added or their time zones read
     * @throws DateInvalidTimeZoneException If a time zone is invalid
     * @throws DateMalformedStringException If a date is invalid
     */
    #[DataProvider('listsInTimeZones')]
    public function testListsFollowThePhasesOfTheEvents(
        string $now,
        string $timezone,
        array $upcoming,
        array $past,
    ): void {
        $this->addEventsInTimeZones();
        $time = new DateTimeImmutable($now, new DateTimeZone($timezone));
        $repository = $this->get(EventRepository::class);

        self::assertSame($upcoming, $this->titles($repository->findForList('upcoming', [], 0, $time)));
        self::assertSame($past, $this->titles($repository->findForList('past', [], 0, $time)));
        foreach ($repository->findForList('upcoming', [], 0, $time) as $event) {
            self::assertSame('upcoming', $event->getPhase($time));
        }
    }

    /**
     * The list of upcoming events changes when the next of them is over in its time zone
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the repository is not available
     * @throws InvalidQueryException If the query is invalid
     * @throws DBALException If the events cannot be added or their time zones read
     */
    public function testNextListChangeIsInTheTimeZoneOfTheEvent(): void
    {
        $this->addEventsInTimeZones();
        $repository = $this->get(EventRepository::class);
        $berlin = new DateTimeZone('Europe/Berlin');

        $change = $repository->findNextListChange('upcoming', [], new DateTimeImmutable('2030-03-01 17:00', $berlin));
        self::assertSame('2030-03-01T18:00:00+01:00', $change?->format('c'));
        $change = $repository->findNextListChange('upcoming', [], new DateTimeImmutable('2030-03-02 21:30', $berlin));
        self::assertSame('2030-03-02T21:00:00-05:00', $change?->format('c'));
    }

    /**
     * Add an event without end on 1 March 2030 at 18:00 in Berlin, and one from 18:00 to 21:00 on 2 March 2030 in
     * New York
     *
     * @return void
     *
     * @throws DBALException If the events cannot be added
     */
    private function addEventsInTimeZones(): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('tx_twevents_event');
        $connection->insert('tx_twevents_event', [
            'uid' => 10,
            'pid' => 6,
            'title' => 'Without end',
            'slug' => 'without-end',
            'start' => '2030-03-01 18:00:00',
            'end' => null,
            'timezone' => 'Europe/Berlin',
        ]);
        $connection->insert('tx_twevents_event', [
            'uid' => 11,
            'pid' => 6,
            'title' => 'In New York',
            'slug' => 'in-new-york',
            'start' => '2030-03-02 18:00:00',
            'end' => '2030-03-02 21:00:00',
            'timezone' => 'America/New_York',
        ]);
    }

    /**
     * Titles of events
     *
     * @param iterable<Event> $events Events
     *
     * @return list<string>
     */
    private function titles(iterable $events): array
    {
        $titles = [];
        foreach ($events as $event) {
            $titles[] = $event->getTitle();
        }

        return $titles;
    }
}
