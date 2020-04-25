<?php

/**
 * data
 *
 * @category   Tollwerk
 * @package    Tollwerk\Vamoso
 * @subpackage Tollwerk\TwEvents\Utility
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Tollwerk\TwEvents\Utility;

/**
 * TCA utilities
 *
 * @package    Tollwerk\Vamoso
 * @subpackage Tollwerk\TwEvents\Utility
 */
class Tca
{
    /**
     * Layout blocks
     *
     * @var string[]
     */
    const LAYOUT_BLOCKS = [
        1  => 'basics',
        2  => 'description',
        3  => 'performers',
        4  => 'schedule',
        5  => 'tickets',
        6  => 'location',
        7  => 'downloads',
        8  => 'hosts',
        9  => 'sponsors',
        10 => 'coverage',
        11 => 'live',
    ];

    /**
     * Return all available layout block items for a backend form
     *
     * @return array Layout block items
     */
    public static function layoutBlockItems(): array
    {
        $items = [];
        foreach (self::LAYOUT_BLOCKS as $layoutBlock) {
            $items[] = [
                'LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:plugin.layout.'.$layoutBlock,
                $layoutBlock
            ];
        }

        return $items;
    }
}
