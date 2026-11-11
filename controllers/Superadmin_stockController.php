<?php

namespace app\controllers;

use app\models\Stock;
use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

class Superadmin_stockController extends SuperadminBaseController
{

    public function actionIndex()
    {
        $items = Stock::query()
            ->orWhere(['is_enabled' => null])
            ->orWhere(['is_enabled' => 0])
            ->all();
        $red = StockPrognosisRed::query()
            ->select([
                'stock_id',
                'MIN(`date`) as min',
                'MAX(`date`) as max',
            ])
            ->groupBy('stock_id')
            ->all();
        $blue = StockPrognosisBlue::query()
            ->select([
                'stock_id',
                'MIN(`date`) as min',
                'MAX(`date`) as max',
            ])
            ->groupBy('stock_id')
            ->all();
        $kurs = StockKurs::query()
            ->select([
                'stock_id',
                'MIN(`date`) as min',
                'MAX(`date`) as max',
            ])
            ->groupBy('stock_id')
            ->all();

        return $this->render([
            'items' => $items,
            'red'   => $red,
            'blue'  => $blue,
            'kurs'  => $kurs,
        ]);
    }

    /**
     * AJAX
     * Обновляет код finam_code для котировки
     *
     * REQUEST:
     * - id - int - идентификатор котировки
     * - code - str - код finam_code для котировки
     */
    public function actionUpdate_code()
    {
        $id = self::getParam('id');
        $code = self::getParam('code');

        $stock = Stock::find($id);
        if (is_null($stock)) {
            return self::jsonErrorId(101, 'Не найдена котировка');
        }
        $stock->update(['finam_code' => $code]);

        return self::jsonSuccess();
    }

