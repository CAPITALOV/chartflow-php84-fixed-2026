<?php

namespace app\models;

use cs\services\BitMask;
use cs\services\VarDumper;
use yii\db\Query;

class Registration extends \cs\base\DbRecord
{
    const TABLE = 'cap_registration';

    /**
     * Добавляет реферальну ссылку
     *
     * @param array $fields реферальная ссылка и кто зарегистрировался
     * [
     *    'user_id' => int
     *    'referal_code' => str
     * ]
     *
     * @return static
     */
    public static function insert($fields)
    {
        $fields['datetime'] = time();

        return parent::insert($fields);
    }
}