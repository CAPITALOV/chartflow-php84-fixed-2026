<?php

use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Курсы';

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>


<table class="table table-striped" style="width:auto;">
    <thead>
    <tr>
        <th></th>
        <th>Наименование</th>
        <th>Оплачено до</th>
        <th>Оплатить</th>
    </tr>
    </thead>
    <?php
    foreach ($items as $item) {
        ?>
        <tr>
            <td>
                <?php
                    if (!is_null($item['logo'])) {
                        echo Html::a(Html::img($item['logo'], [
                            'width' => 50,
                            'class' => 'thumbnail',
                            'style' => 'margin-bottom: 0px;',
                        ]), [
                            'cabinet/stock_item3',
                            'id' => $item['id']
                        ]);
                    }
                ?>
            </td>
            <td>
                <a href="<?= Url::to([
                    'cabinet/stock_item3',
                    'id' => $item['id']
                ]) ?>">
                    <?= $item['name'] ?>
                </a>
            </td>
            <td>
                <?= (is_null($item['date_finish']))? '' : \Yii::$app->formatter->asDate($item['date_finish']) ?>
            </td>
            <td>
                <a href="<?= Url::to(['cabinet_wallet/add', 'id' => $item['id']]) ?>" class="btn btn-default btn-xs">Оплатить</a>
            </td>
        </tr>

    <?php
    }?>
</table>
