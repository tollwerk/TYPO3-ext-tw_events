<?php

/**
 * Plugins of tw_events as content elements
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

foreach (
    [
        'EventList' => 'FILE:EXT:tw_events/Configuration/FlexForms/EventList.xml',
        'Event' => 'FILE:EXT:tw_events/Configuration/FlexForms/Event.xml',
        'Person' => '',
        'Organization' => '',
        'PersonSelection' => 'FILE:EXT:tw_events/Configuration/FlexForms/PersonSelection.xml',
        'OrganizationSelection' => 'FILE:EXT:tw_events/Configuration/FlexForms/OrganizationSelection.xml',
    ] as $plugin => $flexForm
) {
    ExtensionUtility::registerPlugin(
        'TwEvents',
        $plugin,
        'tw_events.plugins:' . lcfirst($plugin) . '.title',
        'content-calendar',
        'events',
        'tw_events.plugins:' . lcfirst($plugin) . '.description',
        $flexForm,
    );
}
