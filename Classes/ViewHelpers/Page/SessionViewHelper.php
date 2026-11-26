<?php

/**
 * Returns the session the page shows
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

use Override;
use Tollwerk\TwEvents\Domain\Model\Session;
use Tollwerk\TwEvents\Page\ShownEvent;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the session the page shows
 *
 * The content of the page must be rendered before, since the views of an event set it.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class SessionViewHelper extends AbstractViewHelper
{
    /**
     * The result is a record, not markup
     *
     * @var boolean
     */
    #[Override]
    protected $escapeOutput = false;

    /**
     * Constructor
     *
     * @param ShownEvent $shownEvent Event and session the page shows
     */
    public function __construct(private readonly ShownEvent $shownEvent) {}

    /**
     * Return the session
     *
     * @return Session|null Session, or NULL if the page shows none
     */
    public function render(): ?Session
    {
        return $this->shownEvent->getSession();
    }
}
