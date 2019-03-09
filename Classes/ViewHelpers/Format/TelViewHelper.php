<?php

namespace Tollwerk\TwEvents\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Tel-Link viewhelper
 */
class TelViewHelper extends AbstractViewHelper
{
    /**
     * Enable static rendering
     */
    use CompileWithRenderStatic;

    /**
     * Render
     *
     * @param array $arguments                            Arguments
     * @param \Closure $renderChildrenClosure             Children rendering closure
     * @param RenderingContextInterface $renderingContext Rendering context
     *
     * @return mixed|string Output
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return preg_replace('/[^0-9\+]/', '', $arguments['number'] ?: $renderChildrenClosure());
    }

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('number', 'string', 'Phone number', false, '');
    }
}
