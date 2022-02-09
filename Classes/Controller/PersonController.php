<?php

/**
 * Person Controller
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de>
 * @copyright  2021 Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2021 Jolanta Dworczyk <jolanta@tollwerk.de>
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

namespace Tollwerk\TwEvents\Controller;

use Tollwerk\TwEvents\Domain\Repository\PersonRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

/**
 * Person Controller
 *
 * @category   Tollwerk\TwEvents\Controller
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://tollwerk.de
 */
class PersonController extends AbstractController
{
    /**
     * Manual selection
     *
     * @var string
     */
    const MODE_MANUAL = 'manual';
    /**
     * Selection by categories
     *
     * @var string
     */
    const MODE_CATEGORIES = 'categories';

    /**
     * PersonRepository
     *
     * @var PersonRepository
     */
    protected $personRepository = null;

    /**
     * Constructor
     *
     * @param PersonRepository $personRepository PersonRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * List persons
     *
     * @return void
     * @throws InvalidQueryException
     */
    public function listAction(): void
    {
        if ($this->settings['selection_mode'] == self::MODE_MANUAL) {
            $personIdentifiers = GeneralUtility::trimExplode(',', $this->settings['persons'], true);
            $persons           = count($personIdentifiers) ?
                $this->personRepository->findByIdentifiers($personIdentifiers) : [];
        } else {
            $categoryIdentifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], true);
            $persons             = count($categoryIdentifiers) ?
                $this->personRepository->findByCategories($categoryIdentifiers) : [];
        }
        $this->view->assign('persons', $persons);
    }
}
