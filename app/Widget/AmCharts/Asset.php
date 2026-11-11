<?php

/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace cs\Widget\AmCharts;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@vendor/amcharts/amcharts4/';

    // public $css      = [
    //     'default.css',
    // ];

    public $js       = [
        'core.js',
        'charts.js',
        'lang/ru_RU.js',
        'themes/animated.js',
    ];

    public $depends  = [
    ];

    public $jsOptions = [
    ];

    public function init()
    {
    }
}
