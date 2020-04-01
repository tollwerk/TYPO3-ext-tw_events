<?php

namespace Tollwerk\TwEvents\Controller;

use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

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
 * EventController
 */
class EventController extends ActionController
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
     * Selection by date
     *
     * @var string
     */
    const MODE_DATE = 'date';
    /**
     * Event repository
     *
     * @var EventRepository
     */
    protected $eventRepository = null;

    /**
     * Inject the event repository
     *
     * @param EventRepository $eventRepository
     */
    public function injectEventRepository(EventRepository $eventRepository): void
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * List events
     *
     * @return void
     * @throws InvalidQueryException
     */
    public function listAction()
    {
        if ($this->settings['selection_mode'] == self::MODE_MANUAL) {
            $eventIdentifiers = GeneralUtility::trimExplode(',', $this->settings['events'], true);
            $events           = count($eventIdentifiers) ?
                $this->eventRepository->findByIdentifiers($eventIdentifiers) : [];
        } elseif ($this->settings['selection_mode'] == self::MODE_DATE) {
            $today = new \DateTimeImmutable('today');
            switch ($this->settings['date']) {
                case 'future':
                    $events = $this->eventRepository->findFutureEvents($today);
                    break;
                case 'past':
                    $events = $this->eventRepository->findPastEvents($today);
                    break;
                case 'cancelled':
                    $events = $this->eventRepository->findCancelledEvents($today);
                    break;
            }
        } else {
            $categoryIdentifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], true);
            $events              = count($categoryIdentifiers) ?
                $this->eventRepository->findByCategories($categoryIdentifiers) : [];
        }
        $this->view->assign('events', $events);
    }

    /**
     * action show
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Event $event
     *
     * @return void
     */
    public function showAction(Event $event)
    {
        $this->view->assign('event', $event);
    }

    /**
     * Render an event block
     */
    public function blockAction()
    {
        $this->view->assign('blockTemplate', ucfirst($this->settings['block']));
    }
}
