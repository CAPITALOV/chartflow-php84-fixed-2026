<?php

namespace cs\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class VarDumper
{
    public static function dump($value, $highlight = true, $depth = 10)
    {
        if (\Yii::$app->response->className() != 'yii\console\Response') {
            \Yii::$app->response->headers->set('Content-Encoding', 'utf-8');
            \Yii::$app->response->charset = 'utf-8';
            \Yii::$app->response->send();
            $isHighlight = $highlight;
        } else {
            $isHighlight = false;
        }

        \yii\helpers\VarDumper::dump($value, $depth, $isHighlight);
        $c = 1;

        echo "\r\n";
        echo '<pre>';
        foreach (debug_backtrace(2) as $item) {
            echo '#' . $c . ' ' . ArrayHelper::getValue($item, 'file', '') . ':' . ArrayHelper::getValue($item, 'line', '') . ' ' . ArrayHelper::getValue($item, 'class', '') . ArrayHelper::getValue($item, 'type', '') . ArrayHelper::getValue($item, 'function', '') . "\n";
            $c++;
        }
        echo '</pre>';
        exit;
    }
} 