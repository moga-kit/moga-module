<?php

namespace MogaKit\TplManager\Application\Controller\Admin;

class Mogacustomizer extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    protected $_sThisTemplate = 'customizer.tpl';

    public function getCustomScss()
    {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        $sCustomScssFile = $sScssPath."custom.scss";

        return (file_exists($sCustomScssFile) ? file_get_contents($sCustomScssFile) : "");
    }
    public function setCustomScss()
    {
        $request = oxNew(\OxidEsales\Eshop\Core\Request::class);
        $sCustomScss = $request->getRequestEscapedParameter("customscss");

        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        $sCustomScssFile = $sScssPath."custom.scss";

        if (is_writable($sCustomScssFile)) {
            file_put_contents($sCustomScssFile, $sCustomScss);
        } else {
            $this->getUtilsView()->addErrorToDisplay("custom.scss File is not writable");
            return \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('custom.scss File is not writable');
        }
    }


    public function preview()
    {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $request = oxNew(\OxidEsales\Eshop\Core\Request::class);

        $aScssVariables = $request->getRequestEscapedParameter("scssvariables");
        $css = $this->compileScss();

        $sCssPath = $cfg->getDir(null, "src/css", false, null, null, "moga");
        $sPreviewFilePath = $sCssPath."preview.css";
        //var_dump($sPreviewFilePath);

        if (!file_put_contents($sPreviewFilePath,$css)) {
            return \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('preview.css File is not writable');
        }

        setcookie("scsspreview", true, time()+3600 , "/");
    }
    public function save()
    {
    }

    private function compileScss($aScssVariables = false)
    {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sTwbsVendorPath = str_replace("source", "vendor/twbs/bootstrap/scss", $cfg->getConfigParam('sShopDir'));
        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        //$sScssFile = $cfg->getDir("_variables.scss","src",false, null, null, "moga");

        //var_dump($sScssPath);

        try {
            $scss = new \ScssPhp\ScssPhp\Compiler;
            $scss->setImportPaths([$sTwbsVendorPath,$sScssPath]);
            $css = $scss->compile('@import "moga";');

            $autoprefixer  = new \Padaliyajay\PHPAutoprefixer\Autoprefixer($css);
            return $autoprefixer->compile();
        } catch (\Throwable $error) {
            echo $error->getMessage();
        }
    }
}
