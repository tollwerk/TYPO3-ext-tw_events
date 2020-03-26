<?php

namespace Tollwerk\TwEvents\Controller;

use Tollwerk\TwEvents\Domain\Model\Presentation;
use Tollwerk\TwEvents\Domain\Repository\PresentationRepository;

/***
 *
 * This file is part of the "tollwerk Event Calendar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/

/**
 * Presentation Controller
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
     * Inject the presentation repository
     *
     * @param PresentationRepository $presentationRepository
     */
    public function injectPresentationRepository(PresentationRepository $presentationRepository): void
    {
        $this->presentationRepository = $presentationRepository;
    }

    /**
     * action list
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
