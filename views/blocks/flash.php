<?php if (Yii::$app->session->hasFlash('flash')): foreach (Yii::$app->session->getFlash('flash') as $flash): ?>
        <div class="alert alert-<?= isset($flash['type']) ? $flash['type'] : 'info'?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?= $flash['message']?>
        </div>
    <?php endforeach;
endif; ?>


