<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.08.2015
 * Time: 9:32
 */

/** @var $user \app\models\User */
/** @var $message \app\models\ChatMessage */

?>

<li class="right clearfix">
    <span class="chat-img pull-right">
        <img
            src="<?= $user->getAvatar() ?>"
            alt="<?= $user->getNameFull() ?>"
            class="img-circle"
            width="50"
            >
    </span>

    <div class="chat-body clearfix">
        <div class="header">
            <small class="text-muted chat-datetime" data-time="<?= $message->getDateTime() ?>" title="<?= Yii::$app->formatter->asDatetime($message->getDateTime()) ?>">
                <i class="glyphicon glyphicon-time"></i> <?= \cs\services\DatePeriod::back($message->getDateTime()) ?>
            </small>
            <strong class="pull-right primary-font char-user-name в"><?= $user->getNameFull() ?></strong>
        </div>
        <p>
            <?= \cs\helpers\Html::encode($message->getText()) ?>
        </p>
    </div>
</li>