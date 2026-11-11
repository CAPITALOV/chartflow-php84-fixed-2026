<?php
/**
 * @var $stock    \app\models\Stock   котировка
 * @var $request  \app\models\Request заказ
 * @var $user     \app\models\User    который заказал
 */
?>
<p>Была заказана котировка у которой не расчитан прогноз</p>
<p><?= $stock->getName() ?> (<?= $stock->getId() ?>)</p>