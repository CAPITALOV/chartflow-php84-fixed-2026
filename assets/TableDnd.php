<?php

/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class TableDnd extends AssetBundle
{
    public $sourcePath = '@vendor/isocra/TableDnD';
    public $css        = [
    ];
    public $js         = [
        'js/jquery.tablednd.js'
    ];
    public $depends    = [
        'yii\web\JqueryAsset',
    ];
}
