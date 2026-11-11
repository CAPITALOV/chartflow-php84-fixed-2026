<?php

namespace app\service;

use cs\services\VarDumper;

class Counters
{


    /**
     * Возвращает коды счетчиков с тегами <script>
     *
     * @return string
     */
    public static function get()
    {
        $key = 'statistic.counters';
        $data = \Yii::$app->cache->get($key);
        if ($data === false) {
            $data = file_get_contents(\Yii::getAlias('@app/views/blocks/counters.txt'));
            \Yii::$app->cache->set($key, $data);
        }

        return $data;
    }


}