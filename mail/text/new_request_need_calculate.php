<?php
/**
 * @var $stock    \app\models\Stock   котировка
 * @var $request  \app\models\Request заказ
 * @var $user     \app\models\User    который заказал
 */
?>
Была заказана котировка у которой не расчитан прогноз
<?= $stock->getName() ?> (<?= $stock->getId() ?>)