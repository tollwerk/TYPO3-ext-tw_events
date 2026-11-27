<?php

/**
 * Database schema of events and their records
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

use Doctrine\DBAL\Exception;
use Override;
use Psr\Container\ContainerExceptionInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Database schema of events and their records
 *
 * TYPO3 derives the tables of tw_events from their TCA; this checks that it does so for every table and relation.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class DatabaseSchemaTest extends FunctionalTestCase
{
    /**
     * Tables of the records of tw_events
     *
     * @var list<string>
     */
    private const array TABLES = [
        'tx_twevents_coverage',
        'tx_twevents_event',
        'tx_twevents_note',
        'tx_twevents_organization',
        'tx_twevents_participation',
        'tx_twevents_series',
        'tx_twevents_session',
    ];

    /**
     * System extensions tw-base needs
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin', 'rte_ckeditor'];

    /**
     * Extensions under test, with tw-base, which tw-events needs
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
     * Every record type of tw_events has a TCA schema
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the schema factory is missing from the container
     */
    public function testEveryRecordTypeHasASchema(): void
    {
        $tcaSchemaFactory = $this->get(TcaSchemaFactory::class);

        foreach (self::TABLES as $table) {
            self::assertTrue($tcaSchemaFactory->has($table), $table);
        }
    }

    /**
     * Records with all their relations fit into the tables TYPO3 derives from the TCA
     *
     * @return void
     */
    public function testRecordsWithTheirRelationsAreStored(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Records.csv');

        $this->assertCSVDataSet(__DIR__ . '/Fixtures/Records.csv');
    }

    /**
     * The speakers of a session are found through its relation table
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If the connection pool is missing from the container
     * @throws Exception If the query fails
     */
    public function testSpeakersAreRelatedToTheirSession(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Records.csv');
        $queryBuilder = $this->get(ConnectionPool::class)->getQueryBuilderForTable('fe_users');

        $familyName = $queryBuilder
            ->select('person.last_name')
            ->from('fe_users', 'person')
            ->join(
                'person',
                'tx_twevents_session_speaker_mm',
                'mm',
                $queryBuilder->expr()->eq('mm.uid_foreign', $queryBuilder->quoteIdentifier('person.uid')),
            )
            ->where($queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->createNamedParameter(1)))
            ->executeQuery()
            ->fetchOne();

        self::assertSame('Lovelace', $familyName);
    }
}
