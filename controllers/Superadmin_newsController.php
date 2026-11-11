<?php

namespace app\controllers;

use app\models\NewsItem;
use app\models\Registration;
use app\models\Request;
use app\models\Stock;
use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\UserException;

class Superadmin_newsController extends SuperadminBaseController
{

    public function actionIndex()
    {
        $items = NewsItem::query()
            ->orderBy(['datetime' => SORT_DESC])
            ->all();

        return $this->render([
            'items' => $items,
        ]);
    }

    /**
     * Добавляет
     *
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new \app\models\Form\NewsItem();
        if ($model->load(Yii::$app->request->post()) && $model->insert()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * Редактирует
     *
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $model = \app\models\Form\NewsItem::find($id);

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * Удаляет новость
     *
     * @param int $id
     *
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        NewsItem::find($id)->delete();

        return self::jsonSuccess();
    }
}

