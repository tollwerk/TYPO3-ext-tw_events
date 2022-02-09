<?php

/**
 * Organization Controller
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

use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Repository\OrganizationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

/**
 * OrganizationController
 *
 * @category   Tollwerk\TwEvents\Controller
 * @package    Tollwerk\TwEvents
 * @subpackage Tollwerk\TwEvents\Controller
 * @author     Jolanta Dworczyk <jolanta@tollwerk.de>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://tollwerk.de
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
     * Constructor
     *
     * @param OrganizationRepository $organizationRepository Organization Repository
     */
    public function __construct(OrganizationRepository $organizationRepository)
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
     *
     * @return void
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
