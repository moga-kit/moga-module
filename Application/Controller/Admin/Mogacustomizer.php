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
    private $sPreviewCSS = false;
    private $sLiveCSS = false;

    public function init()
    {
        parent::init();

        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $this->sSourcePath = $oConfig->getViewsDir(true) . "moga/build/scss/";
        $this->sCustomVariables = $this->sSourcePath . "_custom_variables.scss";
        $this->sCustomStyles = $this->sSourcePath . "_custom_styles.scss";
        $this->sPreviewCSS = $oConfig->getDir('preview.css', "src/css", false, null, null, "moga");
        $this->sLiveCSS = $oConfig->getDir('styles.min.css', "src/css", false, null, null, "moga");
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


    public function getScssColors()
    {
        $default = [
            [
                'name' => 'primary',
                'value' => '',
                'default' => '#7952b3'
            ],
            [
                'name' => 'secondary',
                'value' => '',
                'default' => '#6c757d'
            ],
            [
                'name' => 'success',
                'value' => '',
                'default' => '#28a745'
            ],
            [
                'name' => 'info',
                'value' => '',
                'default' => '#17a2b8'
            ],
            [
                'name' => 'warning',
                'value' => '',
                'default' => '#ffc107'
            ],
            [
                'name' => 'danger',
                'value' => '',
                'default' => '#dc3545'
            ],
            [
                'name' => 'light',
                'value' => '',
                'default' => '#f8f9fa'
            ],
            [
                'name' => 'dark',
                'value' => '',
                'default' => '#343a40'
            ],
            [
                'name' => 'body-color',
                'value' => '',
                'default' => '#333'
            ],
            [
                'name' => 'body-bg',
                'value' => '',
                'default' => '#fff'
            ],
            [
                'name' => 'link-color',
                'value' => '',
                'default' => '$primary'
            ],
            [
                'name' => 'link-hover-color',
                'value' => '',
                'default' => 'darken($link-color, 15%)'
            ]
        ];

        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        $value = $moduleSettingBridge->get('aMogaScssColors', 'moga');

        die((is_array(json_decode($value)) ? $value : json_encode($default)));
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
        if (!file_put_contents($this->sCustomVariables, $header.join("\n", $aCustomVariables))) {
            Registry::getUtilsView()->addErrorToDisplay("Fehler beim Schreiben in die Datei _custom_variables.scss");
        }
    }

    public function getCustomStyles()
    {
        return (file_exists($this->sCustomStyles) ? file_get_contents($this->sCustomStyles) : "");
    }

    private function saveCustomStyles($sCustomStyles)
    {
        $header = "/* add your custom scss code here */\n";
        if (!file_put_contents($this->sCustomStyles, $header.$sCustomStyles)) {
            Registry::getUtilsView()->addErrorToDisplay("Fehler beim Schreiben in die Datei _custom_styles.scss");
        }
    }

    public function preview()
    {
        // check if all files are writable
        if (!is_writable($this->sPreviewCSS)) {
            $this->error("<h3>preview.css file is not writable.</h3><br/>please check file permissions on {$this->sPreviewCSS}"); //phpcs:ignore
        }

        $success = $this->compileScss(false);

        if ($success) {
            setcookie("scsspreview", true, time() + 3600, "/");
            $this->success("<h3>Preview erfolgreich!</h3><br/>Du kannst dich jetzt mit deinem Administrator Konto im <a href='" . Registry::getConfig()->getShopHomeUrl() . "' target='_blank'>Shop</a> anmelden, um die Vorschau zu sehen.");
        } else {
            $this->error("<h3>Preview fehlgeschlagen!</h3><br/>Prüfe den Fehler Log.");
        }
    }


    public function live()
    {
        // check if all files are writable
        if (!is_writable($this->sLiveCSS)) {
            $this->error("<h3>styles.min.css file is not writable.</h3><br/>please check file permissions on {$this->sLiveCSS}"); //phpcs:ignore
        } elseif (!is_writable($this->sCustomVariables)) {
            $this->error("<h3>_custom_variables.scss file is not writable.</h3><br/>please check file permissions on {$this->sCustomVariables}"); //phpcs:ignore
        } elseif (!is_writable($this->sCustomStyles)) {
            $this->error("<h3>_custom_styles.scss file is not writable.</h3><br/>please check file permissions on {$this->sCustomStyles}"); //phpcs:ignore
        }

        $success = $this->compileScss(true);

        if ($success) {
            setcookie("scsspreview", true, time() + 3600, "/");
            $this->success("<h3>Änderung erfolgreich!</h3>");
        } else {
            $this->error("<h3>Änderung fehlgeschlagen!</h3><br/>Prüfe den Fehler Log.");
        }
    }

    private function compileScss($blFinal = false)
    {
        $aPayload = json_decode(file_get_contents('php://input'), true);
        $aScssCustomVariables = [];

        foreach ($aPayload["colors"] as $var) {
            $aScssCustomVariables[$var["name"]] = (!empty($var["value"]) ? $var["value"] : $var["default"]);
            //$sCustomScss .= "$".$var["name"].": ".(!empty($var["value"]) ? $var["value"] : $var["default"]).";\n";
        }

        foreach ($aPayload["fontsizes"] as $var) {
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
            if ($blFinal) file_put_contents($this->sLiveCSS,$css);
            else file_put_contents($this->sPreviewCSS,$css);
        }
        // save all the new variables to database
        if ($blFinal) {
            $this->saveScssColors($aPayload["colors"]);
            $this->saveScssFontsizes($aPayload["fontsizes"]);
        }

        return (bool) $css;
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

    private function error($msg)
    {
        die(json_encode(["status" => "red","msg" => $msg]));
    }

    private function success($msg)
    {
        die(json_encode(["status" => "green","msg" => $msg]));
    }
}
