<?php

/**
 * Title of the page that shows a record
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Page;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

/**
 * Title of the page that shows a record
 *
 * Detail views set it, so the page title and the heading of the first level name the record instead of the page that
 * shows it.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class RecordTitleProvider extends AbstractPageTitleProvider
{
    /**
     * Name the record the page shows
     *
     * @param string $title Title, such as the name of an event
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Start over for the next page
     *
     * @return void
     */
    public function reset(): void
    {
        $this->title = '';
    }
}
