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

$this->title = 'Регистрация по реферальным ссылкам';
\cs\assets\Confirm\Asset::register($this);

$urlDelete = Url::to(['superadmin/referal_delete']);
$this->registerJs(<<<JS
    $('.buttonDelete').confirmation({
        btnOkLabel: 'Да',
        btnCancelLabel: 'Нет',
        title: 'Вы уверены',
        popout: true,
        onConfirm: function() {
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
    $('.dateTooltip').tooltip();
JS
);

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query'      => \app\models\Registration::query()
            ->select([
                'cap_registration.*',
                'cap_users.avatar       as cap_users_avatar',
                'cap_users.name_first   as cap_users_name_first',
                'cap_users.name_last    as cap_users_name_last',
                'cap_users.name_org     as cap_users_name_org',
                'cap_users.email        as cap_users_email',
            ])
            ->innerJoin('cap_users', 'cap_registration.referal_code = cap_users.referal_code')
            ->orderBy(['cap_registration.datetime' => SORT_DESC]),
        'pagination' => [
            'pageSize' => 20,
        ],

    ]),
    'tableOptions' => [
        'class' => 'table table-striped table-hover',
    ],
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn', // <-- here
            // you may configure additional properties here
        ],
        [
            'header'  => 'Время регистрации',
            'content' => function ($model, $key, $index, $column) {
                return Html::tag('abbr', \cs\services\DatePeriod::back($model['datetime']), [
                    'title' => Yii::$app->formatter->asDatetime($model['datetime'], 'php:d.m.Y в H:i (P)'),
                    'class' => 'dateTooltip'
                ]);
            }
        ],
        [
            'header'  => 'Чья ссылка?',
            'content' => function ($model, $key, $index, $column) {
                $arr = [];
                if ($model['cap_users_avatar']) {
                    $arr[] = Html::img($model['cap_users_avatar'], [
                        'width' => 40,
                        'style' => 'padding-right: 5px;',
                    ]);
                }
                $arr[] = $model['cap_users_name_first'] . ' ' . $model['cap_users_name_last'] . ' (' . $model['cap_users_email'] . ')';

                return join('', $arr);
            }
        ],
        [
            'header'  => 'Кто зарегистрировался?',
            'content' => function ($model, $key, $index, $column) {
                $user = \app\models\User::find($model['user_id'])->getFields();
                $arr = [];
                if ($user['avatar']) {
                    $arr[] = Html::img($user['avatar'], [
                        'width' => 40,
                        'style' => 'padding-right: 5px;',
                    ]);
                }
                $arr[] = $user['name_first'] . ' ' . $user['name_last'] . ' (' . $user['email'] . ')';

                return join('', $arr);
            }
        ],
        [
            'header'  => 'Оплатил?',
            'content' => function ($model, $key, $index, $column) {
                if ($model['is_paid']) {
                    return Html::tag('span', 'Да', [
                        'class' => 'label label-success',
                    ]);
                } else {
                    return Html::tag('span', 'Нет', [
                        'class' => 'label label-default',
                    ]);
                }
            }
        ],
        [
            'header'  => 'Удалить',
            'content' => function ($model, $key, $index, $column) {
                return Html::button('Удалить', [
                    'class' => 'btn btn-primary buttonDelete',
                    'data'  => [
                        'id' => $model['id']
                    ]
                ]);
            }
        ],
    ]

]) ?>
