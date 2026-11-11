<?php

/**
 * Класс для действий Superadmin
 *
 */

namespace app\controllers;

use app\models\Stock;
use app\models\WalletHistory;
use cs\services\VarDumper;
use YandexMoney\API;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

class MoneyController extends CabinetBaseController
{
    /**
     * Выводит историю платежей
     *
     * @return string|\yii\web\Response
     */
    public function actionHistory()
    {
        return $this->render([
            'items' => WalletHistory::query(['user_id' => \Yii::$app->user->id])
            ->select([
                'id',
                'datetime',
                'description',
            ])
            ->orderBy(['datetime' => SORT_DESC])
            ->all()
        ]);

    }

}
