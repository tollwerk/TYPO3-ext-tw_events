<?php

/**
 * Event Controller
 *
 * @category   TwEvents
 * @package    TwEvents\TwEvents
 * @subpackage TwEvents\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de>
 * @copyright  2021 Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link       https://tollwerk.de
 */

namespace Tollwerk\TwEvents\Controller;

use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

/**
 * Event Controller
 *
 * @category   Tollwerk\TwEvents\Controller
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://tollwerk.de
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
     * Constructor
     *
     * @param EventRepository $eventRepository Event Repository
    */
    public function __construct(EventRepository $eventRepository)
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
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);
    }

    /**
     * Action show
     *
     * @param \Tollwerk\TwEvents\Domain\Model\Event $event Event Model
     *
     * @return void
     */
    public function showAction(Event $event)
    {
        $this->view->assign('event', $event);
    }

    /**
     * Render an event block
     *
     * @return void
     */
    public function blockAction()
    {
        $this->view->assign('blockTemplate', ucfirst($this->settings['block']));
    }
}
