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
 * Datetime Utility
 *
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Utility
 */
class DatetimeUtility
{
    /**
     * Fix the timezone of a datetime
     *
     * @param \DateTimeInterface $datetime Datetime
     *
     * @return \DateTimeInterface Datetime with timezone fixed
     */
    public static function fixTimezone(\DateTimeInterface $datetime = null): ?\DateTimeInterface
    {
        if ($datetime === null) {
            return null;
        }

        $timezone = $datetime->getTimezone();
        if (!($timezone instanceof \DateTimeZone) || ($timezone->getName() !== ini_get('date.timezone'))) {
            $timezoneOffset    = $timezone->getOffset($datetime);
            $newTimezone       = new \DateTimeZone(ini_get('date.timezone'));
            $newTimezoneOffset = $newTimezone->getOffset($datetime);
            $skew              = $timezoneOffset - $newTimezoneOffset;
            if ($skew) {
                $datetime->modify((($skew > 0) ? '+' : '').$skew.' seconds');
            }

            $datetime->setTimezone($newTimezone);
        }

        return $datetime;
    }
}
