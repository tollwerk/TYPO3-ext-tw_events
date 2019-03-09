<?php

namespace Tollwerk\TwEvents\Controller;

use Tollwerk\TwEvents\Domain\Repository\PersonRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;

/**
 * Person Controller
 */
class PersonController extends AbstractController
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
     * personRepository
     *
     * @var PersonRepository
     */
    protected $personRepository = null;

    /**
     * Inject the person repository
     *
     * @param PersonRepository $personRepository
     */
    public function injectPersonRepository(PersonRepository $personRepository): void
    {
        $this->personRepository = $personRepository;
    }

    /**
     * List persons
     *
     * @return void
     * @throws InvalidQueryException
     */
    public function listAction(): void
    {
        if ($this->settings['selection_mode'] == self::MODE_MANUAL) {
            $personIdentifiers = GeneralUtility::trimExplode(',', $this->settings['persons'], true);
            $persons           = count($personIdentifiers) ?
                $this->personRepository->findByIdentifiers($personIdentifiers) : [];
        } else {
            $categoryIdentifiers = GeneralUtility::trimExplode(',', $this->settings['categories'], true);
            $persons             = count($categoryIdentifiers) ?
                $this->personRepository->findByCategories($categoryIdentifiers) : [];
        }
        $this->view->assign('persons', $persons);
    }
}
