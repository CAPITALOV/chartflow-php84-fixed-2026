<?php

/**
 * Класс для действий дизайнера
 *
 */

namespace app\controllers;

use app\models\Stock;
use app\models\User;
use app\models\UserRole;
use app\models\UserStock;
use cs\services\VarDumper;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class DesignerController extends CabinetBaseController
{
    public $layout = 'admin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->isRole(UserRole::ROLE_DESIGNER);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['designer/landing']);
    }

    /**
     */
    public function actionLanding()
    {
        $model = \app\models\Form\Landing::find(1);

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }
}
