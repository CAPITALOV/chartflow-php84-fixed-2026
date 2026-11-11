<?php

/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\assets\Slider;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@app/assets/Slider/jQRangeSlider-5.7.1';
    public $css      = [
        'css/iThing-min.css',
    ];
    public $js       = [
        'lib/jquery.mousewheel.min.js',
        'jQAllRangeSliders-withRuler-min.js'
    ];
    public $depends  = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];
}
