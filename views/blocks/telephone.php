<?php

$t = \Yii::$app->params['tel'];

$telephone2 = substr($t,0,2) . ' (' . substr($t,2,3) . ') ' . substr($t,5,3) . '-' . substr($t,8,4);

?>

<a href="tel:<?= $t ?>">
    <?= $telephone2 ?>
</a>