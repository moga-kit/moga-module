<?php

namespace MogaKit\TplManager\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;

class Mogacustomizer extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    protected $_sThisTemplate = 'customizer.tpl';

    public function getCustomScss()
    {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        $sCustomScssFile = $sScssPath . "custom.scss";

        return (file_exists($sCustomScssFile) ? file_get_contents($sCustomScssFile) : "");
    }

    public function setCustomScss()
    {
        $request = oxNew(\OxidEsales\Eshop\Core\Request::class);
        $sCustomScss = $request->getRequestEscapedParameter("customscss");

        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        $sCustomScssFile = $sScssPath . "custom.scss";

        if (is_writable($sCustomScssFile)) {
            file_put_contents($sCustomScssFile, $sCustomScss);
        } else {
            $this->getUtilsView()->addErrorToDisplay("custom.scss File is not writable");
            return \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('custom.scss File is not writable');
        }
    }

    public function getScssColors()
    {
        $default = [
            [
            'name' => 'primary',
            'value' => '',
            'default' => '#7952b3'
        ], [
            'name' => 'secondary',
            'value' => '',
            'default' => '#6c757d'
        ], [
            'name' => 'success',
            'value' => '',
            'default' => '#28a745'
        ], [
            'name' => 'info',
            'value' => '',
            'default' => '#17a2b8'
        ], [
            'name' => 'warning',
            'value' => '',
            'default' => '#ffc107'
        ], [
            'name' => 'danger',
            'value' => '',
            'default' => '#dc3545'
        ], [
            'name' => 'light',
            'value' => '',
            'default' => '#f8f9fa'
        ], [
            'name' => 'dark',
            'value' => '',
            'default' => '#343a40'
        ], [
            'name' => 'link-color',
            'value' => '',
            'default' => '$primary'
        ], [
            'name' => 'link-hover-color',
            'value' => '',
            'default' => 'darken($link-color, 15%)'
        ]
        ];

        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        $value = $moduleSettingBridge->get('aMogaScssColors', 'tpl-manager');

        die((is_array(json_decode($value)) ? $value : json_encode($default)));
    }
    public function saveScssColors($aMogaScssVariables)
    {
        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        $moduleSettingBridge->save('aMogaScssColors', json_encode($aMogaScssVariables), 'tpl-manager');
    }
    public function resetScssColors() {
        $this->saveScssColors("");
        $this->getScssColors();
    }

    public function getScssFontsizes()
    {
        $default = [
            [
            'name' => 'font-size-root',
            'hint' => 'effects the value of `rem`, which is used for as well font sizes, paddings and margins',
            'value' => '',
            'default' => 'null'
        ], [
            'name' => 'font-size-base',
            'hint' => 'effects the font size of the body text',
            'value' => '',
            'default' => '1rem'
        ], [
            'name' => 'h1-font-size',
            'value' => '',
            'default' => '$font-size-base * 1.75'
        ], [
            'name' => 'h2-font-size',
            'value' => '',
            'default' => '$font-size-base * 1.5'
        ], [
            'name' => 'h3-font-size',
            'value' => '',
            'default' => '$font-size-base * 1.25'
        ], [
            'name' => 'h4-font-size',
            'value' => '',
            'default' => '$font-size-base'
        ], [
            'name' => 'h5-font-size',
            'value' => '',
            'default' => '$font-size-base'
        ], [
            'name' => 'h6-font-size',
            'value' => '',
            'default' => '$font-size-base'
        ]
        ];
        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        $value = $moduleSettingBridge->get('aMogaScssFontsizes', 'tpl-manager');

        die((is_array(json_decode($value)) ? $value : json_encode($default)));
    }
    public function saveScssFontsizes($aMogaScssVariables)
    {
        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        $moduleSettingBridge->save('aMogaScssFontsizes', json_encode($aMogaScssVariables), 'tpl-manager');
    }
    public function resetScssFontsizes() {
        $this->saveScssFontsizes("");
        $this->getScssFontsizes();
    }

    private function sendCssReport($aNewScssColors, $aNewScssFontsizes)
    {
        $blSendReports = Registry::getConfig()->getConfigParam("blSendReportOnSave");
        if (!$blSendReports) return;

        $aOldScssColors = json_decode(Registry::getConfig()->getConfigParam("aMogaScssColors", json_encode([])), true);
        $aOldScssFontsizes = json_decode(Registry::getConfig()->getConfigParam("aMogaScssFontsizes", json_encode([])), true);

        $oEmail = oxNew(Email::class);
        $oEmail->setSubject("MOGA Customization Report");
        $oEmail->setFrom(Registry::getConfig()->getActiveShop()->oxshops__oxinfoemail->value);
        $oEmail->setRecipient(Registry::getConfig()->getActiveShop()->oxshops__oxinfoemail->value);
        $oEmail->setViewData("oUser", Registry::getConfig()->getUser());
        $oEmail->setViewData("aOldScssColors", $aOldScssColors);
        $oEmail->setViewData("aNewScssColors", $aNewScssColors);
        $oEmail->setViewData("aOldScssFontsizes", $aOldScssFontsizes);
        $oEmail->setViewData("aNewScssFontsizes", $aNewScssFontsizes);

        $oSmarty = Registry::getUtilsView()->getSmarty();;
        $oSmarty->assign("oUser", Registry::getConfig()->getUser());
        $oSmarty->assign("aOldScssColors", $aOldScssColors);
        $oSmarty->assign("aNewScssColors", $aNewScssColors);
        $oSmarty->assign("aOldScssFontsizes", $aOldScssFontsizes);
        $oSmarty->assign("aNewScssFontsizes", $aNewScssFontsizes);
        $oEmail->setBody($oSmarty->fetch("report.tpl"));
        $oEmail->send();
    }

    public function preview()
    {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        //$request = oxNew(\OxidEsales\Eshop\Core\Request::class);

        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        $sCustomVariablesFilePath = $sScssPath . "_custom_variables.scss";

        $sCssPath = $cfg->getDir(null, "src/css", false, null, null, "moga");
        $sPreviewFilePath = $sCssPath . "preview.css";

        // check if file is writable before we start compiling
        if (file_exists($sPreviewFilePath) && !is_writable($sPreviewFilePath)) {
            die(json_encode(["status" => "danger", "msg" => "<h3>preview.css file is not writable.</h3><br/>please check file permissions on out/moga/src/css/preview.css"]));
        }

        $aPayload = json_decode(file_get_contents('php://input'), true);
        $aScssColors = $aPayload["colors"];
        $aScssFontsizes = $aPayload["fontsizes"];

        $aScssVariables = [];
        //$sCustomScss = "// do not edit this file manually! it is generated by moga customizer and will be overwritten \n";
        foreach ($aPayload["variables"] as $var) {
            $aScssVariables[$var["name"]] = (!empty($var["value"]) ? $var["value"] : $var["default"]);
            //$sCustomScss .= "$".$var["name"].": ".(!empty($var["value"]) ? $var["value"] : $var["default"]).";\n";
        }

        // save custom scss to a file
        //if(!file_put_contents($sCustomVariablesFilePath,$sCustomScss)) {
        //    die(json_encode(["status" => "danger", "msg" => "<h3>custom_variables.scss file is not writable.</h3><br/>please check file permissions on out/moga/src/scss/custom_variables.scss"]));
        //}

        $css = $this->compileScss($aScssVariables);

        if (!is_string($css)) {
            // Fehler!
            /** /Throwable $css */
            die(json_encode(["status" => "danger", "msg" => "<h3>Error compiling SCSS:</h3><br/>" . $css->getMessage()]));
        }


        //var_dump($sPreviewFilePath);

        if (!file_put_contents($sPreviewFilePath, $css)) {
            print json_encode(["status" => "danger", "msg" => "<h3>preview.css file is not writable.</h3><br/>please check file permissions on out/moga/src/css/preview.css"]);
        } else {
            setcookie("scsspreview", true, time() + 3600, "/");
            $msg = "<h3>preview.css generated.</h3><br/>You can now log in as admin user and preview changes in your <a href='" . $cfg->getShopHomeUrl() . "' target='_blank'>Shop</a>";
            print json_encode(["status" => "success", "msg" => $msg]);
        }
        die();
    }

    public function save()
    {
        $aPayload = json_decode(file_get_contents('php://input'), true);
        $aScssColors = $aPayload["colors"];
        $aScssFontsizes = $aPayload["fontsizes"];

        $this->sendCssReport($aScssColors, $aScssFontsizes);

        $this->saveScssColors($aScssColors);
        $this->saveScssFontsizes($aScssFontsizes);
        die();
    }

    private function compileScss($aScssVariables = false)
    {
        $cfg = Registry::getConfig();
        $sTwbsVendorPath = str_replace("source", "vendor/twbs/bootstrap/scss", $cfg->getConfigParam('sShopDir'));
        $sScssPath = $cfg->getDir(null, "src/scss", false, null, null, "moga");
        //$sScssFile = $cfg->getDir("_variables.scss","src",false, null, null, "moga");

        //var_dump($sScssPath);

        try {
            $scss = new \ScssPhp\ScssPhp\Compiler;
            $scss->setImportPaths([$sTwbsVendorPath, $sScssPath]);
            if ($aScssVariables) {
                $scss->setVariables($aScssVariables);
            }
            $css = $scss->compile('@import "moga";');

            $autoprefixer = new \Padaliyajay\PHPAutoprefixer\Autoprefixer($css);
            return $autoprefixer->compile();
        } catch (\Throwable $error) {
            return $error;
        }
    }
}
