<?php

/**
 * Languages to choose from for an event
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tests\Unit\Tca;

use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tollwerk\TwEvents\Tca\LanguageItems;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Languages to choose from for an event
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
#[RequiresPhpExtension('intl')]
final class LanguageItemsTest extends UnitTestCase
{
    /**
     * Languages are offered by their English name, with their code of ISO 639-1 as value, ordered by name
     *
     * @return void
     */
    public function testLanguagesAreOfferedByNameAndCode(): void
    {
        $params = ['items' => []];

        new LanguageItems()->addItems($params);

        self::assertContains(['label' => 'English', 'value' => 'en'], $params['items']);
        self::assertContains(['label' => 'German', 'value' => 'de'], $params['items']);
        self::assertContains(['label' => 'Chamorro', 'value' => 'ch'], $params['items']);
        $labels = array_column($params['items'], 'label');
        $sorted = $labels;
        natcasesort($sorted);
        self::assertSame(array_values($sorted), $labels);
        foreach ($params['items'] as $item) {
            self::assertMatchesRegularExpression('/^[a-z]{2}$/', $item['value']);
        }
    }
}
