<?php

/**
 * Fluid components of tw-events under the shared namespace
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

// Priority 40, see the README
return [
    'Tollwerk\\Components' => [
        'templatePaths' => [40 => 'EXT:tw_events/Resources/Private/Components'],
    ],
];
