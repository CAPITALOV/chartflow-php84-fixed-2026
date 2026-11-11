<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 30.08.2015
 * Time: 9:32
 */

/** @var $user \app\models\User от кого */
/** @var $message \app\models\ChatMessage */

?>

<?= $this->render('_left', [
    'user'    => $user,
    'message' => $message
]); ?>