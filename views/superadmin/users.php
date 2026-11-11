<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $query \yii\db\Query */
/** @var $this \yii\web\View */

$this->title = 'Пользователи';
$this->registerJs(<<<JS
$('.dateRegistration').tooltip();
JS
);

?>

<h1 class="page-header"><?= $this->title ?></h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query'      => $query->orderBy(['datetime_reg' => SORT_DESC]),
        'pagination' => [
            'pageSize' => 50,
        ],
    ]),
    'tableOptions' => [
        'class' => 'table table-striped table-hover'
    ],
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'header'  => 'аватар',
            'content' => function ($model, $key, $index, $column) {
                if (!is_null($model['avatar'])) {
                    return Html::img($model['avatar'], [
                        'width' => 40,
                        'style' => 'border: 1px solid #888',
                    ]);
                }

                return '';
            },
        ],
        [
            'header'    => 'имя',
            'attribute' => 'name_first',
        ],
        [
            'header'    => 'фамилия',
            'attribute' => 'name_last',
        ],
        'email',
        [
            'header'    => 'телефон',
            'attribute' => 'phone',
        ],
        [
            'header'    => 'регистрация',
            'content' => function ($model, $key, $index, $column) {
                return Html::tag('abbr', \cs\services\DatePeriod::back($model['datetime_reg']), [
                    'title' => Yii::$app->formatter->asDatetime($model['datetime_reg']),
                    'class' => 'dateRegistration',
                ]);
            },
        ],
        [
            'header'    => 'организация',
            'attribute' => 'name_org',
        ],
    ]
]) ?>

