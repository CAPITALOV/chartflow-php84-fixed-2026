<?php


/** @var $this \yii\web\View */
/** @var $user \app\models\User */
/** @var $message \app\models\ChatMessage */

?>

<?= $this->render('_right', [
    'user'    => $user,
    'message' => $message
]); ?>