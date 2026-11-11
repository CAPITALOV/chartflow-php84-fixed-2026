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

<nav class="navbar navbar-default navbar-fixed-top" role="navigation"
     style="border:0px; background-color:#ffffff; margin-bottom:-1px;">
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
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a class="navbar-brand" href="<?= Url::to(['superadmin/index'])?>" style="margin-top:-10px;">
                        <img
                            src="/images/kapitalovlogo1-2.png"
                            class="siteLayoutLogo" style="height:40px; margin:0px"></a>
                </li>
                <?php if (!Yii::$app->user->isGuest) { ?>
                    <li class="dropdown<?php if (\cs\services\Str::isContain(Yii::$app->controller->id, 'superadmin')) {
                        echo(' active');
                    } ?>">
                        <a
                            href="#"
                            class="dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"
                            role="button"
                            >
                            Админ
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?= Url::to(['superadmin_requests/index']) ?>">Заявки на оплату услуги</a></li>
                            <li><a href="<?= Url::to(['superadmin/referal']) ?>">Регистрация по реферальным ссылкам</a></li>
                            <li><a href="<?= Url::to(['superadmin_stock/index']) ?>">Котировки</a></li>
                            <li><a href="<?= Url::to(['superadmin/users_stock']) ?>">Текущие заказы пользователей</a></li>
                            <li><a href="<?= Url::to(['superadmin/users']) ?>">Пользователи</a></li>
                            <li><a href="<?= Url::to(['superadmin/stock_calc']) ?>">Расчитываемые котировки</a></li>
                            <li><a href="<?= Url::to(['superadmin_news/index']) ?>">Новости</a></li>
                        </ul>
                    </li>
                    <?php if (Yii::$app->user->identity->isRole(\app\models\UserRole::ROLE_DESIGNER)) { ?>
                        <li class="dropdown<?php if (Yii::$app->controller->id == 'designer') {
                            echo(' active');
                        } ?>">
                            <a
                                href="#"
                                class="dropdown-toggle"
                                data-toggle="dropdown"
                                aria-expanded="false"
                                role="button"
                                >
                                Дизайн
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?= Url::to(['designer/landing']) ?>">Главная страница</a></li>

                            </ul>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>


            <?php if (!Yii::$app->user->isGuest) { ?>
                <ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
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
                            <li><a href="<?= Url::to(['auth/logout']) ?>" data-method="post"><i
                                        class="glyphicon glyphicon-off" style="padding-right: 5px;"></i>Выйти</a></li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
    <hr class="clearfix" style="color:#ededed; background-color:#ededed; margin-top: 0px;margin-bottom: 0px;">
</nav>
