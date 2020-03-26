<?php

/**
 * allyclub
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\ViewHelpers\Presentation
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2019 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2019 Joschi Kuphal <joschi@tollwerk.de>
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

namespace Tollwerk\TwEvents\ViewHelpers\Presentation;

use Tollwerk\TwEvents\Domain\Model\Presentation;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Sort a list of presentations to create a multi-day schedule
 *
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\ViewHelpers\Presentation
 */
class ScheduleViewHelper extends AbstractViewHelper
{
    /**
     * Enable static rendering
     */
    use CompileWithRenderStatic;

    /**
     * Render
     *
     * @param array $arguments                            Arguments
     * @param \Closure $renderChildrenClosure             Children rendering closure
     * @param RenderingContextInterface $renderingContext Rendering context
     *
     * @return mixed|string Output
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $presentationsByDate = [];

        /** @var Presentation $presentation */
        foreach ($arguments['presentations'] as $presentation) {
            $day                         = mktime(
                0,
                0,
                0,
                $presentation->getStart()->format('n'),
                $presentation->getStart()->format('j'),
                $presentation->getStart()->format('Y')
            );
            $presentationsByDate[$day][] = $presentation;
        }

        ksort($presentationsByDate);

        // Sort the presentations by start time (and title)
        foreach ($presentationsByDate as $day => $dayPresentations) {
            usort($dayPresentations, [static::class, 'sortPresentationsByStartDate']);
            $presentationsByDate[$day] = $dayPresentations;
        }

        return $presentationsByDate;
    }

    /**
     * Sort presentations by date
     *
     * @param Presentation $presentation1 Presentation 1
     * @param Presentation $presentation2 Presentation 2
     *
     * @return int Sorting order
     */
    protected static function sortPresentationsByStartDate(Presentation $presentation1, Presentation $presentation2)
    {
        $date1 = $presentation1->getStart();
        $date2 = $presentation2->getStart();
        if ($date1 > $date2) {
            return 1;
        }
        if ($date1 < $date2) {
            return -1;
        }

        return strnatcasecmp($presentation1->getTitle(), $presentation2->getTitle());
    }

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('presentations', ObjectStorage::class, 'Presentations', true);
    }
}
