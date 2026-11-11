<?php


use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $request \app\models\Request */

/* @var  $stock \app\models\Stock */
/* @var  $dateFinish string дата до которого оплачена услуга в формате 'yyyy-mm-dd' */

$this->title = 'Услуга успешно активирована';

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="alert alert-success">
    Услуга успешно активирована
</div>

<p>Акция: <?= $stock->getName() ?></p>
<p>Оплачено до: <?= Yii::$app->formatter->asDate($dateFinish) ?></p>