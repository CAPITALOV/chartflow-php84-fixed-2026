<?php


use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Stock;

/* @var $this yii\web\View */

$this->title = 'Расчитываемые котировки';
\cs\assets\Confirm\Asset::register($this);

$url = Url::to(['superadmin/stock_calc_activate']);
$this->registerJs(<<<JS
    $('.buttonActivate').click(function(){
        if (confirm('Вы уверены?')) {
            var button = $(this);
            ajaxJson({
                url: '{$url}',
                data: {
                    id: button.data('id')
                },
                success: function(ret) {
                    button.parent().parent().remove();
                    showInfo('Успешно');
                }
            })
        }
    });
    $('.dateTooltip').tooltip();
JS
);

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query'      => \app\models\Stock::query(['cap_stock.status' => Stock::STATUS_IN_PROGRESS])
            ->innerJoin('cap_requests', 'cap_requests.stock_id = cap_stock.id')
            ->groupBy('cap_stock.id')
            ->orderBy(['min(cap_requests.datetime)' => SORT_DESC])
            ->select([
                'min(cap_requests.datetime) as datetime_min',
                'max(cap_requests.datetime) as datetime_max',
                'cap_stock.*',
                'count(cap_requests.id) as request_list_count',
            ])
        ,
        'pagination' => [
            'pageSize' => 20,
        ],

    ]),
    'tableOptions' => [
        'class' => 'table table-striped table-hover',
    ],
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn', // <-- here
            // you may configure additional properties here
        ],
        'name',
        [
            'header'  => 'logo',
            'content' => function ($model, $key, $index, $column) {
                if (\yii\helpers\ArrayHelper::getValue($model, 'logo', '') == '') return '';

                return Html::img($model['logo'],  [
                    'class' => 'thumbnail',
                    'width' => '50',
                ]);
            }
        ],
        'description',
        'finam_em',
        'finam_market',
        'finam_code',
        'request_list_count',
        [
            'header'  => 'Мин время заказа',
            'content' => function ($model, $key, $index, $column) {
                return Yii::$app->formatter->asDatetime($model['datetime_min']);
            }
        ],
        [
            'header'  => 'Макс время заказа',
            'content' => function ($model, $key, $index, $column) {
                return Yii::$app->formatter->asDatetime($model['datetime_max']);
            }
        ],
        [
            'header'  => 'Публикация прогноза',
            'content' => function ($model, $key, $index, $column) {
                return Html::button('Публикация прогноза', [
                    'class' => 'btn btn-primary buttonActivate',
                    'data' => [
                        'id' => $model['id']
                    ],
                ]);
            }
        ],

    ]

]) ?>
