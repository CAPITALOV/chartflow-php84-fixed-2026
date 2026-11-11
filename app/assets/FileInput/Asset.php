<?php


namespace cs\assets\FileInput;

use yii\web\AssetBundle;
use Yii;

/**
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@csRoot/assets/FileInput/source';
    public $css      = [
    ];
    public $js       = [
        'bootstrap.file-input.js'
    ];
    public $depends  = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
