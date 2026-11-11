<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 03.08.2017
 * Time: 0:22
 */
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="page-header col-lg-12">Что мы можем проанализировать</h2>
<table class="table table-striped table-hover" style="width:auto;">
    <thead>
    <tr>
        <th>Биржа</th>
        <th>Котировка</th>
        <th>Цена</th>
        <th>Курс</th>
        <th>Статус</th>
        <th>Посмотреть</th>
    </tr>
    </thead>
    <?php foreach ($items as $market) { ?>
        <?php if (count($market['stockList']) > 0) { ?>
            <tr>
                <td colspan="6">
                    <h2><?= $market['name'] ?></h2>
                </td>
            </tr>
            <?php foreach ($market['stockList'] as $item) { ?>
                <tr>
                    <td>
                        <?= $market['name'] ?>
                    </td>
                    <td>
                        <?= $item['name'] ?>
                    </td>
                    <td>
                        <?= ($item['finam_market'] == 1) ? 99 : 249 ?> уе
                    </td>
                    <td>
                        <?= (ArrayHelper::getValue($item, 'is_kurs', 0) == 1) ? \yii\helpers\Html::tag('span', 'Да', ['class' => 'label label-success']) : '' ?>
                    </td>
                    <td>
                        <?php if ($item['status'] == 0) { ?>
                            <span class="label label-default">Не расчитано</span>
                        <?php } else if ($item['status'] == 1) { ?>
                            <span class="label label-warning">Расчитывается</span>
                        <?php } else if ($item['status'] == 2) { ?>
                            <span class="label label-primary">Готов</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ((ArrayHelper::getValue($item, 'is_kurs', 0) == 1) or ($item['status'] == 2)) { ?>
                            <a href="<?= Url::to(['site/stock', 'id' => $item['id']]) ?>"
                               class="btn btn-primary btn-xs">Посмотеть</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</table>

