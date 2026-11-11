<?php

$params = \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    'id'           => 'basic',
    'basePath'     => dirname(__DIR__),
    'bootstrap'    => ['log'],
    'defaultRoute' => 'site/index',
    'language'     => 'ru',
    'timeZone'     => 'Europe/Moscow',
    'aliases'      => [
        '@web'    => __DIR__ . '/../public_html',
        '@csRoot' => __DIR__ . '/../app',
        '@vendor' => __DIR__ . '/../vendor',
        '@bower'  => __DIR__ . '/../vendor/bower-asset',
    ],
    'homeUrl'      => ['cabinet/index'],
    'components'   => [
        'authClientCollection' => [
            'class'   => 'yii\authclient\Collection',
            'clients' => require(__DIR__ . '/authClientCollection.php'),
        ],
        'session'              => [
            'class' => 'yii\web\CacheSession',
        ],
        'request'              => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'enableCookieValidation' => false,
            'enableCsrfValidation'   => false,
            'cookieValidationKey'    => '',
        ],
        'cache'                =>
            (YII_ENV_PROD) ?
                [
                    'class'   => 'yii\caching\MemCache',
                    'servers' => [
                        [
                            'host' => 'localhost',
                            'port' => 11211,
                        ],
                    ],
                ] :
                [
                    'class' => 'yii\caching\FileCache',
                ],
        'user'                 => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl'        => ['auth/login'],
        ],
        'view'                 => [
            'renderers' => [
                'tpl' => [
                    'class'     => 'yii\smarty\ViewRenderer',
                    'cachePath' => '@runtime/Smarty/cache',
                    'widgets'   => [
                        'blocks' => [
                            'ActiveForm' => 'yii\widgets\ActiveForm',
                        ],
                    ],
                ],
            ],
            'on ' . \yii\web\View::EVENT_END_BODY => function () {
                if (YII_ENV_PROD) {
                    echo \app\service\Counters::get();
                }
            }
        ],
        'errorHandler'         => [
            'errorAction' => 'site/error',
        ],
        'mailer'               => require(__DIR__ . '/mailerTransport.php'),
        'log'                  => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => [
                        'error',
                        'warning',
                    ],
                    'maxLogFiles' => 1,
                ],
                [
                    'class'      => 'yii\log\DbTarget',
                    'categories' => ['cap\\*'],
                ],
                [
                    'class'      => 'yii\log\EmailTarget',
                    'levels'     => [
                        'error',
                        'warning',
                    ],
                    'categories' => ['yii\db\*'],
                    'message'    => [
                        'from' => ['smtp@chartflow.ru' => 'ChartFlow'],
                        'to'      => ['smtp@chartflow.ru'],
                        'subject' => 'chartflow.ru ERROR',
                    ],
                ],
            ],
        ],
        'urlManager'           => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
            'suffix'              => '',
            'rules'               => require(__DIR__ . '/urlRules.php'),
        ],
        'db'                   => require(__DIR__ . '/db.php'),
        'deviceDetect'         => [
            'class'     => 'app\service\DeviceDetect',
            'setParams' => 'false',
        ],

        'formatter'            => [
            'dateFormat'        => 'dd.MM.yyyy',
            'timeFormat'        => 'php:H:i:s',
            'datetimeFormat'    => 'php:d.m.Y в H:i',
            'decimalSeparator'  => '.',
            'thousandSeparator' => ' ',
            'currencyCode'      => 'RUB',
            'locale'            => 'ru-RU',
            'nullDisplay'       => '',
        ],
    ],
    'params'       => $params,
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
