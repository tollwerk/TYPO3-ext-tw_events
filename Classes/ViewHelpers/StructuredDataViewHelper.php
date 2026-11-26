<?php

/**
 * Describes a record in the JSON-LD graph of the page
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\ViewHelpers;

use Doctrine\DBAL\Exception;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwBase\StructuredData\StructuredDataGraph;
use Tollwerk\TwBase\ViewHelpers\TypedArguments;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Model\Session;
use Tollwerk\TwEvents\StructuredData\SchemaBuilder;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use UnexpectedValueException;

/**
 * Describes a record in the JSON-LD graph of the page
 *
 * The graph goes into the head of the page through tw-base. The record is the main entity of the page, which a
 * description of the page can refer to.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class StructuredDataViewHelper extends AbstractViewHelper
{
    use TypedArguments;

    /**
     * Constructor
     *
     * @param SchemaBuilder       $schemaBuilder Describes records
     * @param StructuredDataGraph $graph         JSON-LD graph of the page
     */
    public function __construct(
        private readonly SchemaBuilder $schemaBuilder,
        private readonly StructuredDataGraph $graph,
    ) {}

    /**
     * Register the arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('record', 'object', 'Event, session, person or organisation', true);
    }

    /**
     * Describe the record
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
        $record = $this->arguments['record'] ?? null;
        if ($request instanceof ServerRequestInterface) {
            $id = match (true) {
                $record instanceof Event => $this->schemaBuilder->event($request, $record),
                $record instanceof Session => $this->schemaBuilder->session($request, $record),
                $record instanceof Person => $this->schemaBuilder->person($request, $record),
                $record instanceof Organization => $this->schemaBuilder->organization($request, $record),
                default => null,
            };
            if ($id !== null) {
                $this->graph->usePageEntity($id);
            }
        }

        return '';
    }
}
