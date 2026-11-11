<?php
/**
 * @var $message \app\models\ChatMessage
 * @var $user    \app\models\user
 */
?>
Пришло новое сообщение:

<?= $message->getText() ?>

Пользователь:
Email: <?= $user->getEmail() ?>
Ссылка: <?= \yii\helpers\Url::to(['superadmin_chat/user', 'id' => $user->getId()]) ?>
