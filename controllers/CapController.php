<?php

/*
 * Класс для действий незерегистрированного пользователя
 *
 */

namespace app\controllers;

use app\models\Log;
use app\models\Stock;
use app\models\StockKurs;
use app\models\User;
use app\service\LogReader;
use cs\services\Security;
use cs\services\Url;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UserForm;
use app\models\Form\UserPassword as FormUserPassword;

class CapController extends \cs\base\BaseController
{
    public $layout = 'blank';

    public function actionCap()
    {
        return $this->render('cap');
    }

}
