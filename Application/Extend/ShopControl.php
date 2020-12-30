<?php

namespace Moga\Application\Extend;

use OxidEsales\Eshop\Core\Registry;

class ShopControl extends ShopControl_parent
{
    /**
     * Render BaseController object.
     *
     * @param \OxidEsales\Eshop\Application\Controller\FrontendController $view view object to render
     *
     * @return string
     */
    /*
    protected function _render($view) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $output = parent::_render($view);
        return $output;

        $aDynamicOverrides = $view->getViewDataElement("aMogaDynamicOverrides");
        //var_dump($aDynamicOverrides);
        if(empty($aDynamicOverrides) || !is_array($aDynamicOverrides)) return $output;

        //print "yxxxyxyxyx";

        return $output.formDynamicOverrideOutput($aDynamicOverrides);
    }

    protected function formDynamicOverrideOutput($aDynamicOverrides)
    {
        var_dump($aDynamicOverrides);
        $output = '<table cellspacing="0" cellpadding="5" border="1" style="border-collapse:collapse;">';
        foreach ($aDynamicOverrides as $tpl => $override) $output .= '<tr><td>'.$tpl.'</td><td>'.$override.'</td></tr>';
        $output .= '</table>';

        return $output;
    }
    */
}