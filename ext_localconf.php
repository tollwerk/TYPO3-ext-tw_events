<?php

/**
 * Plugins of tw_events
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

use Tollwerk\TwEvents\Controller\EventController;
use Tollwerk\TwEvents\Controller\OrganizationController;
use Tollwerk\TwEvents\Controller\PersonController;
use Tollwerk\TwEvents\Tca\LatitudeEvaluation;
use Tollwerk\TwEvents\Tca\LongitudeEvaluation;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

ExtensionUtility::configurePlugin('TwEvents', 'EventList', [EventController::class => 'list']);
ExtensionUtility::configurePlugin('TwEvents', 'Event', [EventController::class => 'show, session']);
ExtensionUtility::configurePlugin('TwEvents', 'Person', [PersonController::class => 'list, show']);
ExtensionUtility::configurePlugin('TwEvents', 'Organization', [OrganizationController::class => 'list, show']);
ExtensionUtility::configurePlugin('TwEvents', 'PersonSelection', [PersonController::class => 'selection']);
ExtensionUtility::configurePlugin(
    'TwEvents',
    'OrganizationSelection',
    [OrganizationController::class => 'selection'],
);

// Coordinates keep the six decimals of the database, which the number field of TYPO3 rounds to two
$configuration = $GLOBALS['TYPO3_CONF_VARS'] ?? null;
if (is_array($configuration)) {
    $GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive($configuration, [
        'SC_OPTIONS' => ['tce' => ['formevals' => [LatitudeEvaluation::class => '', LongitudeEvaluation::class => '']]],
    ]);
}
