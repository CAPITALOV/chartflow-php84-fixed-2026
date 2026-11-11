<?php


use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $items array cap_chat_messages.* */
/* @var $userFrom integer я */
/* @var $userTo integer  кому я пишу */

$this->title = 'Обратная связь';
$this->registerJsFile('/js/pages/cabinet_chat/index.js', ['depends' => ['yii\web\JqueryAsset']]);
$this->registerJs(<<<JS

JS
);

?>
<style>

    .chat {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .chat li {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #999;
    }

    .chat li.left .chat-body {
        margin-left: 60px;
    }

    .chat li.right .chat-body {
        margin-right: 60px;
    }

    .chat li .chat-body p {
        margin: 0;
    }

    .panel .slidedown .glyphicon,
    .chat .glyphicon {
        margin-right: 5px;
    }

    .chat-panel .panel-body {
        height: 450px;
        overflow-y: scroll;
    }
    .chat .char-user-name {
        opacity: 0.3;
    }
    .chat .chat-img {
        opacity: 0.3;
    }

</style>
<div class="chat-panel panel panel-default" style="margin-top: 20px;">
<div class="panel-heading">
    <i class="glyphicon glyphicon-comment"></i>
    Переписка
<!--    <div class="btn-group pull-right">-->
<!--        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"-->
<!--                aria-expanded="false">-->
<!--            <i class="glyphicon glyphicon-menu-down"></i>-->
<!--        </button>-->
<!--        <ul class="dropdown-menu slidedown">-->
<!--            <li>-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-refresh fa-fw"></i> Refresh-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-check-circle fa-fw"></i> Available-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-times fa-fw"></i> Busy-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="#">-->
<!--                    <i class="glyphicon glyphicon-time"></i> Away-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="divider"></li>-->
<!--            <li>-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-sign-out fa-fw"></i> Sign Out-->
<!--                </a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->
</div>
<!-- /.panel-heading -->
<div class="panel-body">
    <ul
        class="chat"
        id="chatMessages"
        data-user-from="<?= $userFrom ?>"
        data-user-to="<?= $userTo ?>"
        >
        <?php if (count($items) == 0){ ?>
            <p class="alert alert-success">
                Нет сообщений
            </p>
        <?php } else { ?>
            <?php foreach ($items as $item) {
                if ($item['user_id_from'] == Yii::$app->user->id) {
                    $direction = 'right';
                } else {
                    $direction = 'left';
                }
                $user = \app\models\User::find($item['user_id_from']);
                $message = new \app\models\ChatMessage($item);
                ?>

                <?php if ($direction == 'right') { ?>
                    <?= $this->render('_right', [
                        'user'    => $user,
                        'message' => $message
                    ]); ?>
                <?php } else { ?>
                    <?= $this->render('_left', [
                        'user'    => $user,
                        'message' => $message
                    ]); ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
<!-- /.panel-body -->
<div class="panel-footer">
    <div class="input-group">
        <input id="btn-chat-input" type="text" class="form-control input-sm" placeholder="Введите сообщение здесь...">
            <span class="input-group-btn">
                <button class="btn btn-warning btn-sm" id="btn-chat-send">
                    Отправить
                </button>
            </span>
    </div>
</div>
<!-- /.panel-footer -->
</div>