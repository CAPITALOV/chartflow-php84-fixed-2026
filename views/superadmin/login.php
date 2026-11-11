<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */

$this->title = 'Вход в административный модуль';
?>


<div class="row col-lg-6 col-lg-offset-3">
    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста заполните следующие поля для входа:</p>

    <?php $form = ActiveForm::begin([
        'id'     => 'login-form',
    ]); ?>

    <?= $model->field($form, 'username') ?>
    <?= $model->field($form, 'password', [
        'template' => '{label}{beginWrapper}{input}<div style="margin-top: 5px;">Забыли пароль?<span style="margin-left: 20px;"><a href=' . \yii\helpers\Url::to(['auth/password_recover']) . '>Восстановить</a></span></div>{hint}{error}{endWrapper}',
    ])->passwordInput() ?>

    <?= $model->field($form, 'rememberMe') ?>
<hr>
    <p>Вход через социальные сети</p>
    <?= \yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['auth/auth']
    ]); ?>
    <hr>


    <div class="form-group">
            <?= Html::submitButton('Вход', [
                'class' => 'btn btn-primary',
                'name'  => 'login-button',
                'style' => 'width: 100%;',
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>