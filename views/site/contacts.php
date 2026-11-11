<?php

$this->title = 'KAPITALOV.COM Global Financial Services Provider from Russia';
?>
<h1 class="page-header">О продукте</h1>

<div class="row">
    <div class="col-lg-8">
        <p>
            +7 (925) 219-4464<br>
            +7 (968) 723-1023<br>
            info@kapitalov.com
        </p>
        <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Ab01e07770c07f7a15bb4beb9fd1fc925ee1fa91505a29736a9763fdc31da0732&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
    </div>
    <div class="col-lg-4">
        <img class="img-responsive img-thumbnail" src="/images/index/cabinet.gif" alt="">
    </div>
</div>

<div class="row" style="
    margin-top: 30px;
">
    <div class="col-lg-4 col-lg-offset-4">
        <?php if (Yii::$app->user->isGuest) { ?>
            <a class="btn btn-primary btn-lg" href="<?= \yii\helpers\Url::to(['auth/login']) ?>" style="width: 100%;background-color: #aa719f;border: none;border-radius: 24px;">Войти в систему</a>
        <?php } else { ?>
            <a class="btn btn-primary btn-lg" href="<?= \yii\helpers\Url::to(['cabinet/index']) ?>" style="width: 100%;background-color: #aa719f;border: none;border-radius: 24px;">Войти в систему</a>
        <?php } ?>
    </div>
</div>