    /**
     * Выводит график для админа
     *
     * @param int $id идентификатор курса
     *
     * @return string
     */
    public function actionGraph2($id)
    {
        // Закомментированное это старые графики
        $item = \app\models\Stock::find($id);
        // $start = (new \DateTime())->sub(new \DateInterval('P30D'));
        $start = (new \DateTime())->sub(new \DateInterval('P50Y'))->format('Y-m-d');
        $isPaid = Yii::$app->user->identity->isPaid($id);
        if ($isPaid) {
            // $end = (new \DateTime())->add(new \DateInterval('P30D'));
            $end = (new \DateTime())->add(new \DateInterval('P3M'))->format('Y-m-d');
        } else {
            // $end = (new \DateTime());
            $end = (new \DateTime())->format('Y-m-d');
        }
        $defaultParams = [
            'start' => $start,
            'end'   => $end,
        ];

        // график с продажами
        {
             $lineArrayKurs2 = \app\service\GraphUnion2::convert([
                'lines' => [
                     \app\models\StockKurs::query(['stock_id' => $item->getId()])
                    ->select([
                        'date',
                        'kurs',
                    ])
                    ->andWhere(['between', 'date', $start, $end])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
                ]
            ]);
            // Старый график, не выводится, нужны лишь данные для подсчетов
            // $params = ArrayHelper::merge($defaultParams, [
            //     'rows'  => [
            //         \app\models\StockKurs::query(['stock_id' => $id])
            //             // ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
            //             ->andWhere(['between', 'date', $start, $end])
            //             ->all(),
            //     ],
            // ]);
            // $lineArrayKurs = \app\service\GraphExporter::convert($params);
        }

        // график с прогнозом (красная линия)
        {
            $lineArrayRed2 = \app\service\GraphUnion2::convert([
                'lines' => [
                    \app\models\StockPrognosisRed::query(['stock_id' => $item->getId()])
                        ->select([
                            'date',
                            'delta as red',
                        ])
                        ->andWhere(['between', 'date', $start, $end])
                        ->orderBy(['date' => SORT_ASC])
                        ->all(),
                ]
            ]);
            // Старый график, не выводится, нужны лишь данные для подсчетов
            // $params = ArrayHelper::merge($defaultParams, [
            //     'rows'  => [
            //         \app\models\StockPrognosisRed::query(['stock_id' => $id])
            //             // ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
            //             ->andWhere(['between', 'date', $start, $end])
            //             ->select([
            //                 'date',
            //                 'delta as kurs',
            //             ])
            //             ->all(),
            //     ],
            // ]);
            // $lineArrayRed = \app\service\GraphExporter::convert($params);
        }

        // график с прогнозом (синяя линия)
        {
             $lineArrayBlue2 = \app\service\GraphUnion2::convert([
                'lines' => [
                    \app\models\StockPrognosisBlue::query(['stock_id' => $item->getId()])
                        ->select([
                            'date',
                            'delta as blue',
                        ])
                        ->andWhere(['between', 'date', $start, $end])
                        ->orderBy(['date' => SORT_ASC])
                        ->all(),
                ]
            ]);
            // Старый график, не выводится, нужны лишь данные для подсчетов
            // $params = ArrayHelper::merge($defaultParams, [
            //     'rows'  => [
            //         \app\models\StockPrognosisBlue::query(['stock_id' => $id])
            //             // ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
            //             ->andWhere(['between', 'date', $start, $end])
            //             ->select([
            //                 'date',
            //                 'delta as kurs',
            //             ])
            //             ->all(),
            //     ],
            // ]);
            // $lineArrayBlue = \app\service\GraphExporter::convert($params);
        }

        // union
        {
            $lineArrayUnion = \app\service\GraphUnion2::convert([
            'lines' => [
                \app\models\StockPrognosisRed::query(['stock_id' => $item->getId()])
                    ->select([
                        'date',
                        'delta as red',
                    ])
                    ->andWhere(['between', 'date', $start, $end])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
                \app\models\StockPrognosisBlue::query(['stock_id' => $item->getId()])
                    ->select([
                        'date',
                        'delta as blue',
                    ])
                    ->andWhere(['between', 'date', $start, $end])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
            ]
        ]);
            // Старый график, не выводится, нужны лишь данные для подсчетов
            // $lineArrayUnion = \app\service\GraphUnion::convert([
            //     'x' => $lineArrayRed['x'],
            //     'y' => [
            //         $lineArrayRed['y'][0],
            //         $lineArrayBlue['y'][0],
            //     ]
            // ]);
        }

        // union2
        {
             $lineArrayUnion2 = \app\service\GraphUnion2::convert([
            'lines' => [
                \app\models\StockKurs::query(['stock_id' => $item->getId()])
                    ->select([
                        'date',
                        'kurs',
                    ])
                    ->andWhere(['between', 'date', $start, $end])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
                \app\models\StockPrognosisRed::query(['stock_id' => $item->getId()])
                    ->select([
                        'date',
                        'delta as red',
                    ])
                    ->andWhere(['between', 'date', $start, $end])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
                \app\models\StockPrognosisBlue::query(['stock_id' => $item->getId()])
                    ->select([
                        'date',
                        'delta as blue',
                    ])
                    ->andWhere(['between', 'date', $start, $end])
                    ->orderBy(['date' => SORT_ASC])
                    ->all(),
            ]
        ]);
            // Старый график, не выводится, нужны лишь данные для подсчетов
            // $lineArrayUnion2 = \app\service\GraphUnion::convert([
            //     'x' => $lineArrayRed['x'],
            //     'y' => [
            //         $lineArrayRed['y'][0],
            //         $lineArrayBlue['y'][0],
            //         $lineArrayKurs['y'][0],
            //     ]
            // ]);
        }

        return $this->render([
            'item'           => $item,
            'lineArrayKurs'  => $lineArrayKurs2,
            'lineArrayRed'   => $lineArrayRed2,
            'lineArrayBlue'  => $lineArrayBlue2,
            'lineArrayUnion' => $lineArrayUnion,
            'lineArrayUnion2' => $lineArrayUnion2,
            'isPaid'         => $isPaid,
        ]);
    }

    /**
     * Устанавливает флаг is_enabled для котировки
     * AJAX
     *
     * REQUEST:
     * - id - int - идентификатор котировки
     * - is_enabled - int - 0 - нет, 1 - да
     */
    public function actionToggle()
    {
        $id = self::getParam('id');
        $is_enabled = self::getParam('is_enabled');

        $stock = Stock::find($id);
        if (is_null($stock)) {
            return self::jsonErrorId(101, 'Не найдена котировка');
        }
        $stock->update(['is_enabled' => $is_enabled]);

        return self::jsonSuccess();
    }

