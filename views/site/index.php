<?php

use app\models\Translator as T;

/* @var $this yii\web\View */
$this->title = T::t('Welcome to adminnistrative panel');

?>
<div class="site-index">

    <div class="jumbotron"<?= !$this->context->user->identity ? ' style="position:relative;left:-12%;"': '' ?>>
        <h2><?= $this->title ?>!</h2>
        <?php if (!$this->context->user->identity): ?>
            <p><a class="btn btn-lg btn-success" href="/login"><?= T::t('Login into system') ?></a></p>
        <?php endif ?>
    </div>
</div>
