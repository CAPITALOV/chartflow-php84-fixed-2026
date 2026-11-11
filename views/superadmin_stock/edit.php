<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\UnionCategory;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        Успешно обновлено.
    </div>

<?php else: ?>


    <div class="col-lg-5 row">
        <?php $form = ActiveForm::begin([
            'id' => 'contact-form',
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
        <?= $model->field($form, 'name') ?>
        <?= $model->field($form, 'description') ?>
        <?= $model->field($form, 'logo') ?>
        <?= $model->field($form, 'finam_em') ?>
        <?= $model->field($form, 'finam_market') ?>
        <?= $model->field($form, 'finam_code') ?>
        <?= $model->field($form, 'is_enabled') ?>

        <div class="form-group">
            <hr>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Обновить', [
                'class' => 'btn btn-default',
                'name'  => 'contact-button',
                'style' => 'width:100%',
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php endif; ?>
