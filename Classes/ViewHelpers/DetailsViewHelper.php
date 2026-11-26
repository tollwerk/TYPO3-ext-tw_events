<?php

/**
 * Returns the details of the hosts, speakers or sponsors of an event that the site settings choose
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

use Override;
use Psr\Container\ContainerExceptionInterface;
use Tollwerk\TwBase\ViewHelpers\TypedArguments;
use Tollwerk\TwEvents\Page\Details;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the details of the hosts, speakers or sponsors of an event that the site settings choose
 *
 * People: photo (bool), texts and contacts (lists in their order). Organisations: logo, address and description
 * (bool), contacts (list in its order).
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class DetailsViewHelper extends AbstractViewHelper
{
    use TypedArguments;

    /**
     * The result is a list, not markup
     *
     * @var boolean
     */
    #[Override]
    protected $escapeOutput = false;

    /**
     * Constructor
     *
     * @param Details $details Details by the site settings
     */
    public function __construct(private readonly Details $details) {}

    /**
     * Register the arguments
     *
     * @return void
     */
    #[Override]
    public function initializeArguments(): void
    {
        $this->registerArgument('of', 'string', 'Group: "hosts", "speakers" or "sponsors"', true);
    }

    /**
     * Return the details
     *
     * @return array<string, boolean|list<string>> Details, or an empty list for an unknown group
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    public function render(): array
    {
        return $this->details->of($this->request(), (string) $this->stringArgument('of'));
    }
}