    public function actionAdd()
    {
        $model = new \app\models\Form\Stock();
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
     * Импортирует прогнозы
     */
    public function actionImport($id)
    {
        $model = new \app\models\Form\StockPrognosisImport();
        if ($model->load(Yii::$app->request->post()) && $model->import($id)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * Показывает прогнозы
     *
     * @param int $id иднтификатор котировки
     * @param int $color цвет прогноза 'red', 'blue'
     *
     * @return string
     * @throws \cs\web\Exception
     */
    public function actionShow($id, $color)
    {
        switch($color) {
            case 'red':
                $query = StockPrognosisRed::query();
                break;
            case 'blue':
                $query = StockPrognosisBlue::query();
                break;
            default:
                throw new Exception('Не верный запрос');
        }
        $query->andWhere(['stock_id' => $id]);

        return $this->render([
            'query' => $query,
        ]);
    }

    /**
     * Импортирует курсы с finam
     *
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionImport_kurs($id)
    {
        $model = new \app\models\Form\StockKursImport();
        if ($model->load(Yii::$app->request->post()) && $model->import($id)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
                'item' => Stock::find($id),
            ]);
        }
    }

    public function actionKurs_add($id)
    {
        $model = new \app\models\Form\StockKursAdd();
        if ($model->load(Yii::$app->request->post()) && $model->insert()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model'    => $model,
                'stock_id' => $id,
            ]);
        }
    }

    public function actionGraph($id)
    {
        return $this->render([
            'item' => \app\models\Stock::find($id),
        ]);
    }

    public function actionKurs_edit($id)
    {
        return $this->render([
            'items' => \app\models\StockKurs::query(['stock_id' => $id])->orderBy(['date' => SORT_DESC])->all(),
            'item'  => \app\models\Stock::find($id),
        ]);
    }

    /**
     * Обновляет по AJAX знчение
     *
     * @return \yii\web\Response
     */
    public function actionKurs_update()
    {
        $id = self::getParam('id');
        $value = self::getParam('value');
        $type = self::getParam('type');
        $item = StockKurs::find($id);
        switch ($type) {
            case 'date':
                $item->update(['date' => $value]);
                break;
            case 'kurs':
                $item->update(['kurs' => $value]);
                break;
        }

        return $this->jsonSuccess();
    }

    /**
     * Обновляет по AJAX знчение
     *
     * @return \yii\web\Response
     */
    public function actionPrognosis_update()
    {
        $id = self::getParam('id');
        $value = self::getParam('value');
        $type = self::getParam('type');
        $item = StockPrognosis::find($id);
        switch ($type) {
            case 'date':
                $item->update(['date' => $value]);
                break;
            case 'kurs':
                $item->update(['kurs' => $value]);
                break;
        }

        return $this->jsonSuccess();
    }

    public function actionPrognosis_edit($id)
    {
        return $this->render([
            'items' => \app\models\StockPrognosis::query(['stock_id' => $id])->orderBy(['date' => SORT_DESC])->all(),
            'item'  => \app\models\Stock::find($id),
        ]);
    }

    public function actionPrognosis_add($id)
    {
        $model = new \app\models\Form\StockPrognosisAdd();
        if ($model->load(Yii::$app->request->post()) && $model->insert()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model'    => $model,
                'stock_id' => $id,
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = \app\models\Form\Stock::find($id);
        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        \app\models\Form\Stock::find($id)->delete();

        return self::jsonSuccess();
    }

    /**
     * Выводит форму и обрабатывает "Удаление прогноза"
     *
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionPrognosis_delete_red($id)
    {
        $model = new \app\models\Form\StockPrognosisRedDelete();
        if ($model->load(Yii::$app->request->post()) && $model->remove($id)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * Выводит форму и обрабатывает "Удаление прогноза"
     *
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionPrognosis_delete_blue($id)
    {
        $model = new \app\models\Form\StockPrognosisBlueDelete();
        if ($model->load(Yii::$app->request->post()) && $model->remove($id)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * Выводит форму и обрабатывает "Удаление курса"
     *
     * @param int $id идентификатор акции
     *
     * @return \yii\web\Response
     */
    public function actionKurs_delete($id)
    {
        $model = new \app\models\Form\StockKursDelete();
        if ($model->load(Yii::$app->request->post()) && $model->remove($id)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }
}
