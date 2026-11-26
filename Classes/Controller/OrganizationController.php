<?php

/**
 * Lists organisations and shows their profiles
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

use Psr\Http\Message\ResponseInterface;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Repository\EventRepository;
use Tollwerk\TwEvents\Domain\Repository\OrganizationRepository;
use Tollwerk\TwEvents\Domain\Repository\ParticipationRepository;
use Tollwerk\TwEvents\Page\RecordTitleProvider;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

/**
 * Lists organisations and shows their profiles
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class OrganizationController extends AbstractRecordController
{
    /**
     * Addresses of an organisation to show, in the order of the choice
     *
     * @var list<string>
     */
    public const array CONTACTS = ['website', 'email', 'phone', 'mastodon', 'linkedin', 'twitter', 'facebook'];

    /**
     * Addresses that a plugin without a choice shows, as the legacy website did
     *
     * @var list<string>
     */
    public const array DEFAULT_CONTACTS = ['website', 'twitter', 'facebook', 'email'];

    /**
     * Constructor
     *
     * @param RecordTitleProvider     $recordTitleProvider     Title of the page that shows a record
     * @param OrganizationRepository  $organizationRepository  Organisations
     * @param ParticipationRepository $participationRepository Participations in events
     * @param EventRepository         $eventRepository         Events
     */
    public function __construct(
        RecordTitleProvider $recordTitleProvider,
        private readonly OrganizationRepository $organizationRepository,
        private readonly ParticipationRepository $participationRepository,
        private readonly EventRepository $eventRepository,
    ) {
        parent::__construct($recordTitleProvider);
    }

    /**
     * List all organisations
     *
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $this->view->assign('organizations', $this->organizationRepository->findAll());

        return $this->htmlResponse();
    }

    /**
     * List the organisations chosen in the plugin, in their order, as cards with the details chosen in the plugin or
     * as venues with their address
     *
     * @return ResponseInterface
     */
    public function selectionAction(): ResponseInterface
    {
        $organizations = [];
        foreach ($this->uids('organizations') as $uid) {
            $organization = $this->organizationRepository->findByUid($uid);
            if (!($organization instanceof Organization)) {
                continue;
            }

            $organizations[] = $organization;
        }

        $this->view->assignMultiple([
            'organizations' => $organizations,
            'venues' => $this->setting('display') === 'venue',
            'details' => [
                'logo' => $this->flag('logo', true),
                'address' => $this->flag('address', true),
                'contacts' => $this->choice('contacts', self::CONTACTS, self::DEFAULT_CONTACTS),
                'description' => $this->flag('description', true),
            ],
        ]);

        return $this->htmlResponse();
    }

    /**
     * Show the profile of an organisation with the events it took part in and hosted
     *
     * @param Organization $organization Organisation
     *
     * @return ResponseInterface
     *
     * @throws InvalidQueryException If a query is invalid
     */
    public function showAction(Organization $organization): ResponseInterface
    {
        $this->recordTitleProvider->setTitle($organization->getName());
        $this->view->assignMultiple([
            'organization' => $organization,
            'participations' => $this->participationRepository->findByOrganization($organization),
            'hostedEvents' => $this->eventRepository->findByLocation($organization),
        ]);

        return $this->htmlResponse();
    }
}
