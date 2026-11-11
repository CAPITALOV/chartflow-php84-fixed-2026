<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 27.08.2015
 * Time: 23:57
 */

namespace app\service;


use yii\base\BaseObject;

/**
 * Class CalcUnion
 * Вычисляет процент совпадения
 * Вычисление проводится по двум графикам
 * Входные графики должны быть от одной даты и до другой
 *
 * Какие есть варианты получения результата?
 * 1. одна резельтирующая цифра
 * 2. по месяцам
 *
 * @package app\services
 */
class CalcUnion extends BaseObject
{
    /**
     * Вычисляет прцент совпадения
     *
     * @param array $row1
     * [
     *    [
     *        'date' =>
     *        'kurs' =>
     *    ],
     *    ...
     * ]
     * @param array $row2
     * [
     *    [
     *        'date' =>
     *        'kurs' =>
     *    ],
     *    ...
     * ]
     */
    public function calc($row1, $row2)
    {

    }
} 