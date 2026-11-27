<?php

/**
 * Sessions in another language than the page in the templates of tw_events
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

namespace Tollwerk\TwEvents\Tests\Functional;

use Dom\HTMLDocument;
use InvalidArgumentException;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Session;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Sessions in another language than the page in the templates of tw_events
 *
 * The templates of a site may replace those of tw_events, which mark the language of a session as well, as the
 * functional test of the frontend shows for those of the site.
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */
final class SessionLanguageTest extends FunctionalTestCase
{
    /**
     * Template with the item of a session in the programme
     *
     * @var string
     */
    private const string ITEM = __DIR__ . '/Fixtures/Templates/SessionItem.html';

    /**
     * Template of the page of a session
     *
     * @var string
     */
    private const string SESSION = __DIR__ . '/../../Resources/Private/Templates/Event/Session.html';

    /**
     * System extensions tw_base needs
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $coreExtensionsToLoad = ['filemetadata', 'felogin', 'rte_ckeditor'];

    /**
     * Extensions of the project
     *
     * @var non-empty-string[]
     */
    #[Override]
    protected array $testExtensionsToLoad = [
        'tollwerk/tw-base',
        'tollwerk/tw-fields',
        'tollwerk/tw-antibot',
        'tollwerk/tw-accounts',
        'tollwerk/tw-events',
    ];

    /**
     * A session in German on an English page marks its title and description, nothing else
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request cannot be built
     * @throws ContainerExceptionInterface If the view factory is missing
     */
    public function testSessionInAnotherLanguage(): void
    {
        $session = $this->session('Barrierefreie Farbkontraste', 'de', 'en');

        $item = $this->render(self::ITEM, $session);
        self::assertSame('de', $item->querySelector('.SessionItem__title .p-name')?->getAttribute('lang'));
        self::assertSame(['de'], $this->languages($item));

        $page = $this->render(self::SESSION, $session);
        self::assertSame('de', $page->querySelector('.Session > .p-name')?->getAttribute('lang'));
        self::assertSame('de', $page->querySelector('.Session__summary')?->getAttribute('lang'));
        self::assertFalse($page->querySelector('.Session__context')?->hasAttribute('lang') ?? true);
    }

    /**
     * A session without a language of its own is held in the main language of its event
     *
     * @return void
     *
     * @throws InvalidArgumentException If the request cannot be built
     * @throws ContainerExceptionInterface If the view factory is missing
     */
    public function testSessionInTheLanguageOfItsEvent(): void
    {
        $item = self::ITEM;
        self::assertSame(['de'], $this->languages($this->render($item, $this->session('Eröffnung', '', 'de'))));
        self::assertSame([], $this->languages($this->render($item, $this->session('Opening', '', 'en'))));
        self::assertSame([], $this->languages($this->render($item, $this->session('Opening', 'en-GB', 'en'))));
    }

    /**
     * A session of an event
     *
     * @param string $title        Title
     * @param string $language     Language of the session
     * @param string $mainLanguage Main language of its event
     *
     * @return Session
     */
    private function session(string $title, string $language, string $mainLanguage): Session
    {
        $event = new Event();
        $event->_setProperty('title', 'Meetup');
        $event->_setProperty('mainLanguage', $mainLanguage);
        $session = new Session();
        $session->_setProperty('title', $title);
        $session->_setProperty('type', 'talk');
        $session->_setProperty('language', $language);
        // A summary, as the description needs the TypoScript of a site to render
        $session->_setProperty('summary', $title);
        $session->_setProperty('event', $event);

        return $session;
    }

    /**
     * Render a template on an English page
     *
     * @param string  $path    Absolute path of the template
     * @param Session $session Session
     *
     * @return HTMLDocument
     *
     * @throws InvalidArgumentException If the request cannot be built
     * @throws ContainerExceptionInterface If the view factory is missing
     */
    private function render(string $path, Session $session): HTMLDocument
    {
        $request = new ServerRequest('http://localhost/')
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('language', new SiteLanguage(0, 'en-US', new Uri('http://localhost/'), []))
            ->withAttribute('extbase', new ExtbaseRequestParameters()->setControllerExtensionName('TwEvents'));
        $view = $this->get(ViewFactoryInterface::class)->create(new ViewFactoryData(
            templatePathAndFilename: $path,
            request: new Request($request),
        ));
        $view->assignMultiple(['session' => $session, 'event' => $session->getEvent()]);

        return HTMLDocument::createFromString('<!doctype html><body>' . $view->render() . '</body>', LIBXML_NOERROR);
    }

    /**
     * Languages the rendered markup marks
     *
     * @param HTMLDocument $document Rendered markup
     *
     * @return list<string> Distinct languages
     */
    private function languages(HTMLDocument $document): array
    {
        $languages = [];
        foreach ($document->querySelectorAll('body [lang]') as $element) {
            $languages[] = $element->getAttribute('lang') ?? '';
        }

        return array_values(array_unique($languages));
    }
}
