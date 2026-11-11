<?php

/**
 */

namespace app\controllers;

use app\models\ChatMessage;
use app\models\Stock;
use app\models\User;
use cs\Application;
use cs\services\VarDumper;
use YandexMoney\API;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

class Cabinet_chatController extends CabinetBaseController
{
    /**
     * Выводит чат
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->user->id == Yii::$app->params['chat']['consultant_id']) {
            return $this->redirect(['superadmin_chat/index']);
        }

        return $this->render([
            'items'    => ChatMessage::getItems(),
            'userFrom' => Yii::$app->user->id,
            'userTo'   => Yii::$app->params['chat']['consultant_id'],
        ]);
    }

    /**
     * AJAX
     * Добавляет свое сообщение
     *
     * REQUEST:
     * - text - string - текст собщения
     * - to - int - идентификатор пользователя, для кого
     *
     * @return string json html сообщение мое отформатированное для чата
     */
    public function actionSend()
    {
        $to = self::getParam('to');
        $message = ChatMessage::add([
            'message'    => self::getParam('text'),
            'user_id_to' => $to,
        ]);
        $cid = Yii::$app->params['chat']['consultant_id'];
        if ($to == $cid) {
            Application::mail(User::find($cid)->getEmail(), 'Новое сообщение', 'new_message', [
                'message' => $message,
                'user'    => Yii::$app->user->identity,
            ]);
        }

        return self::jsonSuccess(
            $this->renderFile('@app/views/cabinet_chat/send.php', [
                'message' => $message,
                'user'    => Yii::$app->user->identity,
            ])
        );
    }

    /**
     * AJAX
     * Добавляет тестовое сообщение
     */
    public function actionTest()
    {
        ChatMessage::insert([
            'message'=> 'test',
            'user_id_to' => Yii::$app->user->id,
            'user_id_from' => Yii::$app->params['chat']['consultant_id'],
            'datetime' => time(),

        ]);
        return self::jsonSuccess();
    }

    /**
     * AJAX
     * Получает новые сообщения
     * Эта функция стоит как слушатель и вызывается каждые 10 секунд
     * Возвращаемые сообщения будут отсортировны от ранних до поздних
     *
     * REQUEST:
     * - from - int - идентификатор пользователя, от кого
     * - last_datetime - int - время от которого нужно получить сообщения
     *
     * @return string json array
     * [
     *     {
     *          'datetime' => int,
     *          'html'     => string
     *     }, ...
     * ]
     */
    public function actionGet_new_messages()
    {
//        self::validateRequestJson([
//            [['last_datetime', 'from'], 'required'],
//            [['last_datetime', 'from'], 'integer'],
//        ]);
        $last_datetime = self::getParam('last_datetime');
        $items = ChatMessage::query([
                'user_id_from' => self::getParam('from'),
                'user_id_to'   => \Yii::$app->user->id,
            ])
            ->andWhere(['>', 'datetime', $last_datetime])
            ->orderBy(['datetime' => SORT_ASC])
            ->all();

        $new = [];
        foreach($items as $item) {
            $new[] = [
                'datetime' => $item['datetime'],
                'html' => $this->renderFile('@app/views/cabinet_chat/get_new_messages.php', [
                    'message' => new ChatMessage($item),
                    'user'    => User::find($item['user_id_from']),
                ]),
            ];
        }

        return self::jsonSuccess($new);
    }
}
