<?php

/**
 * Profiles of persons who appear in events stay when the account is deleted
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

use Doctrine\DBAL\Exception as DBALException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\ContainerExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tollwerk\TwAccounts\Event\AccountDeletedEvent;
use Tollwerk\TwAccounts\Event\KeepProfileEvent;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Profiles of persons who appear in events stay when the account is deleted
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class PersonReferencesTest extends FunctionalTestCase
{
    /**
     * System extensions the extensions need
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin', 'rte_ckeditor'];

    /**
     * Extensions of the project
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $testExtensionsToLoad = [
        'tollwerk/tw-base',
        'tollwerk/tw-fields',
        'tollwerk/tw-antibot',
        'tollwerk/tw-accounts',
        'tollwerk/tw-events',
    ];

    /**
     * Set up Ada, who speaks and organises, and four more persons
     *
     * Grace wrote a hidden note, Alan spoke in a deleted session only, Hedy wrote a deleted coverage only, and Margaret
     * appears nowhere.
     *
     * @return void
     *
     * @throws DBALException If the records cannot be written
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Records.csv');
        $pool = $this->getConnectionPool();
        foreach ([2 => 'Grace', 3 => 'Alan', 4 => 'Hedy', 5 => 'Margaret'] as $uid => $name) {
            $pool->getConnectionForTable('fe_users')->insert('fe_users', [
                'uid' => $uid,
                'pid' => 1,
                'username' => 'person-' . $uid,
                'first_name' => $name,
            ]);
        }

        $pool->getConnectionForTable('tx_twevents_note')->insert('tx_twevents_note', [
            'uid' => 2, 'pid' => 1, 'session' => 1, 'author' => 2, 'hidden' => 1,
        ]);
        $pool->getConnectionForTable('tx_twevents_session')->insert('tx_twevents_session', [
            'uid' => 2, 'pid' => 1, 'event' => 1, 'title' => 'Removed', 'speakers' => 1, 'deleted' => 1,
        ]);
        $pool->getConnectionForTable('tx_twevents_session_speaker_mm')->insert('tx_twevents_session_speaker_mm', [
            'uid_local' => 2, 'uid_foreign' => 3,
        ]);
        $pool->getConnectionForTable('tx_twevents_coverage')->insert('tx_twevents_coverage', [
            'uid' => 2, 'pid' => 1, 'session' => 1, 'title' => 'Old', 'author' => 4, 'deleted' => 1,
        ]);
    }

    /**
     * Speakers, organisers and authors of records that are not deleted keep their profile, others do not
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the dispatcher is missing
     */
    #[Test]
    public function personsInEventsKeepTheirProfile(): void
    {
        $dispatcher = $this->get(EventDispatcherInterface::class);
        $kept = [];
        foreach ([1, 2, 3, 4, 5] as $uid) {
            $event = $dispatcher->dispatch(new KeepProfileEvent(['uid' => $uid, 'tx_twaccounts_public' => 0]));
            self::assertInstanceOf(KeepProfileEvent::class, $event);
            $kept[$uid] = $event->isKept();
        }

        self::assertSame([1 => true, 2 => true, 3 => false, 4 => false, 5 => false], $kept);
    }

    /**
     * A person whose profile went with the account leaves no references in deleted records
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the dispatcher is missing
     * @throws DBALException If the records cannot be read
     */
    #[Test]
    public function deletedPersonsLeaveNoReferences(): void
    {
        $dispatcher = $this->get(EventDispatcherInterface::class);
        $dispatcher->dispatch(new AccountDeletedEvent(3, false));
        $dispatcher->dispatch(new AccountDeletedEvent(4, false));
        $dispatcher->dispatch(new AccountDeletedEvent(1, true));
        $pool = $this->getConnectionPool();

        $speakers = $pool->getConnectionForTable('tx_twevents_session_speaker_mm')
            ->select(['uid_foreign'], 'tx_twevents_session_speaker_mm')->fetchFirstColumn();
        self::assertEquals([1], $speakers);
        $author = $pool->getConnectionForTable('tx_twevents_coverage')
            ->select(['author'], 'tx_twevents_coverage', ['uid' => 2])->fetchOne();
        self::assertEquals(0, $author);
        $organizers = $pool->getConnectionForTable('tx_twevents_event_organizer_mm')
            ->count('*', 'tx_twevents_event_organizer_mm', ['uid_foreign' => 1]);
        self::assertSame(1, $organizers);
    }
}
