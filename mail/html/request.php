<?php
/**
 * @var $stock \app\models\Stock акция
 * @var $user \app\models\User пользователь
 * @var $request \app\models\Request запрос на получение услуги
 */

?>

<p>Клиент запросил получение услуги</p>

<p>Клиент: <?= $user->getEmail() ?><br>
<?= $user->getField('name_first') ?><br>
<?= $user->getField('name_last') ?></p>

<p>Акция: <?= $stock->getName() ?></p>
<p>Количество месяцев: <?= $request->getField('month') ?></p>
<p><a href="<?= \yii\helpers\Url::to(['superadmin_requests/activate', 'hash' => $request->getField('hash') ]) ?>" style="
            text-decoration: none;
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
             display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
">Активировать</a></p>
