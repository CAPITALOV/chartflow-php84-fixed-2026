<?php


namespace app\assets\LayoutSite;

use yii\web\AssetBundle;
use Yii;

/**
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@app/assets/LayoutSite/source';
    public $css      = [
        'css/modern-business.css',
        'font-awesome/css/font-awesome.min.css',
        '/css/landing.css',
    ];
    public $js       = [
        'js/index.js'
    ];
    public $depends  = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
