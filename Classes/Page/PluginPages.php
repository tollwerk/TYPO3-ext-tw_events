<?php

/**
 * Finds the pages that show events, people and organisations
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

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;

/**
 * Finds the pages that show events, people and organisations
 *
 * Editors mark these pages with "Contains plugin" in the page properties; links to records lead there, and the routes
 * of the records apply there.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class PluginPages
{
    /**
     * Value of "Contains plugin" of the page of the events
     *
     * @var string
     */
    public const string EVENTS = 'events';

    /**
     * Value of "Contains plugin" of the page of the people
     *
     * @var string
     */
    public const string PEOPLE = 'people';

    /**
     * Value of "Contains plugin" of the page of the organisations
     *
     * @var string
     */
    public const string ORGANIZATIONS = 'organizations';

    /**
     * Pages found so far, by site and module
     *
     * @var array<string, integer>
     */
    private array $pages = [];

    /**
     * Constructor
     *
     * @param ConnectionPool $connectionPool Connection pool
     * @param SiteFinder     $siteFinder     Sites
     */
    public function __construct(
        private readonly ConnectionPool $connectionPool,
        private readonly SiteFinder $siteFinder,
    ) {}

    /**
     * Page of a site that contains a plugin
     *
     * @param Site   $site   Site
     * @param string $module Value of "Contains plugin", such as "events"
     *
     * @return integer Uid of the page, or 0 if the site has none
     *
     * @throws Exception If the query fails
     */
    public function find(Site $site, string $module): int
    {
        $key = $site->getIdentifier() . ':' . $module;
        $this->pages[$key] ??= $this->lookUp($site, $module);

        return $this->pages[$key];
    }

    /**
     * Look up the page of a site that contains a plugin
     *
     * @param Site   $site   Site
     * @param string $module Value of "Contains plugin"
     *
     * @return integer Uid of the page, or 0 if the site has none
     *
     * @throws Exception If the query fails
     */
    private function lookUp(Site $site, string $module): int
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('pages');
        $uids = $queryBuilder
            ->select('uid')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('module', $queryBuilder->createNamedParameter($module)),
                $queryBuilder->expr()->eq(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter(0, Connection::PARAM_INT),
                ),
            )
            ->orderBy('uid')
            ->executeQuery()
            ->fetchFirstColumn();

        foreach ($uids as $uid) {
            $uid = is_numeric($uid) ? (int) $uid : 0;
            try {
                if ($this->siteFinder->getSiteByPageId($uid)->getIdentifier() === $site->getIdentifier()) {
                    return $uid;
                }
            } catch (SiteNotFoundException) {
                continue;
            }
        }

        return 0;
    }
}
