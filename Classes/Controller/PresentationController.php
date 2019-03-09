<?php

namespace Tollwerk\TwEvents\Controller;


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
 * PresentationController
 */
class PresentationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * presentationRepository
     *
     * @var \Tollwerk\TwEvents\Domain\Repository\PresentationRepository
     * @inject
     */
    protected $presentationRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $presentations = $this->presentationRepository->findAll();
        $this->view->assign('presentations', $presentations);
    }

    /**
     * action show
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Presentation $presentation
     *
     * @return void
     */
    public function showAction(\Tollwerk\TwEvents\Domain\Model\Presentation $presentation)
    {
        $this->view->assign('presentation', $presentation);
    }
}
