<?php

/**
 * Address segment of a session: title, date and time of its start
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

use InvalidArgumentException;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Container\ContainerExceptionInterface;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Schema\Exception\UndefinedSchemaException;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Address segment of a session: title, date and time of its start
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class SessionSlugTest extends FunctionalTestCase
{
    /**
     * System extensions tw_base needs
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
     * Records and their segments
     *
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function records(): array
    {
        return [
            'start from the database' => [
                ['title' => 'Exclusive design (Foyer)', 'start' => '2019-11-16 15:00:00'],
                'exclusive-design-foyer-2019-11-16-1500',
            ],
            'start from the form' => [
                ['title' => 'Exclusive design (Foyer)', 'start' => '2019-11-16T15:00:00'],
                'exclusive-design-foyer-2019-11-16-1500',
            ],
            'start in UTC notation' => [
                ['title' => 'Exclusive design (Foyer)', 'start' => '2019-11-16T15:00:00Z'],
                'exclusive-design-foyer-2019-11-16-1500',
            ],
            'start with an offset' => [
                ['title' => 'Exclusive design', 'start' => '2019-11-16T15:00:00+01:00'],
                'exclusive-design-2019-11-16-1500',
            ],
            'no start' => [['title' => 'Lunch / Break', 'start' => null], 'lunch-break'],
            'title like a date' => [
                ['title' => '2019-11-16 15:00 in review', 'start' => '2019-11-16 15:00:00'],
                '2019-11-16-1500-in-review-2019-11-16-1500',
            ],
        ];
    }

    /**
     * The segment joins the title and the start, as year, month, day and time
     *
     * @param array<string, mixed> $record Session
     * @param string               $slug   Segment
     *
     * @return void
     *
     * @throws InvalidArgumentException If the helper cannot be created
     * @throws ContainerExceptionInterface If the schema factory is missing
     * @throws UndefinedSchemaException If sessions have no schema
     */
    #[DataProvider('records')]
    public function testSegmentJoinsTitleAndStart(array $record, string $slug): void
    {
        $config = $this->get(TcaSchemaFactory::class)->get('tx_twevents_session')->getField('slug')->getConfiguration();
        $helper = GeneralUtility::makeInstance(SlugHelper::class, 'tx_twevents_session', 'slug', $config);

        self::assertSame($slug, $helper->generate($record, 1));
    }
}
