<?php

/** @var \yii\web\View $this */

$this->title = 'Тарифы';

use yii\helpers\Url;
use yii\helpers\ArrayHelper;


$this->registerJs("$('.payImage').tooltip()");

?>

<!-- Page Heading/Breadcrumbs -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= \yii\helpers\Html::encode($this->title) ?></h1>
    </div>
</div>
<!-- /.row -->

<!-- Content Row -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Демонстрационный</h3>
            </div>
            <div class="panel-body">
                <span class="price"><sup>$</sup>0</span>
                <span class="period">до конца месяца</span>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><strong>1</strong> Акция</li>
                <li class="list-group-item"><strong>Нет</strong> поддержки</li>
                <li class="list-group-item"><a href="<?= Url::to(['auth/registration']) ?>" class="btn btn-default"
                                               style="width:100%;">Получить!</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-success text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Базовый <span class="label label-success">Национальный рынок</span></h3>
            </div>
            <div class="panel-body">
                <span class="price"><sup>$</sup>99</span>
                <span class="period">за месяц</span>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><strong>1</strong> Акция</li>
                <li class="list-group-item"><strong>Есть</strong> поддержка</li>
                <li class="list-group-item"><a href="<?= Url::to(['auth/registration']) ?>" class="btn btn-success"
                                               style="width:100%;">Получить!</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-primary text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Индивидуальный</h3>
            </div>
            <div class="panel-body">
                <span class="price"><sup>$</sup>249</span>
                <span class="period">за месяц</span>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><strong>1</strong> Акция</li>
                <li class="list-group-item"><strong>Есть</strong> поддержка</li>
                <li class="list-group-item"><a href="<?= Url::to(['auth/registration']) ?>" class="btn btn-primary"
                                               style="width:100%;">Получить!</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /.row -->
