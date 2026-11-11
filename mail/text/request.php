<?php
/**
 * @var $stock \app\models\Stock
 * @var $user \app\models\User
 * @var $request \app\models\Request
 */

?>

Клиент запросил получение услуги

Клиент: <?= $user->getEmail() ?>
<?= $user->getField('name_first') ?>
<?= $user->getField('name_last') ?>

Акция: <?= $stock->getName() ?>
Количество месяцев: <?= $request->getField('month') ?>

Для активации пройдите по ссылке: <?= \yii\helpers\Url::to(['superadmin_requests/activate', 'hash' => $request->getField('hash') ]) ?>
