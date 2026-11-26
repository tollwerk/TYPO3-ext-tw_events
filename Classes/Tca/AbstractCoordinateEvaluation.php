<?php

/**
 * Evaluates a geographic coordinate in decimal degrees for the backend
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tca;

use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

/**
 * Evaluates a geographic coordinate in decimal degrees for the backend
 *
 * The number field of TYPO3 rounds decimals to two places, about a kilometre, so coordinates are text fields with
 * this evaluation. It keeps the six decimals the database stores, and it keeps the previous value if the input is no
 * coordinate, telling the editor so.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
abstract class AbstractCoordinateEvaluation
{
    /**
     * Decimals the database stores
     *
     * @var integer
     */
    private const int DECIMALS = 6;

    /**
     * Constructor
     *
     * @param FlashMessageService    $flashMessageService    Messages to the editor
     * @param LanguageServiceFactory $languageServiceFactory Labels in the language of the editor
     */
    public function __construct(
        private readonly FlashMessageService $flashMessageService,
        private readonly LanguageServiceFactory $languageServiceFactory,
    ) {}

    /**
     * Evaluate the input of the field before it is saved
     *
     * @param mixed   $value Input
     * @param string  $isIn  Characters the field allows, which a coordinate does not use
     * @param boolean $set   Whether the value is saved
     *
     * @return string|null Coordinate with six decimals, or NULL for an empty input
     *
     * @throws Exception If the message about an invalid input cannot be queued
     */
    public function evaluateFieldValue(mixed $value, string $isIn, bool &$set): ?string
    {
        $input = str_replace(',', '.', trim(\is_scalar($value) ? (string) $value : ''));
        if ($input === '') {
            return null;
        }

        if ((preg_match('/^[+-]?\d+(\.\d+)?$/', $input) === 1) && (abs((float) $input) <= $this->maximum())) {
            return number_format((float) $input, self::DECIMALS, '.', '');
        }

        $set = false;
        $this->reject($input);

        return $input;
    }

    /**
     * Largest value of the coordinate in either direction
     *
     * @return integer Degrees
     */
    abstract protected function maximum(): int;

    /**
     * Key of the label that names the coordinate
     *
     * @return string Key in the labels of the database of tw-events
     */
    abstract protected function label(): string;

    /**
     * Tell the editor that the input is no coordinate and that the previous value stays
     *
     * @param string $input Input
     *
     * @return void
     *
     * @throws Exception If the message cannot be queued
     */
    private function reject(string $input): void
    {
        $user = $GLOBALS['BE_USER'] ?? null;
        $languageService = $this->languageServiceFactory->createFromUserPreferences(
            ($user instanceof AbstractUserAuthentication) ? $user : null,
        );
        $message = $languageService->sL('tw_events.db:coordinate.invalid');
        $this->flashMessageService->getMessageQueueByIdentifier()->enqueue(new FlashMessage(
            \sprintf($message, $input, $this->maximum()),
            $languageService->sL('tw_events.db:' . $this->label()),
            ContextualFeedbackSeverity::ERROR,
            true,
        ));
    }
}
