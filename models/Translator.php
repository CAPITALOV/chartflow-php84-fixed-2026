<?php

namespace app\models;

use Yii;

/**
 * @author Andrew Lykov <andrew.lykov@yandex.ru>
 */
class Translator {

    /**
     * To make Poedit finely parse source files we change order of params here
     * @param string $message
     * @param array $params
     * @param string $category
     * @param string $language
     */
    public static function t($message, $params = [], $category = 'app', $language = null) {
        return Yii::t($category, $message, $params, $language);
    }

}
