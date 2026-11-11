<?php

/**
 * Класс для действий кабинета
 *
 */

namespace app\controllers;

use app\models\Stock;
use app\models\User;
use app\models\UserStock;
use cs\services\Security;
use cs\services\VarDumper;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

use app\models\Log;
use yii\db\Query;

class CabinetController extends CabinetBaseController
{
    public function actionProfile()
    {
        $model = \app\models\Form\Profile::find(Yii::$app->user->getId());
        if ($model->load(Yii::$app->request->post()) && ($fields = $model->update())) {
            Yii::$app->user->identity->cacheClear();
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model'   => $model,
                'user'    => Yii::$app->user->identity,
                'refLink' => Yii::$app->user->identity->getReferalLink(true),
            ]);
        }
    }

    public function actionChange_email()
    {
        $model = new \app\models\Form\EmailNew();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->action()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * Выдает элементы поиска курсов для строки поиска Autocomplete
     */
    public function actionSearch_stock_autocomplete()
    {
        $term = self::getParam('term');

        return self::jsonSuccess(
            Stock::query(['like', 'name', $term . '%', false])->select('id, name as value')->all()
        );
    }

    public function actionIndex()
    {
        return $this->render([
            'paid'    => Stock::getPaid()
                ->select([
                    'cap_stock.id',
                    'cap_stock.name',
                    'cap_stock.logo',
                    'cap_stock.description',
                    'cap_users_stock_buy.date_finish'
                ])
                ->all(),
        ]);
    }

    /**
     * AJAX
     * Выдает значения для графика курса по заданному диапазону
     *
     * REQUEST:
     * - id        - int    - идентификатор курса
     * - min       - string - дата начала графика 'yyyy-mm-dd'
     * - max       - string - дата окончания графика 'yyyy-mm-dd'
     * - isUseRed  - int    - 1 - на графике будет присутствовать прогноз красный, 0 - не будет присутствовать
     * - isUseBlue - int    - 1 - на графике будет присутствовать прогноз синий, 0 - не будет присутствовать
     * - isUseKurs - int    - 1 - на графике будет присутствовать курс, 0 - не будет присутствовать
     * - y         - int    - какие значения использовать для оси Y (1 => 'Курс', 2 => 'Красный прогноз', 3 => 'Синий прогноз',)
     *
     * @return string json
     *                {
     *                   'red': - object - data которая для графика нужна
     *                   'blue': - object - data которая для графика нужна
     *                   'kurs': - object - data которая для графика нужна
     *                }
     */
    public function actionGraph_ajax()
    {
        $start = self::getParam('min');
        $end = self::getParam('max');
        $id = self::getParam('id');
        $isUseRed = self::getParam('isUseRed', 0);
        $isUseBlue = self::getParam('isUseBlue', 0);
        $isUseKurs = self::getParam('isUseKurs', 0);
        $y = self::getParam('y');

        $colorGreen = [
            'label'                => "Курс",
            'fillColor'            => "rgba(220,220,220,0.2)",
            'strokeColor'          => "rgba(120,255,120,1)",
            'pointColor'           => "rgba(70,255,70,1)",
            'pointStrokeColor'     => "#fff",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220,220,220,1)",
        ];
        $colorRed = [
            'label'                => "Прогноз",
            'fillColor'            => "rgba(220,220,220,0)",
            'strokeColor'          => "rgba(255,120,120,1)",
            'pointColor'           => "rgba(255,70,70,1)",
            'pointStrokeColor'     => "#fff",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220,220,220,1)",
        ];
        $colorBlue = [
            'label'                => "Прогноз",
            'fillColor'            => "rgba(220,220,220,0)",
            'strokeColor'          => "rgba(120,120,255,1)",
            'pointColor'           => "rgba(70,70,255,1)",
            'pointStrokeColor'     => "#fff",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220,220,220,1)",
        ];
        $colorViolet = [
            'label'                => "Прогноз",
            'fillColor'            => "rgba(220,220,220,0)",
            'strokeColor'          => "rgba(120,255,255,1)",
            'pointColor'           => "rgba(70,255,255,1)",
            'pointStrokeColor'     => "#fff",
            'pointHighlightFill'   => "#fff",
            'pointHighlightStroke' => "rgba(220,220,220,1)",
        ];

        $defaultParams = [
            'start'   => $start,
            'end'     => $end,
            'formatX' => 'd.m',
        ];
        $colors = [
            $colorGreen, $colorRed, $colorBlue,
        ];

        // график с продажами
        $lineArrayKurs = \app\service\GraphExporter::convert(ArrayHelper::merge($defaultParams, [
            'rows'  => [
                \app\models\StockKurs::query(['stock_id' => $id])
                    ->andWhere(['between', 'date', $start, $end])
                    ->select(['date', 'kurs'])
                    ->all(),
            ],
        ]));

        // график с прогнозом (красная линия)
        $lineArrayRed = \app\service\GraphExporter::convert(ArrayHelper::merge($defaultParams, [
            'rows'  => [
                \app\models\StockPrognosisRed::query(['stock_id' => $id])
                    ->andWhere(['between', 'date', $start, $end])
                    ->select([
                        'date',
                        'delta as kurs',
                    ])
                    ->all(),
            ],
        ]));

        // график с прогнозом (синяя линия)
        $lineArrayBlue = \app\service\GraphExporter::convert(ArrayHelper::merge($defaultParams, [
            'rows'  => [
                \app\models\StockPrognosisBlue::query(['stock_id' => $id])
                    ->andWhere(['between', 'date', $start, $end])
                    ->select([
                        'date',
                        'delta as kurs',
                    ])
                    ->all(),
            ],
        ]));

        // график с объем сделок
        {
            $params = ArrayHelper::merge($defaultParams, [
                'rows'  => [
                    \app\models\StockKurs::query(['stock_id' => $id])
                        ->andWhere(['between', 'date', $start, $end])
                        ->select([
                            'date',
                            'volume as kurs',
                        ])
                        ->all(),
                ],
            ]);
            $lineArrayVolume = \app\service\GraphExporter::convert($params);
        }

        // Объединение
        {
            if  (
            ($isUseRed == 1 && $isUseBlue == 0 && $isUseKurs == 0)  ||
            ($isUseRed == 0 && $isUseBlue == 1 && $isUseKurs == 0)  ||
            ($isUseRed == 0 && $isUseBlue == 0 && $isUseKurs == 1)
            ) {
                // показывается только один график
                if ($isUseRed == 1) {
                    // показываю красный
                    $lineArray = $lineArrayRed;
                    $colors = [
                        $colorRed,
                    ];
                } else if ($isUseBlue == 1) {
                    // показываю синий
                    $lineArray = $lineArrayBlue;
                    $colors = [
                        $colorBlue,
                    ];
                } else {
                    // показываю курс
                    $lineArray = $lineArrayKurs;
                    $colors = [
                        $colorGreen
                    ];
                }
            } else if (
                ($isUseRed == 0 && $isUseBlue == 1 && $isUseKurs == 1)  ||
                ($isUseRed == 1 && $isUseBlue == 0 && $isUseKurs == 1)  ||
                ($isUseRed == 1 && $isUseBlue == 1 && $isUseKurs == 0)
            ) {
                // показывается два графика
                if ($isUseRed == 0) {
                    switch($y) {
                        case 1: // Курс
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayKurs['y'][0],
                                    $lineArrayBlue['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorGreen, $colorBlue,
                            ];
                            break;
                        case 3: // Синий
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayBlue['y'][0],
                                    $lineArrayKurs['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorBlue, $colorGreen,
                            ];
                            break;
                    }
                } else if ($isUseBlue == 0) {
                    switch($y) {
                        case 1: // Курс
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayKurs['y'][0],
                                    $lineArrayRed['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorGreen, $colorRed,
                            ];
                            break;
                        case 2: // Красный
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayRed['y'][0],
                                    $lineArrayKurs['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorRed, $colorGreen,
                            ];
                            break;
                    }
                } else {
                    switch($y) {
                        case 1: // Курс
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayRed['y'][0],
                                    $lineArrayBlue['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorRed, $colorBlue,
                            ];
                            break;
                        case 2: // Красный
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayRed['y'][0],
                                    $lineArrayBlue['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorRed, $colorBlue,
                            ];
                            break;
                        case 3: // Синий
                            $lineArray = \app\service\GraphUnion::convert([
                                'x' => $lineArrayRed['x'],
                                'y' => [
                                    $lineArrayBlue['y'][0],
                                    $lineArrayRed['y'][0],
                                ]
                            ]);
                            $colors = [
                                $colorBlue, $colorRed,
                            ];
                            break;
                    }
                }
            } else {
                // показывается три графика
                switch($y) {
                    case 1: // Курс
                        $lineArray = \app\service\GraphUnion::convert([
                            'x' => $lineArrayRed['x'],
                            'y' => [
                                $lineArrayKurs['y'][0],
                                $lineArrayRed['y'][0],
                                $lineArrayBlue['y'][0],
                            ]
                        ]);
                        $colors = [
                            $colorGreen, $colorRed, $colorBlue,
                        ];
                        break;
                    case 2: // Красный
                        $lineArray = \app\service\GraphUnion::convert([
                            'x' => $lineArrayRed['x'],
                            'y' => [
                                $lineArrayRed['y'][0],
                                $lineArrayKurs['y'][0],
                                $lineArrayBlue['y'][0],
                            ]
                        ]);
                        $colors = [
                            $colorRed, $colorGreen, $colorBlue,
                        ];
                        break;
                    case 3: // Синий
                        $lineArray = \app\service\GraphUnion::convert([
                            'x' => $lineArrayRed['x'],
                            'y' => [
                                $lineArrayBlue['y'][0],
                                $lineArrayRed['y'][0],
                                $lineArrayKurs['y'][0],
                            ]
                        ]);
                        $colors = [
                            $colorBlue, $colorRed, $colorGreen, $colorViolet,
                        ];
                        break;
                }
            }
        }

        $graph3 = new \cs\Widget\ChartJs\Line([
            'width'     => 800,
            'lineArray' => $lineArray,
            'colors'    => $colors,
        ]);

        return self::jsonSuccess($graph3->getData());
    }

    public function actionStock_list()
    {
        $items = Stock::query()->orderBy(['name' => SORT_ASC])->all();
        $dateFinishList = UserStock::query(['user_id' => \Yii::$app->user->getId()])->select([
            'stock_id',
            'date_finish',
        ])->all();
        for($i=0;$i<count($items);$i++) {
            $item = &$items[$i];
            foreach($dateFinishList as $row) {
                if ($row['stock_id'] == $item['id']) {
                    $item['date_finish'] = $row['date_finish'];
                }
            }
            if (!isset($item['date_finish'])) {
                $item['date_finish'] = null;
            }
        }

        return $this->render([
            'items' => $items,
        ]);
    }

    /**
     * Рисует графики с прошлым и будущим
     *
     * @param int $id идентификатор курса
     *
     * @return string
     */
    public function actionStock_item2($id)
    {
        $item = \app\models\Stock::find($id);
        $start = (new \DateTime())->sub(new \DateInterval('P31D'));
        $end = (new \DateTime())->sub(new \DateInterval('P1D'));
        $isPaid = Yii::$app->user->identity->isPaid($id);
        $defaultParams = [
            'start' => $start,
            'end'   => $end,
        ];

        // график с продажами
        {
            $params = ArrayHelper::merge($defaultParams, [
                'rows'  => [
                    \app\models\StockKurs::query(['stock_id' => $id])
                        ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
                        ->all(),
                ],
            ]);
            $lineArrayKurs = \app\service\GraphExporter::convert($params);
        }

        // график с прогнозом (красная линия)
        {
            $params = ArrayHelper::merge($defaultParams, [
                'rows'  => [
                    \app\models\StockPrognosisRed::query(['stock_id' => $id])
                        ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
                        ->select([
                            'date',
                            'delta as kurs',
                        ])
                        ->all(),
                ],
            ]);
            $lineArrayRed = \app\service\GraphExporter::convert($params);
        }

        // график с прогнозом (синяя линия)
        {
            $params = ArrayHelper::merge($defaultParams, [
                'rows'  => [
                    \app\models\StockPrognosisBlue::query(['stock_id' => $id])
                        ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
                        ->select([
                            'date',
                            'delta as kurs',
                        ])
                        ->all(),
                ],
            ]);
            $lineArrayBlue = \app\service\GraphExporter::convert($params);
        }

        // union
        {
            $lineArrayPast = \app\service\GraphUnion::convert([
                'x' => $lineArrayRed['x'],
                'y' => [
                    $lineArrayKurs['y'][0],
                    $lineArrayRed['y'][0],
                    $lineArrayBlue['y'][0],
                ]
            ]);
        }

        if ($isPaid) {
            $start = (new \DateTime());
            $end = (new \DateTime())->add(new \DateInterval('P30D'));
            $defaultParams = [
                'start' => $start,
                'end'   => $end,
            ];

            // график с прогнозом (красная линия)
            {
                $params = ArrayHelper::merge($defaultParams, [
                    'rows'  => [
                        \app\models\StockPrognosisRed::query(['stock_id' => $id])
                            ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
                            ->select([
                                'date',
                                'delta as kurs',
                            ])
                            ->all(),
                    ],
                ]);
                $lineArrayRed = \app\service\GraphExporter::convert($params);
            }

            // график с прогнозом (синяя линия)
            {
                $params = ArrayHelper::merge($defaultParams, [
                    'rows'  => [
                        \app\models\StockPrognosisBlue::query(['stock_id' => $id])
                            ->andWhere(['between', 'date', $start->format('Y-m-d'), $end->format('Y-m-d')])
                            ->select([
                                'date',
                                'delta as kurs',
                            ])
                            ->all(),
                    ],
                ]);
                $lineArrayBlue = \app\service\GraphExporter::convert($params);
            }

            $lineArrayFuture = \app\service\GraphUnion::convert([
                'x' => $lineArrayRed['x'],
                'y' => [
                    $lineArrayRed['y'][0],
                    $lineArrayBlue['y'][0],
                ]
            ]);
        } else {
            $lineArrayFuture = null;
        }

        return $this->render([
            'item'            => $item,
            'lineArrayPast'   => $lineArrayPast,
            'lineArrayFuture' => $lineArrayFuture,
            'isPaid'          => $isPaid,
        ]);
    }

    /**
     * Рисует графики с прошлым и будущим
     *
     * @param int $id идентификатор курса
     *
     * @return string
     */
    public function actionStock_item3($id)
    {
        $item = \app\models\Stock::find($id);
        $isPaid = Yii::$app->user->identity->isPaid($id);

        // будущее
        $today = new \DateTime();
        $start = $today->format('Y-m-d');
        $end = $today->add(new \DateInterval('P3M'))->format('Y-m-d');
        $lineArrayFuture = \app\service\GraphUnion2::convert([
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

        // Прошлое
        $today = new \DateTime();
        $end_date = new \DateTime('2019-12-31');
        $end = $today->format('Y-m-d');
        $end_obj = $today->diff($end_date);
        $end_D = ($end_obj->y * 365) + ($end_obj->m * 30) + $end_obj->d + 1;
        $start = $today->sub(new \DateInterval('P'.$end_D.'D'))->format('Y-m-d');
        //   Log::batchInsert([
        //     'id',
        //     'level',
        //     'category',
        //     'log_time',
        //     'prefix',
        //     'message',
        // ], array(array(1,0,NULL,11111,333,json_encode($start))));
        $lineArrayPast = \app\service\GraphUnion2::convert([
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

        // свечи
        $today = new \DateTime();
        $end = $today->format('Y-m-d');
        $start = $today->sub(new \DateInterval('P1Y'))->format('Y-m-d');
        $lineArrayCandels = \app\models\StockKurs::query(['stock_id' => $item->getId()])
            ->select([
                'date',
                'open',
                'close',
                'low',
                'high',
            ])
            ->andWhere(['between', 'date', $start, $end])
            ->orderBy(['date' => SORT_ASC])
            ->all();

        $template = 'stock_item3';
        if (\Yii::$app->deviceDetect->isMobile()) {
            $template = 'stock_item3_mobile';
        }

        return $this->render($template, [
            'item'             => $item,
            'isPaid'           => $isPaid,
            'lineArrayFuture'  => $lineArrayFuture,
            'lineArrayPast'    => $lineArrayPast,
            'lineArrayCandels' => $lineArrayCandels,
        ]);
    }

    public function actionPassword_change()
    {
        $model = new \app\models\Form\PasswordNew();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->action(\Yii::$app->user->identity)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }

    /**
     * AJAX
     * Сохраняет base64 в картинку
     *
     * REQUEST:
     * - base64 - string - data:image/png;base64,iVBORw0KGgoAAAA .. и так далее
     */
    public function actionSave_png()
    {
        $img = self::getParam('base64');
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        return Yii::$app->response->sendContentAsFile(base64_decode($img), 'img.png');
    }
}
