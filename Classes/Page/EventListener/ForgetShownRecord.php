<?php

/**
 * Forgets the record a page shows, and its title, once the page is rendered
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Page\EventListener;

use Tollwerk\TwEvents\Page\RecordTitleProvider;
use Tollwerk\TwEvents\Page\ShownEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

/**
 * Forgets the record a page shows, and its title, once the page is rendered
 *
 * One process can render several pages, such as subrequests or tests.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
#[AsEventListener('tw-events/forget-shown-record', event: AfterCacheableContentIsGeneratedEvent::class)]
final readonly class ForgetShownRecord
{
    /**
     * Constructor
     *
     * @param RecordTitleProvider $recordTitleProvider Title of the record
     * @param ShownEvent          $shownEvent          Event and session the page shows
     */
    public function __construct(
        private RecordTitleProvider $recordTitleProvider,
        private ShownEvent $shownEvent,
    ) {}

    /**
     * Forget the record and its title
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->recordTitleProvider->reset();
        $this->shownEvent->reset();
    }
}
