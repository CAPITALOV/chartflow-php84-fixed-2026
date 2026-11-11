<?php

namespace cs\assets\Confirm;

use yii\web\AssetBundle;

/**
 * http://mistic100.github.io/Bootstrap-Confirmation/
 */
class Asset extends AssetBundle
{
    public $sourcePath  = '@vendor/mistic100/Bootstrap-Confirmation';
    public $css     = [
    ];
    public $js      = [
        'bootstrap-confirmation.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
