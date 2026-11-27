<?php

/**
 * Coordinates of organisations as editors save them
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
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Coordinates of organisations as editors save them
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class OrganizationCoordinatesTest extends FunctionalTestCase
{
    /**
     * System extensions tw_base needs
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
     * Set up an editor and the venue tollwerk at 49.452031, 11.076751
     *
     * @return void
     *
     * @throws RuntimeException If the editor cannot be set up
     * @throws ContainerExceptionInterface If the language service is not available
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/Coordinates.csv');
        $user = $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = $this->get(LanguageServiceFactory::class)->createFromUserPreferences($user);
    }

    /**
     * Forget the editor, whom tests that run after it in the same process would take as theirs
     *
     * @return void
     */
    #[Override]
    protected function tearDown(): void
    {
        unset($GLOBALS['BE_USER'], $GLOBALS['LANG']);
        parent::tearDown();
    }

    /**
     * Inputs and the coordinates they save
     *
     * @return array<string, array{string, string}>
     */
    public static function inputs(): array
    {
        return [
            'six decimals' => ['49.475159', '11.106781'],
            'decimal comma and sign' => ['49,475159', '+11.106781'],
            'more decimals' => ['49.4751594', '11.1067806'],
        ];
    }

    /**
     * Coordinates keep the six decimals of the database
     *
     * @param string $latitude  Input of the latitude
     * @param string $longitude Input of the longitude
     *
     * @return void
     *
     * @throws DBALException If the organisation cannot be read
     * @throws ContainerExceptionInterface If a service is missing
     */
    #[DataProvider('inputs')]
    public function testCoordinatesKeepSixDecimals(string $latitude, string $longitude): void
    {
        $this->save(['latitude' => $latitude, 'longitude' => $longitude]);

        self::assertSame([49.475159, 11.106781], $this->coordinates());
        self::assertSame([], $this->get(FlashMessageService::class)->getMessageQueueByIdentifier()->getAllMessages());
    }

    /**
     * An input that is no coordinate keeps the previous value and tells the editor
     *
     * @return void
     *
     * @throws DBALException If the organisation cannot be read
     * @throws ContainerExceptionInterface If a service is missing
     */
    public function testInvalidCoordinatesKeepThePreviousValue(): void
    {
        $this->save(['latitude' => '91', 'longitude' => '11.1 E']);

        self::assertSame([49.452031, 11.076751], $this->coordinates());
        $messages = $this->get(FlashMessageService::class)->getMessageQueueByIdentifier()->getAllMessages();
        self::assertSame(
            [
                '"91" is no number from -90 to 90. The previous value stays.',
                '"11.1 E" is no number from -180 to 180. The previous value stays.',
            ],
            array_map(static fn(FlashMessage $message): string => $message->getMessage(), $messages),
        );
    }

    /**
     * An empty input removes the coordinate
     *
     * @return void
     *
     * @throws DBALException If the organisation cannot be read
     * @throws ContainerExceptionInterface If a service is missing
     */
    public function testEmptyInputRemovesTheCoordinate(): void
    {
        $this->save(['latitude' => '', 'longitude' => '']);

        self::assertSame([null, null], $this->coordinates());
    }

    /**
     * Save the organisation as the editor
     *
     * @param array<string, string> $fields Fields
     *
     * @return void
     *
     * @throws ContainerExceptionInterface If a service is missing
     */
    private function save(array $fields): void
    {
        $dataHandler = $this->get(DataHandler::class);
        $dataHandler->start(['tx_twevents_organization' => [1 => $fields]], []);
        $dataHandler->process_datamap();
    }

    /**
     * Latitude and longitude of the organisation
     *
     * @return array{float|null, float|null}
     *
     * @throws DBALException If the organisation cannot be read
     */
    private function coordinates(): array
    {
        $row = $this->getConnectionPool()->getConnectionForTable('tx_twevents_organization')
            ->select(['latitude', 'longitude'], 'tx_twevents_organization', ['uid' => 1])
            ->fetchAssociative();

        $coordinate = static fn(mixed $value): ?float => is_numeric($value) ? (float) $value : null;

        return [$coordinate($row['latitude'] ?? null), $coordinate($row['longitude'] ?? null)];
    }
}
