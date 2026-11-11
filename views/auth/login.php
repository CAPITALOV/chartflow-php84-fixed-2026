<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */

$this->title = 'Вход в систему (Российский рынок акций) AI rus.chartflow.ru';
?>
<h1 class="page-header text-center"><?= Html::encode($this->title) ?><a class="btn btn-primary" style="display:inline-block;margin-left: 20px;background-color: #c81c38;border: none;border-radius: 24px;" href="https://chartflow.ru/login">Вход (US international market Nasdaq-NYSE) AI chartflow.ru</a></h1>
<div class="row">
<div class="col-lg-6 col-lg-offset-3">

    <p>Пожалуйста заполните следующие поля для входа: AI rus.chartflow.ru</p>

    <?php $form = ActiveForm::begin([
        'id'     => 'login-form',
    ]); ?>

    <?= $model->field($form, 'username') ?>
    <?= $model->field($form, 'password', [
        'template' => '{label}{beginWrapper}{input}<div style="margin-top: 5px;"><b>Забыли пароль?</b><span style="margin-left: 20px;"><a href=' . \yii\helpers\Url::to(['auth/password_recover']) . '>Восстановить</a></span></div>{hint}{error}{endWrapper}',
    ])->passwordInput() ?>

    <hr>
    <?= $model->field($form, 'rememberMe') ?>
    <hr>



    <div class="form-group">
        <?= Html::submitButton('Вход', [
            'class' => 'btn btn-primary btn-lg',
            'name'  => 'login-button',
            'style' => 'width: 100%;background-color: #c81c38;border: none;border-radius: 24px;',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
</div>