<?php
/**
 * @var $message \app\models\ChatMessage
 * @var $user    \app\models\user
 */
?>
<p>Пришло новое сообщение:</p>
<pre style="
    display: block;
    padding: 9.5px;
    margin: 0 0 10px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;
">
    <?= $message->getText() ?>
</pre>
<p>Пользователь:</p>
<p>email: <?= $user->getEmail() ?></p>
<p><a href="<?= \yii\helpers\Url::to(['superadmin_chat/user', 'id' => $user->getId()]) ?>">ссылка</a></p>
