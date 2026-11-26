<?php

/**
 * Languages to choose from for an event
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

use ResourceBundle;

/**
 * Languages to choose from for an event
 *
 * The main language of an event is a language tag as schema.org expects it for "inLanguage" (BCP 47). An event names
 * a language, not a region, so the items are the languages with a two-letter code of ISO 639-1, named in English as
 * ICU knows them. The language list of TYPO3 does not fit: it lists translations of the backend, some under codes of
 * other languages.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class LanguageItems
{
    /**
     * Add the languages to the items of the field, ordered by name
     *
     * @param array{items: list<array{label: string, value: string}>} $params Parameters of the items processor
     *
     * @return void
     */
    public function addItems(array &$params): void
    {
        $names = ResourceBundle::create('en', 'ICUDATA-lang')?->get('Languages');
        if (!$names instanceof ResourceBundle) {
            return;
        }

        $languages = [];
        foreach ($names as $code => $name) {
            if (!\is_string($code) || !\is_string($name) || preg_match('/^[a-z]{2}$/', $code) !== 1) {
                continue;
            }

            $languages[$code] = $name;
        }

        asort($languages, SORT_NATURAL | SORT_FLAG_CASE);
        foreach ($languages as $code => $name) {
            $params['items'][] = ['label' => $name, 'value' => $code];
        }
    }
}
