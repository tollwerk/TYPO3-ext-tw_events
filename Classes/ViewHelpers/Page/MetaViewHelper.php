<?php

namespace Tollwerk\TwEvents\ViewHelpers\Page;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;


/**
 * ViewHelper used to render a link tag in the `<head>` section of the page.
 * If you use the ViewHelper in a plugin, the plugin and its action have to
 * be cached!
 */
class MetaViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var    string
     */
    protected $tagName = 'meta';

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerTagAttribute('property', 'string', 'The property attribute of the meta tag');
        $this->registerTagAttribute('name', 'string', 'The name attribute of the meta tag');
        $this->registerTagAttribute('content', 'string', 'The content attribute of the meta tag');
    }

    /**
     * Render method
     *
     * @return void
     */
    public function render()
    {
        $hash                                                             = md5(implode('', [
            $this->arguments['property'],
            $this->arguments['name']
        ]));
        $GLOBALS['TSFE']->additionalHeaderData['tx_TwEvents_meta_'.$hash] = $this->tag->render();
    }
}
