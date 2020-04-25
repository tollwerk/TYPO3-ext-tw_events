<?php

/**
 * data
 *
 * @category   Jkphl
 * @package    Jkphl\Antibot
 * @subpackage Tollwerk\TwPresentations\ViewHelpers
 * @author     Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2020 Joschi Kuphal <joschi@kuphal.net>
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
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO PRESENTATION SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Tollwerk\TwEvents\ViewHelpers;

use Tollwerk\TwEvents\Domain\Model\Presentation;
use Tollwerk\TwEvents\Domain\Repository\PresentationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Presentation view helper
 *
 * @package    Tollwerk\TwPresentations
 * @subpackage Tollwerk\TwPresentations\ViewHelpers
 */
class PresentationViewHelper extends AbstractViewHelper
{
    /**
     * Find and return the current presentation
     *
     * @return Presentation|null
     */
    public function render(): ?Presentation
    {
        $presentation = null;

        // Try to extract the current presentation from the GET / POST parameters
        $presentationParameters = GeneralUtility::_GPmerged('tx_twevents_presentations');
        if (!empty($presentationParameters['presentation'])) {
            $objectManager          = GeneralUtility::makeInstance(ObjectManager::class);
            $presentationRepository = $objectManager->get(PresentationRepository::class);
            $presentation           = $presentationRepository->findByIdentifier($presentationParameters['presentation']);
        }

        // Else: Try to find the current presentation via its presentation page
        if ($presentation === null) {
            $objectManager          = GeneralUtility::makeInstance(ObjectManager::class);
            $presentationRepository = $objectManager->get(PresentationRepository::class);
            $presentation           = $presentationRepository->findOneByPage($GLOBALS['TSFE']->id);
        }

        return $GLOBALS['TSFE']->presentation = $presentation;
    }
}
