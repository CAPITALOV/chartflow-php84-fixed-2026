<?php

namespace app\commands;

use app\models\SubscribeMailItem;
use yii\console\Controller;
use yii\console\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Занимается обслуживанием авторизации
 */
class AuthController extends Controller
{
    /**
     * Очищает старые заявки на регистрацию
     */
    public function actionClear_registration()
    {
        \app\service\RegistrationDispatcher::cron();
    }
    /**
     * Очищает старые заявки на смену email
     */
    public function actionClear_change_email()
    {
        \app\service\EmailChangeDispatcher::cron();
    }
}
