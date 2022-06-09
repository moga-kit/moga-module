<?php

namespace Moga\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;

class Mogacustomizer extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    protected $_sThisTemplate = 'customizer.tpl';

    private $sSourcePath = false;
    private $sCustomVariables = false;
    private $sCustomStyles = false;
    private $sCssDir = false;
    private $sPreviewCSS = false;
    private $sLiveCSS = false;

    public function init()
    {
        parent::init();

        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $this->sSourcePath = $oConfig->getViewsDir(true) . "moga/build/scss/";
        $this->sCustomVariables = $this->sSourcePath . "_custom_variables.scss";
        $this->sCustomStyles = $this->sSourcePath . "_custom_styles.scss";
        $this->sCssDir = $oConfig->getDir('', "src/css", false);
        $this->sPreviewCSS = $oConfig->getDir('preview.css', "src/css", false);
        $this->sLiveCSS = $oConfig->getDir('styles.min.css', "src/css", false);
    }

    public function render()
    {
        parent::render();
        $this->addTplParam("aMessages", $this->prechecks());
        $this->addTplParam("oConfig", Registry::getConfig());
        $this->addTplParam("aScssColors", $this->_getScssColors());

        return $this->_sThisTemplate;
    }

    private function prechecks()
    {
        $oLang = Registry::getLang();
        $aChecks = [];
        if (!is_writable($this->sCssDir)) $aChecks[] = $this->error("MOGA_DIR_NOT_WRITABLE", $this->sCssDir);
        else {
            if (file_exists($this->sPreviewCSS) && !is_writable($this->sPreviewCSS)) $aChecks[] = $this->error("MOGA_FILE_NOT_WRITABLE", $this->sPreviewCSS);
            if (file_exists($this->sLiveCSS) && !is_writable($this->sLiveCSS)) $aChecks[] = $this->error("MOGA_FILE_NOT_WRITABLE", $this->sLiveCSS);
        }

        return $aChecks;
    }

    // bootstrap über composer zu verwalten ist behindert.
    // Sollte vnm Composer und shop-dependencies abgekoppelt sein, aber nicht jeder hat nodejs auf dem Server
    private $aSourceLibraries = [
        "bootstrap" => "https://github.com/twbs/bootstrap/archive/v5.0.0-beta1.zip"
    ];

    public function checkSourceFiles()
    {
        $oConfig = Registry::getConfig();
    }

    public function downloadSourceFiles()
    {
        foreach ($this->aSourceLibraries as $lib => $url) {
            return;
        }
    }

    private $aScssColors = null;

    private function _getScssColors()
    {
        if ($this->aScssColors === null)
        {
            $aColors = [
                'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark',
                'body-color', 'body-bg', 'link-color', 'link-hover-color',
            ];
            $aScssVariables = $this->getScssVariables();
            $aCustomScssVaraibles = $this->getCustomScssVariables();

            $this->aScssColors = [];
            foreach ($aColors as $variable) {
                $this->aScssColors[$variable] = [
                    "name" => $variable,
                    "default" => $aScssVariables[$variable]["default"],
                    "value" => $aCustomScssVaraibles[$variable]["value"] ?? ""
                ];
            }
        }
        return $this->aScssColors;
    }

    public function getScssColors()
    {
        die(json_encode($this->_getScssColors));
    }

    private function saveScssColors($aMogaScssColors)
    {
        ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class)
            ->save('aMogaScssColors', json_encode($aMogaScssColors), 'moga');
    }

    public function resetScssColors()
    {
        $this->saveScssColors(null);
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
            ],
            [
                'name' => 'font-size-base',
                'hint' => 'effects the font size of the body text',
                'value' => '',
                'default' => '1rem'
            ],
            [
                'name' => 'h1-font-size',
                'value' => '',
                'default' => '$font-size-base * 1.75'
            ],
            [
                'name' => 'h2-font-size',
                'value' => '',
                'default' => '$font-size-base * 1.5'
            ],
            [
                'name' => 'h3-font-size',
                'value' => '',
                'default' => '$font-size-base * 1.25'
            ],
            [
                'name' => 'h4-font-size',
                'value' => '',
                'default' => '$font-size-base'
            ],
            [
                'name' => 'h5-font-size',
                'value' => '',
                'default' => '$font-size-base'
            ],
            [
                'name' => 'h6-font-size',
                'value' => '',
                'default' => '$font-size-base'
            ]
        ];
        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        $value = $moduleSettingBridge->get('aMogaScssFontsizes', 'moga');

        die((is_array(json_decode($value)) ? $value : json_encode($default)));
    }

    private function saveScssFontsizes($aMogaScssFontsizes)
    {
        ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class)
            ->save('aMogaScssFontsizes', json_encode($aMogaScssFontsizes), 'moga');
    }

    public function resetScssFontsizes()
    {
        $this->saveScssFontsizes(null);
        $this->getScssFontsizes();
    }

    private function saveCustomVariables($aCustomVariables)
    {
        $header = "/* do not edit this file manually, it will be overwritten by moga customizer*/\n";
        if (!file_put_contents($this->sCustomVariables, $header . join("\n", $aCustomVariables))) {
            Registry::getUtilsView()->addErrorToDisplay("Fehler beim Schreiben in die Datei _custom_variables.scss");
        }
    }

    public function getCustomStyles()
    {
        return (file_exists($this->sCustomStyles) ? file_get_contents($this->sCustomStyles) : "");
    }

    private $aScssVariables = null;

    public function getScssVariables()
    {
        if ($this->aScssVariables === null) {
            $aFile = file($this->sSourcePath . "_variables.scss");
            $this->aScssVariables = [];
            foreach ($aFile as $row) {
                preg_match('/^\$([^:]+):\s?(.+);/', $row, $matches);
                if (!$matches) continue;
                $name = $matches[1];
                $value = preg_replace("/\s?\!default/","",$matches[2]);
                $this->aScssVariables[$name] = ["name" => $name, "default" => $value];
            }
        }

        return $this->aScssVariables;
    }

    private $aCustomScssVariables = null;

    public function getCustomScssVariables()
    {
        if ($this->aCustomScssVariables === null) {
            $aFile = file($this->sSourcePath . "_custom_variables.scss");
            $this->aCustomScssVariables = [];
            foreach ($aFile as $row) {
                preg_match('/^\$([^:]+):\s?(.+);/', $row, $matches);
                if (!$matches) continue;
                $name = $matches[1];
                $value = $matches[2];
                $this->aCustomScssVariables[$name] = ["name" => $name, "value" => $value];
            }
        }

        return $this->aCustomScssVariables;
    }

    private function saveCustomStyles($sCustomStyles)
    {
        $header = "/* add your custom scss code here */\n";
        if (!file_put_contents($this->sCustomStyles, $header . $sCustomStyles)) {
            Registry::getUtilsView()->addErrorToDisplay("Fehler beim Schreiben in die Datei _custom_styles.scss");
        }
    }

    public function preview()
    {
        $aMsg = [];
        // check if file is writable
        if (!is_writable($this->sPreviewCSS)) $aMsg[] = $this->error("<h3>preview.css file is not writable.</h3><br/>please check file permissions on {$this->sPreviewCSS}"); //phpcs:ignore

        if(!empty($aMsg)) $this->exit($aMsg);

        if($this->compileScss(false))
        {
            setcookie("scsspreview", true, time() + 3600, "/");
            $aMsg[] = $this->success("MOGA_PREVIEW_SUCCESS",Registry::getConfig()->getShopHomeUrl());
        }
        else $aMsg[] = $this->error("<h3>Preview fehlgeschlagen!</h3><br/>Prüfe den Fehler Log.");

        $this->exit($aMsg);
    }


    public function live()
    {
        $aMsg = [];
        // check if all files are writable
        if (!is_writable($this->sLiveCSS)) $aMsg[] = $this->error("<h3>styles.min.css file is not writable.</h3><br/>please check file permissions on {$this->sLiveCSS}"); //phpcs:ignore
        if (!is_writable($this->sCustomVariables)) $aMsg[] = $this->error("<h3>_custom_variables.scss file is not writable.</h3><br/>please check file permissions on {$this->sCustomVariables}"); //phpcs:ignore
        if (!is_writable($this->sCustomStyles)) $aMsg[] = $this->error("<h3>_custom_styles.scss file is not writable.</h3><br/>please check file permissions on {$this->sCustomStyles}"); //phpcs:ignore

        if(!empty($aMsg)) $this->exit($aMsg);

        $aMsg[] = ($this->compileScss(true) ? $this->success("<h3>Änderung erfolgreich!</h3>") : $this->error("<h3>Änderung fehlgeschlagen!</h3><br/>Prüfe den Fehler Log."));

        $this->exit($aMsg);
    }

    private function compileScss($blFinal = false)
    {
        $aPayload = json_decode(file_get_contents('php://input'), true);
        $aScssCustomVariables = [];

        foreach ($aPayload["scssVariables"] as $var) {
            $aScssCustomVariables[$var["name"]] = (!empty($var["value"]) ? $var["value"] : $var["default"]);
            //$sCustomScss .= "$".$var["name"].": ".(!empty($var["value"]) ? $var["value"] : $var["default"]).";\n";
        }

        $sScssCustomStyles = trim($aPayload["customstyles"]);


        if ($blFinal) {
            // write custom stuff into files before compiling because they will be imported
            $this->saveCustomVariables($aScssCustomVariables);
            $this->saveCustomStyles($sScssCustomStyles);
        }

        $scss = '@import "style";';
        $css = false;

        // scss import paths
        $oConfig = Registry::getConfig();
        $sBootstrapScssPath = $oConfig->getViewsDir(true) . "moga/node_modules/bootstrap/scss";

        try {
            $oCompiler = new \ScssPhp\ScssPhp\Compiler;
            $oCompiler->setImportPaths([$this->sSourcePath, $sBootstrapScssPath]);
            if (!$blFinal) {
                $oCompiler->setVariables($aScssCustomVariables);
                $scss .= $sScssCustomStyles;
            }
            $css = $oCompiler->compile($scss);

            //$autoprefixer = new \Padaliyajay\PHPAutoprefixer\Autoprefixer($css);
            //return $autoprefixer->compile();
        } catch (\Throwable $error) {
            $this->error(print_r($error, true));
        }

        if ($css) {
            //$this->sendCssReport($aPayload["colors"], $aPayload["fontsizes"]);
            if ($blFinal) file_put_contents($this->sLiveCSS, $css);
            else file_put_contents($this->sPreviewCSS, $css);
        }
        // save all the new variables to database
        if ($blFinal) {
            $this->saveScssColors($aPayload["colors"]);
            $this->saveScssFontsizes($aPayload["fontsizes"]);
        }

        return (bool)$css;
    }

    private function sendCssReport($aNewScssColors, $aNewScssFontsizes)
    {
        $blSendReports = Registry::getConfig()->getConfigParam("blSendReportOnSave");
        if (!$blSendReports) {
            return;
        }

        $aMogaScssColors = Registry::getConfig()->getConfigParam("aMogaScssColors", json_encode([]));
        $aOldScssColors = json_decode($aMogaScssColors, true);
        $aMogaScssFontsizes = Registry::getConfig()->getConfigParam("aMogaScssFontsizes", json_encode([]));
        $aOldScssFontsizes = json_decode($aMogaScssFontsizes, true);

        $oEmail = oxNew(Email::class);
        $oEmail->setSubject("MOGA Customization Report");
        $oEmail->setFrom(Registry::getConfig()->getActiveShop()->oxshops__oxinfoemail->value);
        $oEmail->setRecipient(Registry::getConfig()->getActiveShop()->oxshops__oxinfoemail->value);
        $oEmail->setViewData("oUser", Registry::getConfig()->getUser());
        $oEmail->setViewData("aOldScssColors", $aOldScssColors);
        $oEmail->setViewData("aNewScssColors", $aNewScssColors);
        $oEmail->setViewData("aOldScssFontsizes", $aOldScssFontsizes);
        $oEmail->setViewData("aNewScssFontsizes", $aNewScssFontsizes);

        $oSmarty = Registry::getUtilsView()->getSmarty();

        $oSmarty->assign("oUser", Registry::getConfig()->getUser());
        $oSmarty->assign("aOldScssColors", $aOldScssColors);
        $oSmarty->assign("aNewScssColors", $aNewScssColors);
        $oSmarty->assign("aOldScssFontsizes", $aOldScssFontsizes);
        $oSmarty->assign("aNewScssFontsizes", $aNewScssFontsizes);
        $oEmail->setBody($oSmarty->fetch("report.tpl"));
        $oEmail->send();
    }

    private function error($ident, $arg = "")
    {
        return [ "type" => "danger", "txt" => sprintf(Registry::getLang()->translateString($ident), $arg) ];
    }

    private function success($ident, $arg = "")
    {
        return [ "type" => "success", "txt" => sprintf(Registry::getLang()->translateString($ident), $arg) ];
    }
    private function exit($aMessages)
    {
        header('Content-Type: application/json');
        die(json_encode($aMessages));
    }
}
