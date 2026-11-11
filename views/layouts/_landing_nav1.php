<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var  $this \yii\web\View */

?>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" style="padding: 5px 10px 5px 10px;"><img
                    src="<?= Yii::$app->getAssetManager()->getBundle('app\assets\LayoutSite\Asset')->baseUrl ?>/images/capitalovlogo2.png"
                    class="siteLayoutLogo"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?= Url::to(['site/about']) ?>">О продукте</a>
                </li>
                <li>
                    <a href="<?= Url::to(['site/price']) ?>">Тарифы</a>
                </li>

                <?php if (Yii::$app->user->isGuest) { ?>
                    <li>
                        <a href="<?= Url::to(['auth/registration']) ?>">Регистрация</a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['auth/login']) ?>" class="linkEnter" style="color: #008000">Личный кабинет</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <!-- Split button -->
                        <div class="btn-group" style="margin-top: 9px;" id="loginBarButton">
                            <a href="<?= Url::to(['cabinet/index']) ?>" class="btn btn-default" id="modalLogin"><i
                                    class="glyphicon glyphicon-user" style="padding-right: 5px;"></i>Кабинет</a>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?= Url::to(['cabinet/profile']) ?>"><i class="glyphicon glyphicon-cog"
                                                                                     style="padding-right: 5px;"></i>Мой
                                        профиль</a></li>
                                <li><a href="<?= Url::to(['cabinet/password_change']) ?>"><i
                                            class="glyphicon glyphicon-asterisk" style="padding-right: 5px;"></i>Сменить
                                        пароль</a></li>
                                <li class="divider"></li>
                                <li><a href="<?= Url::to(['money/history']) ?>"><i
                                            class="glyphicon glyphicon-rub" style="padding-right: 5px;"></i>История
                                        платежей</a></li>
                                <li class="divider"></li>
                                <li><a href="<?= Url::to(['auth/logout']) ?>" data-method="post"><i
                                            class="glyphicon glyphicon-off" style="padding-right: 5px;"></i>Выйти</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>