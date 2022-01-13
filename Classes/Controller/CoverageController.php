<?php

/**
 * TYPO3 tw_events
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2021 Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
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

use Tollwerk\TwEvents\Domain\Repository\CoverageRepository;

/***
 *
 * This file is part of the "tollwerk Event Calendar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  © 2020
 *
 ***/

/**
 * CoverageController
 */
class CoverageController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * CoverageRepository
     *
     * @var CoverageRepository CoverageRepository
     */
    protected $coverageRepository = null;

    /**
     * Inject the coverage Repository
     *
     * @param CoverageRepository $coverageRepository Coverage Repository
     *
     * @return void
     */
    public function injectCoverageRepository(CoverageRepository $coverageRepository)
    {
        $this->coverageRepository = $coverageRepository;
    }

    /**
     * Action list
     *
     * @return void
     */
    public function listAction()
    {
        $coverages = $this->coverageRepository->findAll();
        $this->view->assign('coverages', $coverages);
    }

    /**
     * Action show
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Coverage $coverage Coverage
     *
     * @return void
     */
    public function showAction(\Tollwerk\TwEvents\Domain\Model\Coverage $coverage)
    {
        $this->view->assign('coverage', $coverage);
    }
}
