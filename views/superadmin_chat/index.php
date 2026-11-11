<?php


use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $items array
 * [
 *    [
 *        'id'       => int - идентификатор пользователя,
 *        'name'     => str - имя пользователя,
 *        'avatar'   => str - аватар пользователя,
 *        'datetime' => str - время последнего сообщения
 *    ], ...
 * ] */

$this->title = 'Сообщения от пользователей';

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Пользователь</th>
        <th>name_first</th>
        <th>name_last</th>
        <th>email</th>
        <th>Последнее сообщение</th>
    </tr>
    </thead>
    <?php foreach ($items as $item) {
        $url = Url::to(['superadmin_chat/user', 'id' => $item['id']]);
        ?>
        <tr>
            <td>
                <?php if ($item['avatar']) { ?>
                    <a href="<?= $url ?>">
                        <img src="<?= $item['avatar'] ?>" width="30">
                    </a>
                <?php } ?>
            </td>
            <td>
                <a href="<?= $url ?>">
                    <?= $item['name_first'] ?>
                </a>
            </td>
            <td>
                <a href="<?= $url ?>">
                    <?= $item['name_last'] ?>
                </a>
            </td>
            <td>
                <a href="<?= $url ?>">
                    <?= $item['email'] ?>
                </a>
            </td>
            <td>
                <?= Yii::$app->formatter->asDatetime($item['datetime']) ?>
            </td>
        </tr>
    <?php } ?>
</table>