<?php


use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $items

'cap_requests.*',
 * 'cap_users.avatar as cap_users_avatar',
 * 'cap_users.name_first as cap_users_name_first',
 * 'cap_users.name_last as cap_users_name_last',
 * 'cap_users.email as cap_users_email',
 * 'cap_stock.name as cap_stock_name',
 * 'cap_stock.logo as cap_stock_logo',
 */

$this->title = 'Заявки на услуги';
\cs\assets\Confirm\Asset::register($this);

$url = Url::to(['superadmin_requests/activate_ajax']);
$urlDelete = Url::to(['superadmin_requests/delete_ajax']);
$this->registerJs(<<<JS
    $('.buttonActivate').click(function() {
        if (confirm('Вы уверены?')) {
            var button = $(this);
            ajaxJson({
                url: '{$url}',
                data: {
                    id: button.data('id')
                },
                success: function(ret) {
                    button.parent().parent().remove();
                    showInfo('Успешно');
                }
            })
        }
    });
    $('.buttonDelete').click(function() {
        if (confirm('Вы уверены?')) {
            var button = $(this);
            ajaxJson({
                url: '{$urlDelete}',
                data: {
                    id: button.data('id')
                },
                success: function(ret) {
                    button.parent().parent().remove();
                    showInfo('Успешно');
                }
            })
        }
    });
JS
);

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>id</th>
        <th>Акция</th>
        <th>Пользователь</th>
        <th>Время заявки</th>
        <th>Кол-во месяцев</th>
        <th>Подтвердить оплату</th>
        <th>Удалить</th>
    </tr>
    </thead>
    <?php foreach ($items as $item) {
        $url = Url::to(['superadmin_chat/user', 'id' => $item['id']]);
        ?>
        <tr>
            <td>
                <?= $item['id'] ?>
            </td>
            <td>
                <?php if ($item['cap_stock_logo']) { ?>
                    <img src="<?= $item['cap_stock_logo'] ?>" width="40">
                <?php } ?>
                <?= $item['cap_stock_name'] ?>
            </td>
            <td>
                <?php if ($item['cap_users_avatar']) { ?>
                    <img src="<?= $item['cap_users_avatar'] ?>" width="40">
                <?php } ?>
                <?= $item['cap_users_name_first'] . ' ' . $item['cap_users_name_last'] . ' (' . $item['cap_users_email'] . ')' ?>
            </td>
            <td>
                <?= Yii::$app->formatter->asDatetime($item['datetime']) ?>
            </td>
            <td>
                <?= $item['month'] ?>
            </td>
            <td>
                <?php if (\yii\helpers\ArrayHelper::getValue($item, 'is_paid', 0) == 0) { ?>
                    <button class="btn btn-primary buttonActivate" data-id="<?= $item['id'] ?>">Подтвердить оплату</button>
                <?php } else { ?>
                    <span class="label label-success">Оплачено, ожидает расчета</span>
                <?php } ?>
            </td>
            <td>
                <button class="btn btn-primary buttonDelete" data-id="<?= $item['id'] ?>">Удалить</button>
            </td>
        </tr>
    <?php } ?>
</table>