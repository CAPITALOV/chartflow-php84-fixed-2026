<?php

namespace app\models;

use app\models\NewsItem;
use app\models\User;
use app\services\GsssHtml;
use cs\services\Str;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\Model;
use cs\Widget\FileUpload2\FileUpload;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 */
class ChatMessage extends \cs\base\DbRecord
{
    const TABLE = 'cap_chat_messages';

    /**
     * Добавляет сообщение
     *
     * @param array $fields
     *     [
     *     'message' => string
     *     'user_id_to' => integer
     *     ]
     *
     * @return static
     */
    public static function add($fields)
    {
        $fields['datetime'] = time();
        $fields['user_id_from'] = Yii::$app->user->id;

        return parent::insert($fields);
    }

    public function getDateTime()
    {
        return $this->getField('datetime');
    }

    public function getText()
    {
        return $this->getField('message');
    }

    public static function getItems($id = null)
    {
        $me = (int)Yii::$app->user->id;
        if (is_null($id)) {
            $id = ArrayHelper::getValue(Yii::$app->params, 'chat.consultant_id', null);
            if (is_null($id)) {
                throw new Exception('Не указан ID консультанта в систме, укажите в /config/params.php параметр `chat.consultant_id`');
            }
        }

        return self::query()
            ->where([
                'user_id_from' => $me,
                'user_id_to'   => $id,
            ])
            ->orWhere([
                'user_id_from' => $id,
                'user_id_to'   => $me,
            ])
            ->orderBy(['datetime' => SORT_ASC])
            ->all();
    }
}
