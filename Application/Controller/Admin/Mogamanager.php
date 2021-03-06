<?php

namespace Moga\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Mogamanager extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    protected $_sThisTemplate = 'tplmanager.tpl';

    public function getConfigurableTemplates() {
        // format: template => file name pattern
        return [
            "header" => $this->getTemplateOptions("layout/header/*.tpl"),
            "tabs" => $this->getTemplateOptions("details/tabs/*.tpl"),
            "footer" => $this->getTemplateOptions("layout/footer/*.tpl")
        ];
    }

    public function getTemplateOptions($sTemplate) {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sTplPath = $cfg->getDir(null, "tpl", false, null, null, "tpl-kit");

        $oModule = oxNew(\OxidEsales\Eshop\Core\Module\Module::class);
        $sModulePath = $oModule->getModulePath("tpl-manager");
        $pattern = $cfg->getModulesDir() . $sModulePath . DIRECTORY_SEPARATOR . "templates".DIRECTORY_SEPARATOR.$sTemplate;

        //var_dump($pattern);
        $aTemplateOptions = glob($pattern);

        return str_replace($cfg->getModulesDir() . $sModulePath . DIRECTORY_SEPARATOR . "templates".DIRECTORY_SEPARATOR,"",$aTemplateOptions);

        return $aTemplateOptions;
    }

    public function getDynamicOverrides() {
        return unserialize(html_entity_decode(Registry::getUtilsServer()->getOxCookie("mogaDynamicOverrides")));
    }
    public function setDynamicOverrides() {
        $aDynamicOverrides = [
            "layout/header.tpl" => "layout/header/custom.tpl"
        ];
        $x = Registry::getUtilsServer()->setOxCookie("mogaDynamicOverrides", serialize($aDynamicOverrides));
        die(json_encode([
            "status" => "ok",
            "data" => $aDynamicOverrides
        ]));
    }

    public function getTemplates() {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();

        //  function getDir($file, $dir, $admin, $lang = null, $shop = null, $theme = null, $absolute = true, $ignoreCust = false)
        $sTplPath = $cfg->getDir(null, "tpl", false, null, null, "tpl-kit");

        $directory = new RecursiveDirectoryIterator($sTplPath);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);

        $aTempaltes = [];

        foreach ($iterator as $splFileInfo) {
            if(in_array(basename($splFileInfo),[".",".."])) continue;
            $path = $splFileInfo->isDir() ? array($splFileInfo->getFilename() => array()) : array($splFileInfo->getFilename());

            for ($depth = $iterator->getDepth() - 1; $depth >= 0; $depth--) {
                $path = array($iterator->getSubIterator($depth)->current()->getFilename() => $path);
            }
            $aTempaltes = array_merge_recursive($aTempaltes, $path);
        }

        ksort($aTempaltes);

        return $aTempaltes;
    }

    public function setTemplate() {
        /** @var Request $request */
        $request = Registry::get(Request::class);
        $sOldTemplate = $request->getRequestEscapedParameter("oldtemplate");
        $sNewFile = $request->getRequestEscapedParameter("oldtemplate");
    }
}