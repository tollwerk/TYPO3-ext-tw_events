<?php

/**
 * Describes a list of events, people or organisations in the JSON-LD graph of the page
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\ViewHelpers\StructuredData;

use Doctrine\DBAL\Exception;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwBase\ViewHelpers\TypedArguments;
use Tollwerk\TwEvents\StructuredData\SchemaBuilder;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use UnexpectedValueException;

/**
 * Describes a list of events, people or organisations in the JSON-LD graph of the page
 *
 * Each entry names its record and links its page. A page without a main entity of its own, such as the page of all
 * events, can make its lists its main entity.
 *
 * Usage: <events:structuredData.itemList records="{events}" name="Upcoming events" />
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class ItemListViewHelper extends AbstractViewHelper
{
    use TypedArguments;

    /**
     * Constructor
     *
     * @param SchemaBuilder $schemaBuilder Describes records
     */
    public function __construct(private readonly SchemaBuilder $schemaBuilder) {}

    /**
     * Register the arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('records', 'iterable', 'Events, people or organisations', true);
        $this->registerArgument('name', 'string', 'Name of the list', false, '');
    }

    /**
     * Describe the list
     *
     * @return string Nothing
     *
     * @throws Exception If the query for a page fails
     * @throws InvalidRouteArgumentsException If an address cannot be built
     * @throws InvalidArgumentException If an id cannot be built
     * @throws UnexpectedValueException If a page cannot be read
     */
    public function render(): string
    {
        $request = $this->request();
        $records = $this->arguments['records'] ?? null;
        if ($request instanceof ServerRequestInterface && is_iterable($records)) {
            $this->schemaBuilder->itemList($request, $records, $this->stringArgument('name') ?? '');
        }

        return '';
    }
}
