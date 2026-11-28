<?php

/**
 * Returns the addresses of a person or an organisation that the choice names and that can be links
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\ViewHelpers;

use Override;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Person;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the addresses of a person or an organisation that the choice names and that can be links
 *
 * Each address comes with its value as text and the target of its link, so that the template escapes both and
 * collects no markup. A website or a profile links only to the web, a mail address only if it is one, and a phone
 * number with its digits and a leading "+". A person has no mail address to show: the one of an account is how the
 * person signs in.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class ContactsViewHelper extends AbstractViewHelper
{
    /**
     * Register the arguments
     *
     * @return void
     */
    #[Override]
    public function initializeArguments(): void
    {
        $this->registerArgument('record', 'object', 'Person or organisation', true);
        $this->registerArgument('contacts', 'array', 'Kinds of addresses in their order', false, []);
    }

    /**
     * Return the addresses
     *
     * @return list<array{kind: string, value: string, href: string}> Addresses in the order of the choice
     */
    #[Override]
    public function render(): array
    {
        $record = $this->arguments['record'] ?? null;
        $contacts = $this->arguments['contacts'] ?? [];
        if ((!$record instanceof Person && !$record instanceof Organization) || !\is_iterable($contacts)) {
            return [];
        }

        $items = [];
        foreach ($contacts as $kind) {
            $kind = \is_string($kind) ? $kind : '';
            $value = trim($this->value($record, $kind));
            $href = '' === $value ? '' : $this->href($kind, $value);
            if ('' === $href) {
                continue;
            }

            $items[] = ['kind' => $kind, 'value' => $value, 'href' => $href];
        }

        return $items;
    }

    /**
     * An address of a record
     *
     * @param Person|Organization $record Person or organisation
     * @param string              $kind   Kind of address
     *
     * @return string Address, or an empty string if the record has none of this kind
     */
    private function value(Person|Organization $record, string $kind): string
    {
        return match (true) {
            'website' === $kind => $record->getWebsite(),
            'mastodon' === $kind => $record->getMastodon(),
            'linkedin' === $kind => $record->getLinkedin(),
            'twitter' === $kind => $record->getTwitter(),
            'facebook' === $kind => $record->getFacebook(),
            'github' === $kind && $record instanceof Person => $record->getGithub(),
            'instagram' === $kind && $record instanceof Person => $record->getInstagram(),
            'email' === $kind && $record instanceof Organization => $record->getEmail(),
            'phone' === $kind && $record instanceof Organization => $record->getPhone(),
            default => '',
        };
    }

    /**
     * Target of the link of an address
     *
     * @param string $kind  Kind of address
     * @param string $value Address as entered
     *
     * @return string Target, or an empty string if the address can be no link
     */
    private function href(string $kind, string $value): string
    {
        return match ($kind) {
            'email' => false === filter_var($value, FILTER_VALIDATE_EMAIL) ? '' : 'mailto:' . $value,
            'phone' => $this->tel($value),
            default => 1 === preg_match('#^https?://[^\s]+$#i', $value) ? $value : '',
        };
    }

    /**
     * Link of a phone number: its digits, with a leading "+" if it has one
     *
     * @param string $value Number as entered, such as "0911 / 123 456-0"
     *
     * @return string Link, or an empty string without digits
     */
    private function tel(string $value): string
    {
        $digits = (string) preg_replace('/\D/', '', $value);

        return '' === $digits ? '' : 'tel:' . (str_starts_with($value, '+') ? '+' : '') . $digits;
    }
}
