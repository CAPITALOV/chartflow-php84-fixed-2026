<?php

/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\assets\ECharts;

use yii\web\AssetBundle;
use Yii;

/**
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@vendor/ecomfe/echarts';
    public $css      = [
    ];
    public $js       = [
        'build/dist/echarts-all.js',
    ];
    public $depends  = [
    ];

    public function init(){

    }
}
