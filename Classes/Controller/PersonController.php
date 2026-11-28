<?php

/**
 * Lists people and shows their profiles
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Controller;

use Doctrine\DBAL\Exception as DBALException;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use Tollwerk\TwEvents\Domain\Repository\PersonRepository;
use Tollwerk\TwEvents\Domain\Repository\SessionRepository;
use Tollwerk\TwEvents\Page\Details;
use Tollwerk\TwEvents\Page\RecordTitleProvider;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

/**
 * Lists people and shows their profiles
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class PersonController extends AbstractRecordController
{
    /**
     * Texts of a person to show, in the order of the choice
     *
     * @var list<string>
     */
    public const array TEXTS = ['summary', 'teaser', 'biography'];

    /**
     * Addresses of a person to show, in the order of the choice
     *
     * @var list<string>
     */
    public const array CONTACTS = [
        'website',
        'mastodon',
        'linkedin',
        'github',
        'twitter',
        'facebook',
        'instagram',
    ];

    /**
     * Constructor
     *
     * @param RecordTitleProvider $recordTitleProvider Title of the page that shows a record
     * @param PersonRepository    $personRepository    People
     * @param SessionRepository   $sessionRepository   Sessions
     * @param EventRepository     $eventRepository     Events
     * @param Details             $details             Details of people by the site settings
     */
    public function __construct(
        RecordTitleProvider $recordTitleProvider,
        private readonly PersonRepository $personRepository,
        private readonly SessionRepository $sessionRepository,
        private readonly EventRepository $eventRepository,
        private readonly Details $details,
    ) {
        parent::__construct($recordTitleProvider);
    }

    /**
     * List all people
     *
     * @return ResponseInterface
     *
     * @throws DBALException If the database cannot be read
     * @throws InvalidArgumentException If a query cannot be built
     * @throws InvalidQueryException If the query is invalid
     */
    public function listAction(): ResponseInterface
    {
        $this->view->assign('people', $this->personRepository->findPublic());

        return $this->htmlResponse();
    }

    /**
     * List the people chosen in the plugin, in their order, with the details chosen in the plugin
     *
     * A plugin without a choice of details, such as one saved before the choice existed, shows the details of the
     * hosts of an event.
     *
     * @return ResponseInterface
     *
     * @throws ContainerExceptionInterface If the settings of the site cannot be read
     */
    public function selectionAction(): ResponseInterface
    {
        $people = [];
        foreach ($this->uids('people') as $uid) {
            $person = $this->personRepository->findByUid($uid);
            if (!($person instanceof Person)) {
                continue;
            }

            $people[] = $person;
        }

        $hosts = $this->details->ofPeople($this->request, 'hosts');
        $this->view->assignMultiple([
            'people' => $people,
            'details' => [
                'photo' => $this->flag('photo', $hosts['photo']),
                'texts' => $this->choice('texts', self::TEXTS, $hosts['texts']),
                'contacts' => $this->choice('contacts', self::CONTACTS, $hosts['contacts']),
            ],
        ]);

        return $this->htmlResponse();
    }

    /**
     * Show the profile of a person with their sessions and the events they organised
     *
     * @param Person $person Person
     *
     * @return ResponseInterface
     *
     * @throws InvalidQueryException If a query is invalid
     * @throws PropagateResponseException If the profile is not public, with the response of the error page
     * @throws PageNotFoundException If there is no error page
     * @throws InvalidArgumentException If the request is invalid
     * @throws DBALException If the database cannot tell whether the profile is public
     */
    public function showAction(Person $person): ResponseInterface
    {
        $sessions = $this->sessionRepository->findBySpeaker($person);
        $organizedEvents = $this->eventRepository->findByOrganizer($person);
        if (!$this->personRepository->hasPublicProfile($person)) {
            $this->notFound('The profile of the person is not public');
        }

        $this->recordTitleProvider->setTitle($person->getName());
        $this->view->assignMultiple([
            'person' => $person,
            'sessions' => $sessions,
            'organizedEvents' => $organizedEvents,
        ]);

        return $this->htmlResponse();
    }
}
