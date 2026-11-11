<?php


/** @var $this \yii\web\View */
/** @var $user \app\models\User от кого */
/** @var $message \app\models\ChatMessage */

?>

<li class="left clearfix">
    <span class="chat-img pull-left">
        <img
            src="<?= $user->getAvatar() ?>"
            alt="<?= $user->getNameFull() ?>"
            class="img-circle"
            width="50"
            >
    </span>

    <div class="chat-body clearfix">
        <div class="header">
            <strong class="primary-font char-user-name"><?= $user->getNameFull() ?></strong>
            <small class="pull-right text-muted chat-datetime" data-time="<?= $message->getDateTime() ?>" title="<?= Yii::$app->formatter->asDatetime($message->getDateTime()) ?>">
                <i class="glyphicon glyphicon-time"></i> <?= \cs\services\DatePeriod::back($message->getDateTime()) ?>
            </small>
        </div>
        <p>
            <?= \cs\helpers\Html::encode($message->getText()) ?>
        </p>
    </div>
</li>