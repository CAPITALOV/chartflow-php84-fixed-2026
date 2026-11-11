<?php

namespace app\controllers;

use app\models\ChatMessage;
use app\models\Stock;
use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\UserException;

class Superadmin_chatController extends SuperadminBaseController
{
    /**
     * Показывает все чаты
     */
    public function actionIndex()
    {


        return $this->render([
            'items'  => ChatMessage::query()
                ->select([
                    'cap_chat_messages.user_id_from as id',
                    'max(cap_chat_messages.datetime) as datetime',
                    'cap_users.name_first',
                    'cap_users.name_last',
                    'cap_users.email',
                    'cap_users.avatar',
                ])
                ->groupBy('if (`cap_chat_messages`.`user_id_from` = 7, `cap_chat_messages`.`user_id_to`, `cap_chat_messages`.`user_id_from`)')
                ->innerJoin('cap_users', 'cap_users.id = cap_chat_messages.user_id_from')
                ->orderBy(['max(cap_chat_messages.datetime)' => SORT_DESC])
                ->all()
        ]);
    }

    /**
     * Показывает разговор с конкретным пользователем
     *
     * @return string|\yii\web\Response
     */
    public function actionUser($id)
    {
        return $this->render([
            'items'    => ChatMessage::getItems($id),
            'userFrom' => Yii::$app->user->id,
            'userTo'   => $id,
        ]);
    }
}
