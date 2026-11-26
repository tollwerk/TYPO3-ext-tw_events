<?php

/**
 * Event and session the page shows
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Page;

use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Session;

/**
 * Event and session the page shows
 *
 * The views of an event set them, so the templates of the page can show the event outside of the plugin, such as in
 * the banner.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class ShownEvent
{
    /**
     * Event
     *
     * @var Event|null
     */
    private ?Event $event = null;

    /**
     * Session
     *
     * @var Session|null
     */
    private ?Session $session = null;

    /**
     * Show an event, or a session of it
     *
     * @param Event        $event   Event
     * @param Session|null $session Session
     *
     * @return void
     */
    public function show(Event $event, ?Session $session = null): void
    {
        $this->event = $event;
        $this->session = $session;
    }

    /**
     * Event the page shows
     *
     * @return Event|null Event, or NULL if the page shows none
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * Session the page shows
     *
     * @return Session|null Session, or NULL if the page shows none
     */
    public function getSession(): ?Session
    {
        return $this->session;
    }

    /**
     * Forget the event and the session, for the next page
     *
     * @return void
     */
    public function reset(): void
    {
        $this->event = null;
        $this->session = null;
    }
}
