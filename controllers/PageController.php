<?php

/*
 * Класс для действий незерегистрированного пользователя
 *
 */

namespace app\controllers;

use app\models\StockKurs;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UserForm;
use app\models\Form\UserPassword as FormUserPassword;

class PageController extends \cs\base\BaseController
{
    public function actionAbout()
    {
        return $this->render();
    }
}
