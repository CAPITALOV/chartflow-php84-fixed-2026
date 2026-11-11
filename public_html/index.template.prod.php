<?php

//ini_set('display_errors', true);
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

if (!is_null($config['aliases'])) {
    foreach ($config['aliases'] as $a => $d) {
        if (!is_dir($d))
            throw new \Exception(sprintf("Can not set alias %s for %s direcory, cause directory path is wrong. Check config."));
        \Yii::setAlias($a, $d);
    }
} else {
    throw new \Exception('Не задан параметр config.aliases');
}
(new yii\web\Application($config))->run();
