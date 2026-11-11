<?php

namespace app\models;

use app\models\NewsItem;
use app\models\User;
use app\services\GsssHtml;
use cs\services\Str;
use cs\services\VarDumper;
use Yii;
use yii\base\Model;
use cs\Widget\FileUpload2\FileUpload;
use yii\db\Query;
use yii\helpers\Html;

/**
 *
 */
class WalletHistory extends \cs\base\DbRecord
{
    const TABLE = 'cap_users_wallet_history';

    /**
     * Добавляет запись в историю
     *
     * @param array|string $fields поля для добавления или строка описания
     *
     * @return static
     */
    public static function insert($fields)
    {
        if (!is_array($fields)) {
            $fields = [
                'description' => $fields
            ];
        }
        $fields['user_id'] = Yii::$app->user->id;
        $fields['datetime'] = time();

        return parent::insert($fields);
    }
}
