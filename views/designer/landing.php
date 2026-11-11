<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */

$this->title = 'Редактирование лендинга';
?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        Успешно.
    </div>

<?php else: ?>


    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id'      => 'contact-form',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <?= $model->field($form, 'img1') ?>
            <?= $model->field($form, 'img2') ?>
            <?= $model->field($form, 'img3') ?>
            <?= $model->field($form, 'html')->textarea(['rows' => 20]) ?>

            <div class="form-group">
                <hr>
                <?= Html::submitButton('Обновить', [
                    'class' => 'btn btn-default',
                    'name'  => 'contact-button',
                    'style' => 'width:100%',
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php endif; ?>
