<?php

/**
 * Returns the heading of the page: the record it shows, or its own title
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\ViewHelpers\Page;

use Tollwerk\TwBase\ViewHelpers\TypedArguments;
use Tollwerk\TwEvents\Page\RecordTitleProvider;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the heading of the page: the record it shows, or its own title
 *
 * The content of the page must be rendered before, since the detail view of a record names it.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class HeadingViewHelper extends AbstractViewHelper
{
    use TypedArguments;

    /**
     * Constructor
     *
     * @param RecordTitleProvider $recordTitleProvider Title of the record
     */
    public function __construct(private readonly RecordTitleProvider $recordTitleProvider) {}

    /**
     * Register the arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('default', 'string', 'Heading without a record, usually the title of the page', true);
    }

    /**
     * Return the heading
     *
     * @return string
     */
    public function render(): string
    {
        $title = $this->recordTitleProvider->getTitle();

        return ($title !== '') ? $title : $this->stringArgument('default') ?? '';
    }
}
