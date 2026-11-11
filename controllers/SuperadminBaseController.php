<?php

/**
 * Класс для действий Superadmin
 *
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\User;

class SuperadminBaseController extends \cs\base\BaseController
{
    public $layout = 'admin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['login'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->isAdmin();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Логирует действие пользователя
     */
    public function log($description) {
        parent::logAction(Yii::$app->user->identity->getId(), $description);
    }
}
