<?php

use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $items array cap_users_wallet_history.*['user_id'=>id] */

$this->title = 'История платежей';
?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if (count($items) > 0) { ?>
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Описание</th>
    </tr>
    </thead>
    <?php foreach($items as $item) { ?>
        <tr>
            <td>
                <?= Yii::$app->formatter->asDatetime($item['datetime']) ?>
            </td>
            <td>
                <?= $item['description'] ?>
            </td>
        </tr>
    <?php } ?>
</table>
<?php } else { ?>
    <div class="alert alert-success" role="alert">Нет ни одной записи</div>
<?php } ?>
