<?php
namespace Moga\Application\Core;

use OxidEsales\Eshop\Core\Registry;

class MogaSmarty extends \Smarty
{
    /**
     * get a concrete filename for automagically created content
     *
     * @param string $auto_base
     * @param string $auto_source
     * @param string $auto_id
     * @return string
     * @staticvar string|null
     * @staticvar string|null
     */
    /*
    function _get_auto_filename($auto_base, $auto_source = null, $auto_id = null)
    {
        $_return = parent::_get_auto_filename($auto_base, $auto_source, $auto_id);
        if(!$auto_source) return $_return;

        $prefix = str_replace(["/",".tpl"],"_",$auto_source);

        //print $auto_base . '<br/>';
        //print $auto_source .'<br/>';
        //print $prefix .'<br/>';
        return str_replace($auto_base,$prefix,$_return);
    }*/
    private $_aDynamicOverrides = null;
    private function _checkForDynamicOverride($params)
    {
        // nix tun, wenn moga nicht aktiv ist
        $activeThemeIds = oxNew(\OxidEsales\Eshop\Core\Theme::class)->getActiveThemesList();
        if(!in_array("moga",$activeThemeIds)) return $params;

        // nix tun, wenn user nicht angemeldet ist
        $oUser = Registry::getSession()->getUser();
        if(!$oUser || $oUser->oxuser__oxrights->value !== "malladmin") return $params;

        if($this->_aDynamicOverrides === null) $this->_aDynamicOverrides = unserialize(html_entity_decode(Registry::getUtilsServer()->getOxCookie("mogaDynamicOverrides")));

        if(array_key_exists($params["smarty_include_tpl_file"],$this->_aDynamicOverrides)) {
            if(!array_key_exists("aMogaDynamicOverrides",$this->_tpl_vars)) $this->_tpl_vars["aMogaDynamicOverrides"] = [];
            //$this->_tpl_vars["aMogaDynamicOverrides"][$params["smarty_include_tpl_file"]] = $this->_aDynamicOverrides[$params["smarty_include_tpl_file"]];
            //print "overriding ".$params["smarty_include_tpl_file"]." to ".$this->_aDynamicOverrides[$params["smarty_include_tpl_file"]]."<br/>";
            $params["smarty_include_tpl_file"] = $this->_aDynamicOverrides[$params["smarty_include_tpl_file"]];
            //var_dump($this->_tpl_vars["aMogaDynamicOverrides"]);
        }

        //$params['smarty_include_tpl_file']
        return $params;
    }
    /**
     * called for included templates
     *
     * @param string $_smarty_include_tpl_file
     * @param string $_smarty_include_vars
     */

    // $_smarty_include_tpl_file, $_smarty_include_vars

    function _smarty_include($params)
    {
        if(!Registry::getConfig()->isAdmin()) $params = $this->_checkForDynamicOverride($params);
        return parent::_smarty_include($params);
    }
}