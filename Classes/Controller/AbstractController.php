<?php

namespace Tollwerk\TwEvents\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Abstract Controller
 *
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 */
abstract class AbstractController extends ActionController
{
    /**
     * Details
     *
     * @var array
     */
    const DETAILS = ['description', 'teaser', 'website', 'twitter', 'facebook', 'email', 'phone'];

    /**
     * Initialize an action
     */
    protected function initializeAction()
    {
        if (!empty($this->settings['details'])) {
            $details                   = intval($this->settings['details']);
            $this->settings['details'] = [];
            foreach (self::DETAILS as $index => $name) {
                $this->settings['details'][$name] = !!($details & pow(2, $index));
            }
        }
    }
}
