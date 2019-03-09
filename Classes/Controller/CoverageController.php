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
 * CoverageController
 */
class CoverageController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * coverageRepository
     *
     * @var \Tollwerk\TwEvents\Domain\Repository\CoverageRepository
     * @inject
     */
    protected $coverageRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $coverages = $this->coverageRepository->findAll();
        $this->view->assign('coverages', $coverages);
    }

    /**
     * action show
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Coverage $coverage
     *
     * @return void
     */
    public function showAction(\Tollwerk\TwEvents\Domain\Model\Coverage $coverage)
    {
        $this->view->assign('coverage', $coverage);
    }
}
