<?php

namespace Tollwerk\TwEvents\ViewHelpers\Page;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;


/**
 * ViewHelper used to render a link tag in the `<head>` section of the page.
 * If you use the ViewHelper in a plugin, the plugin and its action have to
 * be cached!
 */
class TitleViewHelper extends AbstractViewHelper
{
    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('title', 'string', 'The page title', true);
    }

    /**
     * Render method
     *
     * @return void
     */
    public function render()
    {
        $GLOBALS['TSFE']->altPageTitle = $this->arguments['title'];
    }
}
