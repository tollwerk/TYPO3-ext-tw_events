<?php

/**
 * Presentation Controller
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

use Tollwerk\TwEvents\Domain\Model\Presentation;
use Tollwerk\TwEvents\Domain\Repository\PresentationRepository;

/**
 * Presentation Controller
 *
 * @category   Tollwerk\TwEvents\Controller
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://tollwerk.de
 */
class PresentationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Presentation repository
     *
     * @var \Tollwerk\TwEvents\Domain\Repository\PresentationRepository
     */
    protected $presentationRepository = null;

    /**
     * Constructor
     *
     * @param PresentationRepository $presentationRepository PresentationRepository
     */
    public function __construct(PresentationRepository $presentationRepository)
    {
        $this->presentationRepository = $presentationRepository;
    }

    /**
     * Action list
     *
     * @return void
     */
    public function listAction()
    {
        $presentations = empty($GLOBALS['TSFE']->event) ?
            $this->presentationRepository->findAll() : $GLOBALS['TSFE']->event->getPresentations();
        $this->view->assign('presentations', $presentations);
    }

    /**
     * Presentation details
     *
     * @param Presentation $presentation Presentation
     *
     * @return void
     */
    public function showAction(Presentation $presentation)
    {
        $this->view->assign('presentation', $presentation);
    }
}
