<?php


namespace app\service;

use app\models\Service;
use cs\helpers\Html;
use cs\services\Str;
use cs\services\VarDumper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\UnionCategory;
use app\models\Union;

class GsssHtml
{
    public static $formatIcon = [
        370,
        370,
        \cs\Widget\FileUpload2\FileUpload::MODE_THUMBNAIL_CUT
    ];

    /**
     * Возвращает дату в формате "d M Y г." например "1 Дек 2015 г."
     *
     * @param $date 'yyyy-mm-dd'
     *
     * @return string
     */
    public static function dateString($date)
    {
        if (is_null($date)) return '';
        if ($date == '') return '';
        if (!Str::isContain($date, '-')) $date = date('Y-m-d', $date);

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        if (substr($month, 0, 1) == '0') $month = substr($month, 1, 1);
        if (substr($day, 0, 1) == '0') $day = substr($day, 1, 1);
        $monthList = [
            1  => 'Янв',
            2  => 'Фев',
            3  => 'Мар',
            4  => 'Апр',
            5  => 'Май',
            6  => 'Июн',
            7  => 'Июл',
            8  => 'Авг',
            9  => 'Сен',
            10 => 'Окт',
            11 => 'Ноя',
            12 => 'Дек',
        ];
        $month = $monthList[ $month ];

        return "{$day} {$month} {$year} г.";
    }

} 