<?php

/**
 * Details of the people and organisations of an event, as the site settings choose them
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

use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwEvents\Controller\OrganizationController;
use Tollwerk\TwEvents\Controller\PersonController;
use TYPO3\CMS\Core\Site\Entity\Site;

/**
 * Details of the people and organisations of an event, as the site settings choose them
 *
 * The settings "twEvents.hosts.*", "twEvents.speakers.*" and "twEvents.sponsors.*" choose the details of the hosts,
 * the speakers and the sponsors. Texts and addresses show in the order of the settings; unknown values drop out.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final readonly class Details
{
    /**
     * Details of people by group, if the site has no settings, as on the legacy website
     *
     * @var array<string, array{photo: boolean, texts: list<string>, contacts: list<string>}>
     */
    public const array PEOPLE = [
        'hosts' => [
            'photo' => true,
            'texts' => ['summary', 'biography'],
            'contacts' => ['website', 'twitter', 'mastodon', 'linkedin', 'github'],
        ],
        'speakers' => [
            'photo' => true,
            'texts' => ['summary', 'teaser'],
            'contacts' => ['website', 'twitter', 'mastodon', 'linkedin', 'facebook'],
        ],
    ];

    /**
     * Details of organisations by group, if the site has no settings, as on the legacy website
     *
     * @var array<string, array{logo: boolean, address: boolean, contacts: list<string>, description: boolean}>
     */
    public const array ORGANIZATIONS = [
        'sponsors' => [
            'logo' => true,
            'address' => true,
            'contacts' => ['website', 'twitter', 'facebook', 'mastodon', 'linkedin', 'email'],
            'description' => true,
        ],
    ];

    /**
     * Details of a group of people or organisations
     *
     * @param ServerRequestInterface|null $request Request, for the settings of its site
     * @param string                      $group   "hosts", "speakers" or "sponsors"
     *
     * @return array<string, boolean|list<string>> Details, or an empty list for an unknown group
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    public function of(?ServerRequestInterface $request, string $group): array
    {
        if (isset(self::PEOPLE[$group])) {
            return $this->ofPeople($request, $group);
        }

        $site = $request?->getAttribute('site');
        $site = ($site instanceof Site) ? $site : null;
        if (isset(self::ORGANIZATIONS[$group])) {
            $default = self::ORGANIZATIONS[$group];

            return [
                'logo' => $this->flag($site, $group, 'logo', $default['logo']),
                'address' => $this->flag($site, $group, 'address', $default['address']),
                'contacts' => $this->choice(
                    $site,
                    $group,
                    'contacts',
                    OrganizationController::CONTACTS,
                    $default['contacts'],
                ),
                'description' => $this->flag($site, $group, 'description', $default['description']),
            ];
        }

        return [];
    }

    /**
     * Details of a group of people
     *
     * @param ServerRequestInterface|null $request Request, for the settings of its site
     * @param key-of<self::PEOPLE>        $group   "hosts" or "speakers"
     *
     * @return array{photo: boolean, texts: list<string>, contacts: list<string>}
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    public function ofPeople(?ServerRequestInterface $request, string $group): array
    {
        $site = $request?->getAttribute('site');
        $site = ($site instanceof Site) ? $site : null;
        $default = self::PEOPLE[$group];

        return [
            'photo' => $this->flag($site, $group, 'photo', $default['photo']),
            'texts' => $this->choice($site, $group, 'texts', PersonController::TEXTS, $default['texts']),
            'contacts' => $this->choice($site, $group, 'contacts', PersonController::CONTACTS, $default['contacts']),
        ];
    }

    /**
     * A setting of a group as a switch
     *
     * @param Site|null $site    Site
     * @param string    $group   Group
     * @param string    $name    Name of the setting in the group
     * @param boolean   $default Value without the setting
     *
     * @return boolean
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    private function flag(?Site $site, string $group, string $name, bool $default): bool
    {
        $value = $site?->getSettings()->get('twEvents.' . $group . '.' . $name);

        return \is_bool($value) ? $value : $default;
    }

    /**
     * A setting of a group as a choice from a list
     *
     * @param Site|null    $site    Site
     * @param string       $group   Group
     * @param string       $name    Name of the setting in the group
     * @param list<string> $allowed Values to choose from
     * @param list<string> $default Choice without the setting
     *
     * @return list<string> Chosen values in their order, without duplicates
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    private function choice(?Site $site, string $group, string $name, array $allowed, array $default): array
    {
        $value = $site?->getSettings()->get('twEvents.' . $group . '.' . $name);
        if (!\is_array($value)) {
            return $default;
        }

        $choice = [];
        foreach ($value as $item) {
            $item = \is_string($item) ? trim($item) : '';
            if (!\in_array($item, $allowed, true) || \in_array($item, $choice, true)) {
                continue;
            }

            $choice[] = $item;
        }

        return $choice;
    }
}
