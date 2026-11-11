<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $items array cap_stock */
/** @var $red   array */
/** @var $blue  array */
/** @var $kurs  array */
/** @var $this  yii\web\View */

$this->title = 'Котировки';
$model = new \app\models\Form\StockAll();
$this->registerJs(<<<JS
$('input[name=\"StockAll[is_enabled]\"]').change(function() {
    var t = $(this);
    var id = t.data('id');
    ajaxJson({
        url: 'stock/toggle',
        data:{
            id: id,
            is_enabled: t.is(':checked')? 1 : 0
        },
        success: function(ret) {
            alert('ok');
        }
    });
});
$('.tooltipButton').tooltip();
JS
);

$searchModel = new \app\models\Form\StockSearch();
$dataProvider = $searchModel->search(Yii::$app->request->get());
?>

<h1 class="page-header"><?= $this->title ?></h1>
<style>
    .tableMy .date {
        font-size: 80%;
    }
</style>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'tableOptions' => [
        'class' => 'table tableMy table-striped table-hover',
    ],
    'columns'      => [
        [
            'header'  => 'Действует?',
            'content' => function ($item) {
                $model = new \app\models\Form\StockAll();
                if (\yii\helpers\ArrayHelper::getValue($item, 'is_enabled', 0) == 1) {
                    $model->is_enabled = true;
                } else {
                    $model->is_enabled = false;
                }

                return \cs\Widget\CheckBox2\CheckBox::widget([
                    'model'     => $model,
                    'attribute' => 'is_enabled',
                    'options'   => ['data' => [
                        'id' => $item['id']
                    ]]]);
            }
        ],
        'name:text:Наименование',
        [
            'header'  => Html::tag('span', null, [
                'class' => 'glyphicon glyphicon-edit',
                'title' => 'Редактировать'
            ]),
            'content' => function ($item) {
                return Html::a(Html::tag('span', null, [
                    'class' => 'glyphicon glyphicon-edit',
                ]), [
                    'superadmin_stock/edit',
                    'id' => $item['id'],
                ], [
                    'class' => 'btn btn-default btn-xs tooltipButton',
                    'title' => 'Редактировать',
                ]);
            }
        ],
        [
            'header'  => Html::tag('span', null, [
                'class' => 'glyphicon glyphicon-signal',
                'title' => 'График'
            ]),
            'content' => function ($item) {
                return Html::a(Html::tag('span', null, [
                    'class' => 'glyphicon glyphicon-signal',
                ]), [
                    'superadmin_stock/graph2',
                    'id' => $item['id'],
                ], [
                    'class' => 'btn btn-default btn-xs tooltipButton',
                    'title' => 'График',
                ]);
            }
        ],
        [
            'header'         => 'Красный',
            'contentOptions' => [
                'class' => 'date'
            ],
            'content'        => function ($item) {
                $row = \app\models\StockPrognosisRed::query()
                    ->select([
                        'MIN(`date`) as min',
                        'MAX(`date`) as max',
                    ])
                    ->groupBy('stock_id')
                    ->andWhere(['stock_id' => $item['id']])
                    ->one();
                $lines = [];
                if ($row['min']) {
                    $min = (new DateTime($row['min']))->format('d.m.Y');
                    $max = (new DateTime($row['max']))->format('d.m.Y');
                    $lines[] = Html::a("{$min} ... {$max}", ['superadmin_stock/show', 'id' => $item['id'], 'color' => 'red']);
                    $lines[] = Html::a(Html::tag('span', null, [
                        'class' => 'glyphicon glyphicon-remove-sign',
                    ]), [
                        'superadmin_stock/prognosis_delete_red',
                        'id' => $item['id']
                    ], [
                        'class'       => "btn btn-default btn-xs tooltipButton",
                        'onmouseover' => "$(this).removeClass('btn-default').addClass('btn-danger')",
                        'onmouseout'  => "$(this).removeClass('btn-danger').addClass('btn-default')",
                        'title'       => 'Удалить',
                        'style'       => 'margin-left: 10px;'
                    ]);
                }

                return join("\r", $lines);
            }
        ],

        [
            'header'         => 'Синий',
            'contentOptions' => [
                'class' => 'date'
            ],
            'content'        => function ($item) {
                $row = \app\models\StockPrognosisBlue::query()
                    ->select([
                        'MIN(`date`) as min',
                        'MAX(`date`) as max',
                    ])
                    ->andWhere(['stock_id' => $item['id']])
                    ->one();
                $lines = [];
                if ($row['min']) {
                    $min = (new DateTime($row['min']))->format('d.m.Y');
                    $max = (new DateTime($row['max']))->format('d.m.Y');
                    $lines[] = Html::a("{$min} ... {$max}", ['superadmin_stock/show', 'id' => $item['id'], 'color' => 'blue']);
                    $lines[] = Html::a(Html::tag('span', null, [
                        'class' => 'glyphicon glyphicon-remove-sign',
                    ]), [
                        'superadmin_stock/prognosis_delete_blue',
                        'id' => $item['id']
                    ], [
                        'class'       => "btn btn-default btn-xs tooltipButton",
                        'onmouseover' => "$(this).removeClass('btn-default').addClass('btn-danger')",
                        'onmouseout'  => "$(this).removeClass('btn-danger').addClass('btn-default')",
                        'title'       => 'Удалить',
                        'style'       => 'margin-left: 10px;'
                    ]);
                }

                return join("\r", $lines);
            }
        ],
        [
            'header'  => Html::tag('span', null, [
                'class' => 'glyphicon glyphicon-log-in',
                'title' => 'Имортировать'
            ]),
            'content' => function ($item) {
                return Html::a(Html::tag('span', null, [
                    'class' => 'glyphicon glyphicon-log-in',
                ]), [
                    'superadmin_stock/import',
                    'id' => $item['id'],
                ], [
                    'class' => 'btn btn-default btn-xs tooltipButton',
                    'title' => 'Имортировать',
                ]);
            }
        ],
        [
            'header'         => 'Курс',
            'contentOptions' => [
                'class' => 'date'
            ],
            'content'        => function ($item) {
                $row = \app\models\StockKurs::query()
                    ->select([
                        'MIN(`date`) as min',
                        'MAX(`date`) as max',
                    ])
                    ->groupBy('stock_id')
                    ->andWhere(['stock_id' => $item['id']])
                    ->one();
                $lines = [];
                if ($row['min']) {
                    $min = (new DateTime($row['min']))->format('d.m.Y');
                    $max = (new DateTime($row['max']))->format('d.m.Y');
                    $lines[] = "{$min} ... {$max}";
                    $lines[] = Html::a(Html::tag('span', null, [
                        'class' => 'glyphicon glyphicon-remove-sign',
                    ]), [
                        'superadmin_stock/kurs_delete',
                        'id' => $item['id']
                    ], [
                        'class'       => "btn btn-default btn-xs tooltipButton",
                        'onmouseover' => "$(this).removeClass('btn-default').addClass('btn-danger')",
                        'onmouseout'  => "$(this).removeClass('btn-danger').addClass('btn-default')",
                        'title'       => 'Удалить',
                        'style'       => 'margin-left: 10px;'
                    ]);
                }

                return join("\r", $lines);
            }
        ],

        [
            'header'  => Html::tag('span', null, [
                'class' => 'glyphicon glyphicon-log-in',
                'title' => 'Имортировать'
            ]),
            'content' => function ($item) {
                if (\yii\helpers\ArrayHelper::getValue($item, 'finam_code', '') == '') {
                    return '';
                }

                return Html::a(Html::tag('span', null, [
                    'class' => 'glyphicon glyphicon-log-in',
                ]), [
                    'superadmin_stock/import_kurs',
                    'id' => $item['id'],
                ], [
                    'class' => 'btn btn-success btn-xs tooltipButton',
                    'title' => 'Имортировать',
                ]);
            }
        ],
        [
            'header'         => 'Code',
            'content'        => function ($item) {
                if (\yii\helpers\ArrayHelper::getValue($item, 'finam_code', '') == '') {
                    if (\yii\helpers\ArrayHelper::getValue($item, 'finam_code', '') == '') {
                        $u = Html::tag('div',
                            Html::input('text', null, null, [
                                'class' => 'form-control',
                                'size'  => '5',
                            ]) .
                            Html::tag('span',
                                Html::button('о', [
                                    'class'   => 'btn btn-default buttonUpdateCode',
                                    'type'    => 'button',
                                    'data-id' => $item['id'],
                                    'title'   => 'Обновить',
                                ])
                                , [
                                    'class' => 'input-group-btn',
                                ])
                            , [
                                'class' => 'input-group',
                                'style' => 'width:100px;',
                            ]);

                        return $u;
                    }
                }
                return $item['finam_code'];
            }
        ],
    ],
])
?>
<?php
$url = Url::to(['superadmin_stock/update_code']);
$this->registerJs(<<<JS
$('.buttonUpdateCode').tooltip();
$('.buttonUpdateCode').click(function() {
    var b = $(this);
    ajaxJson({
        url: '{$url}',
        data: {
            id: b.data('id'),
            code: b.parent().parent().find('input').val()
        },
        success: function(ret) {
            var v = b.parent().parent().parent();
            b.parent().parent().remove();
            v.append(
                $('<a>', {
                    class: 'btn btn-success btn-xs tooltipButton',
                    href: '/stock/' + b.data('id') + '/importKurs',
                    title: 'Имортировать'
                })
                .append(
                    $('<span>', {
                        class: 'glyphicon glyphicon-log-in'
                    })
                )
                .tooltip()
            );
        }
    })
})
JS
);
?>

<a href="<?= Url::to(['superadmin_stock/add']) ?>" class="btn btn-default">Добавить</a>
