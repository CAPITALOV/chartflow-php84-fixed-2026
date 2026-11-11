<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $log array */

$this->title = 'Лог';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <div class="site-login">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

        <?= \yii\grid\GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $log,
                'pagination' => [
                    'pageSize' => 50
                ]
            ]),
            'tableOptions' => [
                'class' => 'table table-hover table-striped table-bordered',
            ],
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],
                'date:date:Дата',
                'time:text:Время',
                'ip',
                'user_id',
                'code',
                'type',
                'category',
                [
                    'header'  => 'Сообщение',
                    'content' => function ($item) {
                        return '<pre>' . Html::encode($item['message']) . '</pre>';
                    }
                ],
            ],
        ]) ?>
    </div>
</div>