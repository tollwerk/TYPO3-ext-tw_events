<?php

/**
 * Pages that contain the plugins of tw_events
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

use Tollwerk\TwEvents\Page\PluginPages;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

// "Contains plugin" marks the pages that records link to and whose routes show them
foreach ([PluginPages::EVENTS, PluginPages::PEOPLE, PluginPages::ORGANIZATIONS] as $module) {
    ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'module',
        [
            'label' => 'tw_events.plugins:module.' . $module,
            'value' => $module,
            'icon' => 'content-calendar',
        ],
    );
}
