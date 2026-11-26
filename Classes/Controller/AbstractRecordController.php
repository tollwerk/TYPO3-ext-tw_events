<?php

/**
 * Base of the controllers that show records
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

use InvalidArgumentException;
use Tollwerk\TwEvents\Page\RecordTitleProvider;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Page\PageInformation;

/**
 * Base of the controllers that show records
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
abstract class AbstractRecordController extends ActionController
{
    /**
     * Constructor
     *
     * @param RecordTitleProvider $recordTitleProvider Title of the page that shows a record
     */
    public function __construct(protected readonly RecordTitleProvider $recordTitleProvider) {}

    /**
     * Uid of the current page
     *
     * @return integer Uid, or 0 outside of a page
     */
    protected function pageUid(): int
    {
        $pageInformation = $this->request->getAttribute('frontend.page.information');

        return ($pageInformation instanceof PageInformation) ? $pageInformation->getId() : 0;
    }

    /**
     * A setting as text
     *
     * @param string $name Name
     *
     * @return string Value, or an empty string if it is missing or not scalar
     */
    protected function setting(string $name): string
    {
        $value = $this->settings[$name] ?? '';

        return \is_scalar($value) ? trim((string) $value) : '';
    }

    /**
     * A setting as a choice from a list, such as the details of a record to show
     *
     * @param string       $name    Name
     * @param list<string> $allowed Values to choose from
     * @param list<string> $default Choice if the setting is missing, as in a plugin saved before the setting existed
     *
     * @return list<string> Chosen values in their order, without duplicates
     */
    protected function choice(string $name, array $allowed, array $default): array
    {
        if (!\array_key_exists($name, $this->settings)) {
            return $default;
        }

        $choice = [];
        foreach (explode(',', $this->setting($name)) as $value) {
            $value = trim($value);
            if (!\in_array($value, $allowed, true) || \in_array($value, $choice, true)) {
                continue;
            }

            $choice[] = $value;
        }

        return $choice;
    }

    /**
     * A setting as a switch
     *
     * @param string  $name    Name
     * @param boolean $default Value if the setting is missing, as in a plugin saved before the setting existed
     *
     * @return boolean
     */
    protected function flag(string $name, bool $default): bool
    {
        return \array_key_exists($name, $this->settings) ? ($this->setting($name) === '1') : $default;
    }

    /**
     * A setting as a list of uids, such as the records chosen in a plugin
     *
     * @param string $name Name
     *
     * @return list<positive-int> Uids in their order, without duplicates
     */
    protected function uids(string $name): array
    {
        $uids = [];
        foreach (explode(',', $this->setting($name)) as $value) {
            $uid = (int) trim($value);
            if ($uid <= 0) {
                continue;
            }

            $uids[$uid] = $uid;
        }

        return array_values($uids);
    }

    /**
     * Stop with the error page for a missing record
     *
     * @param string $message Reason, for the log
     *
     * @return never
     *
     * @throws PropagateResponseException Always, with the response of the error page
     * @throws PageNotFoundException If there is no error page
     * @throws InvalidArgumentException If the request is invalid
     */
    protected function notFound(string $message): never
    {
        throw new PropagateResponseException(
            GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction($this->request, $message),
            1795548001,
        );
    }
}
