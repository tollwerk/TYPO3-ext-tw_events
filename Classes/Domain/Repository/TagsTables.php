<?php

/**
 * Tags the page with the tables a query reads
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Domain\Repository;

use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Core\Cache\Event\AddCacheTagEvent;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Tags the page with the tables a query reads
 *
 * With automatic cache tags, TYPO3 tags a page only with the records a custom query returns. A new record, or one that
 * becomes visible, would not remove the page from the cache. Repository::findAll() tags the page with the table, too,
 * and so do the custom queries with this, also with the tables they join, such as the events of sessions.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
trait TagsTables
{
    /**
     * Tag the page with the table of a query and the tables it joins
     *
     * @template T of object
     *
     * @param QueryInterface<T> $query     Query
     * @param string            ...$joined Tables the query joins
     *
     * @return void
     */
    private function tagTables(QueryInterface $query, string ...$joined): void
    {
        $this->addTableToCacheTags($query);
        if (!$this->autoTagging) {
            return;
        }

        foreach ($joined as $table) {
            $this->eventDispatcher->dispatch(new AddCacheTagEvent(new CacheTag($table)));
        }
    }
}
