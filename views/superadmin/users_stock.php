<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $users array */

/**
 * Месяца отображаются с текущего даже если сегодня 31
 * отображаются в яцейках строки, всего 9 месяцев для отображения и еще она ячейка которая показывает что еще дальше
 * есть количество месяцев = 9 (9 * 100 px)
 */

/**
 * Расчитывает данные для прогресс бара
 *
 * @param string $date 'yyyy-mm-dd'
 *
 * @return array
 * [
 *    'month'  => int (до 9), если 1 - то это текущий месяц
 *    'isMore' => bool
 * ]
 */
function getDatePeriod($date)
{
    $d = new DateTime();
    $y = $d->format('Y');
    $n = $d->format('n');
    $c = 0;
    for ($i = 1; $i <= 9; $i++) {
        if ($i == 1) {
            // создаю дату начала следующего месяца
            $n++;
            if ($n > 12) {
                $n = 1;
                $y++;
            }
            $m = $n;
            if ($m < 10) {
                $m = '0' . $m;
            }
            $new = $y . '-' . $m . '-01';
            if ($new <= $date) {
                $c++;
            } else {
                return [
                    'month'  => $c,
                    'isMore' => false,
                ];
            }
        } else {
            $n++;
            if ($n > 12) {
                $n = 1;
                $y++;
            }
            $m = $n;
            if ($m < 10) {
                $m = '0' . $m;
            }
            $new = $y . '-' . $m . '-01';
            if ($new <= $date) {
                $c++;
            } else {
                return [
                    'month'  => $c,
                    'isMore' => false,
                ];
            }
        }
    }

    return [
        'month'  => $c,
        'isMore' => true,
    ];
}

/**
 * Возвращает список месяцев
 * девять штук
 *
 * @return array ['янв', ... ]
 */
function getMonthList()
{
    $list = [
        'янв',
        'фев',
        'мар',
        'апр',
        'май',
        'июн',
        'июл',
        'авг',
        'сен',
        'окт',
        'ноя',
        'дек',
    ];
    $date = new DateTime();
    $n = $date->format('n');
    $ret = [];
    for ($i = 1; $i <= 9; $i++) {
        $ret[] = $list[ $n - 1 ];
        $n++;
        if ($n > 12) $n = 1;
    }

    return $ret;
}

$this->title = 'Текущие заказы пользователей';
?>

<h1 class="page-header"><?= $this->title ?></h1>

<table class="table table-hover" style="width:100%;">
    <thead>
    <tr>
        <th width="150">Пользователь</th>
        <th>что</th>
        <th>до</th>
        <?php foreach (getMonthList() as $i) { ?>
            <th width="100"><?= $i ?></th>
        <?php } ?>
        <th width="100"></th>
    </tr>
    </thead>
    <?php
    foreach ($users as $item) {
        $с = 1;

        ?>
        <?php
        foreach ($item['stockList'] as $stock) {
            ?>
            <tr>
                <?php if ($с == 1) { ?>
                    <td rowspan="<?= count($item['stockList']) ?>">
                        <?php if ( ($item['avatar'] . '') != '' ) { ?>
                            <img src="<?= $item['avatar'] ?>" width="100" class="thumbnail" style="margin-bottom: 0px;"><br/>
                        <?php } ?>
                        <?= $item['name_first'] ?><br/>
                        <?= $item['name_last'] ?><br/>
                        <span style="font-family: 'courier new';font-size: 80%"><?= $item['email'] ?></span>
                    </td>
                    <?php
                    $с++;
                } ?>
                <td>
                    <a href="<?= Url::to(['cabinet/stock_item3', 'id' => $stock['id']]) ?>"><?= $stock['name'] ?>
                </td>
                <td>
                    <?= Yii::$app->formatter->asDate($stock['date_finish']) ?>
                </td>
                <td colspan="9">
                    <?php $d = getDatePeriod($stock['date_finish']);
                    $percent = (int)(($d['month'] / 9) * 100);
                    ?>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0"
                             aria-valuemax="100" style="width: <?= $percent ?>%;">
                            <span class="sr-only">60% Complete</span>
                        </div>
                    </div>
                </td>
                <td>
                    <?php if ($d['isMore']) { ?>
                        <span class="label label-primary">еще</span>
                    <?php } ?>
                </td>
            </tr>
        <?php
        }?>
    <?php
    }?>
</table>

