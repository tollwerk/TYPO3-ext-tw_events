<?php

/**
 * Returns the address of the profile of a person
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\ViewHelpers\Uri;

use Doctrine\DBAL\Exception;
use Tollwerk\TwBase\ViewHelpers\TypedArguments;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Page\RecordUris;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the address of the profile of a person
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class PersonViewHelper extends AbstractViewHelper
{
    use TypedArguments;

    /**
     * Constructor
     *
     * @param RecordUris $recordUris Addresses of records
     */
    public function __construct(private readonly RecordUris $recordUris) {}

    /**
     * Register the arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('person', Person::class, 'Person', true);
    }

    /**
     * Return the address
     *
     * @return string Address, or an empty string without a page to show the record
     *
     * @throws Exception If the query for the page fails
     * @throws InvalidRouteArgumentsException If the route cannot be built
     */
    public function render(): string
    {
        $request = $this->request();
        $record = $this->arguments['person'] ?? null;

        if ((!$request instanceof \Psr\Http\Message\ServerRequestInterface) || !$record instanceof Person) {
            return '';
        }

        return $this->recordUris->person($request, $record);
    }
}
