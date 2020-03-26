<?php

namespace Tollwerk\TwEvents\Controller;


use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Repository\OrganizationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
 * OrganizationController
 */
class OrganizationController extends AbstractController
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
     * Organization repository
     *
     * @var OrganizationRepository
     */
    protected $organizationRepository = null;

    /**
     * Inject the organization repository
     *
     * @param OrganizationRepository $organizationRepository
     */
    public function injectOrganizationRepository(OrganizationRepository $organizationRepository): void
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * List organizations
     *
     * @return void
     * @throws InvalidQueryException
     */
    public function listAction(): void
    {
        if ($this->settings['selection_mode'] == self::MODE_MANUAL) {
            $organizationIdentifiers = GeneralUtility::trimExplode(',', $this->settings['organizations'], true);
            $organizations           = count($organizationIdentifiers) ?
                $this->organizationRepository->findByIdentifiers($organizationIdentifiers) : [];
        } else {
            $categoryIdentifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], true);
            $organizations       = count($categoryIdentifiers) ?
                $this->organizationRepository->findByCategories($categoryIdentifiers) : [];
        }
        $this->view->assign('organizations', $organizations);
    }

    /**
     * Render an organisation as location
     */
    public function locationAction(): void
    {
        $organizationIdentifiers = GeneralUtility::trimExplode(',', $this->settings['organizations'], true);
        $organization            = null;
        while (!($organization instanceof Organization)) {
            $organization = $this->organizationRepository->findByIdentifier(array_shift($organizationIdentifiers));
        }
        $this->view->assign('location', $organization);
    }
}
