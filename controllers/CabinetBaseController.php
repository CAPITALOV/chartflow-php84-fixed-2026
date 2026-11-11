<?php

/**
 * Класс для действий Superadmin
 *
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\User;

class CabinetBaseController extends \cs\base\BaseController
{
    public $layout = 'main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
