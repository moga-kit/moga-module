<?php

namespace MogaKit\TplManager\Application\Controller\Admin;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Mogacustomizer extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    protected $_sThisTemplate = 'customizer.tpl';

    public function getCustomSass() {
        $cfg = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sCustomSassFile = $cfg->getDir("custom.scss","src",false, null, null, "moga");
//getDir($file, $this->_sResourceDir, $admin)

        //var_dump($sCustomSassFile);
        //var_dump($sTemplate);
//"custom.sass"
        //$aTemplateOptions = glob($sTplPath.$sTemplate);

        return file_get_contents($sCustomSassFile);
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
}