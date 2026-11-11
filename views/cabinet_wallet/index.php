<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $end integer до какого оплачен кабинет */

$this->title = 'Счет';
?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if (is_null($end)) { ?>
    <p>Демо режим</p>
<?php } else { ?>
    <p>Оплачено до: <?= date('d.m.Y', $end) ?></p>
    <p>Вы можете еще пользоваться дней: <?= (int)(($end - time()) / (60 * 60 * 24)) ?></p>
<?php } ?>
<a href="<?= \yii\helpers\Url::to(['cabinet_wallet/add']) ?>" class="btn btn-default">Оплатить</a>
