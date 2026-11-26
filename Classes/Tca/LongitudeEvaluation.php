<?php

/**
 * Evaluates a longitude for the backend
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tca;

use Override;

/**
 * Evaluates a longitude for the backend: decimal degrees from -180 to 180
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class LongitudeEvaluation extends AbstractCoordinateEvaluation
{
    /**
     * Largest value of the coordinate in either direction
     *
     * @return integer Degrees
     */
    #[Override]
    protected function maximum(): int
    {
        return 180;
    }

    /**
     * Key of the label that names the coordinate
     *
     * @return string Key in the labels of the database of tw-events
     */
    #[Override]
    protected function label(): string
    {
        return 'tx_twevents_organization.longitude';
    }
}
