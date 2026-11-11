<?php

/** @var $this \yii\web\View */
/** @var $item \app\models\Stock */

use app\models\Translator as T;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->registerJs(<<<JS
    $('#layoutCabinetLinkBack').tooltip({
        placement: 'right',
        animation: true,
        delay: 300
    });
JS
);
?>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="border:0px; background-color:#ffffff; margin-bottom:-1px;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"><?= T::t('Hide navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left" >
                <li>
                     <a class="navbar-brand" href="/" style="margin-top:-10px;">
                     <img src="/images/kapitalovlogo1-2.png"
                                        class="siteLayoutLogo" style="height:40px; margin:0px"></a>
                </li>
                <li class="dropdown <?php if (Yii::$app->controller->id == 'cabinet') { echo(' active');} ?>">
                    <a href="#"
                        class="dropdown-toggle"
                        data-toggle="dropdown"
                        aria-expanded="false"
                        role="button">
                        Выбор котировки
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to(['cabinet/index']) ?>">Все</a></li>
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Оплаченные</li>
                        <?php foreach(\app\models\Stock::getPaid()->all() as $item) { ?>
                            <li><a href="<?= Url::to(['cabinet/stock_item3', 'id' => $item['id']]) ?>"><?= $item['name'] ?></a></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li role="presentation" class="dropdown-header">Заказать</li>
                        <li><a href="<?= Url::to(['cabinet_wallet/add1']) ?>">Национальный рынок</a></li>
                        <li><a href="<?= Url::to(['cabinet_wallet/add2']) ?>">Зарубежный рынок</a></li>
                    </ul>
                </li>


                <?php if (Yii::$app->requestedRoute == 'cabinet/stock_item3') { ?>
                    <li style="margin-top:11px; display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex;align-items: center; -webkit-align-items: center; ">
                        <?php
                        $item = \app\models\Stock::find($_GET['id']);
                        $userStock = \app\models\UserStock::find(['stock_id' => $item->getId(), 'user_id' => Yii::$app->user->id]);
                        $date = Yii::$app->formatter->asDate($userStock->getField('date_finish'));
                        $logo = $item->getField('logo', '');
                        ?>
                        <?php if ($logo) { ?>
                            <img src="<?= $logo ?>" width="50" style="float:left; margin:0 10px;">
                        <?php } ?>
                        <span class="hidden-sm hidden-md">
                            <div
                                style="display: table-cell; font-weight:bold; float:left;"><?= $item->getField('finam_code') ?>
                                –
                            </div>
                            <div style="float:left; margin-left:5px;"><?= $item->getField('name') ?></div>
                            <img src="/images/payd.png" style="margin-left:10px; height:17px"/>

                            <div style="margin-left:5px; color:#58b724">Оплачено до <?= $date ?></div>
                        </span>
                    </li>
                <?php } else { ?>
                    <li>
                        <?= $this->render('telephone') ?>
                    </li>
                <?php } ?>
            </ul>


            <ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
                <li style="height:50px; margin:0;">
                    <?php if (Url::to(['cabinet_chat/index']) == Url::current()) { } ?><a style="margin-top: -2px;" href="<?= Url::to(['cabinet_chat/index']) ?>"><span class="hidden-sm hidden-md">Связаться с экспертом </span><img src="/images/message.png" style="height:28px; margin:0;"/></a></li>
                <li class="dropdown">
                    <a
                        href="#"
                        class="dropdown-toggle"
                        data-toggle="dropdown"
                        aria-expanded="false"
                        role="button"
                        style="margin-top:-10px;"
                        >
                        <?= Html::img(Yii::$app->user->identity->getAvatar(), [
                            'height' => '40px',
                            'class'  => 'img-circle'
                        ]) ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to(['cabinet/index']) ?>">Личный кабинет</a></li>
                        <li><a href="<?= Url::to(['cabinet/profile']) ?>"><i class="glyphicon glyphicon-cog"
                                                                          style="padding-right: 5px;"></i>Мой
                                профиль</a></li>
                        <li><a href="<?= Url::to(['cabinet/password_change']) ?>"><i
                                    class="glyphicon glyphicon-asterisk" style="padding-right: 5px;"></i>Сменить
                                пароль</a></li>
                        <li><a href="<?= Url::to(['cabinet/change_email']) ?>"><i
                                    class="glyphicon glyphicon-asterisk" style="padding-right: 5px;"></i>Сменить
                                логин/email</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to(['money/history']) ?>"><i
                                    class="glyphicon glyphicon-rub" style="padding-right: 5px;"></i>История платежей</a></li>
                        <li class="divider"></li>
                            <li><a href="<?= Url::to(['auth/logout']) ?>" data-method="post"><i
                                    class="glyphicon glyphicon-off" style="padding-right: 5px;"></i>Выйти</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <hr class="clearfix" style="color:#ededed; background-color:#ededed; margin-top: 0px;margin-bottom: 0px;">
</nav>
