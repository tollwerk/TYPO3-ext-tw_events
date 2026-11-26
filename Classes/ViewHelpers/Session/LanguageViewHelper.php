<?php

/**
 * Returns the language of a session where it differs from that of the page
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\ViewHelpers\Session;

use Psr\Http\Message\ServerRequestInterface;
use Tollwerk\TwBase\ViewHelpers\TypedArguments;
use Tollwerk\TwEvents\Domain\Model\Session;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the language of a session where it differs from that of the page
 *
 * A session is held in its own language, else in the main language of its event. Where that differs from the language
 * of the page, its title and descriptions need a "lang" attribute (WCAG 3.1.2), while everything around them stays in
 * the language of the page:
 *
 *     <f:variable name="lang" value="{events:session.language(session: session)}" />
 *     <span class="p-name" lang="{lang}">{session.title}</span>
 *
 * Tag-based ViewHelpers leave out an empty attribute; plain tags need a condition.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class LanguageViewHelper extends AbstractViewHelper
{
    use TypedArguments;

    /**
     * Register the arguments
     *
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('session', Session::class, 'Session', true);
    }

    /**
     * Return the language
     *
     * @return string Language tag of BCP 47, such as "de", or an empty string for the language of the page
     */
    public function render(): string
    {
        $session = $this->arguments['session'] ?? null;
        if (!$session instanceof Session) {
            return '';
        }

        $language = $session->getLanguage();
        if ($language === '') {
            $language = $session->getEvent()?->getMainLanguage() ?? '';
        }

        // Only tags as the backend offers them, which are safe in an attribute
        if (preg_match('/^[a-z]{2,3}(-[A-Za-z0-9]{2,8})*$/', $language) !== 1) {
            return '';
        }

        $primary = static fn(string $tag): string => strtolower(explode('-', $tag)[0]);

        return ($primary($language) === $primary($this->pageLanguage())) ? '' : $language;
    }

    /**
     * Language of the page
     *
     * @return string Language tag, or an empty string without a request
     */
    private function pageLanguage(): string
    {
        $request = $this->request();
        $language = ($request instanceof ServerRequestInterface) ? $request->getAttribute('language') : null;

        return ($language instanceof SiteLanguage) ? $language->getLocale()->getLanguageCode() : '';
    }
}
