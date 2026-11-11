<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\UnionCategory;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */

$this->title = 'Удалить курс';
?>

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        Успешно удалено.
    </div>

<?php else: ?>

    <div class="row col-lg-6">
        <?php $form = ActiveForm::begin([
            'id' => 'contact-form',
        ]); ?>
        <?= $model->field($form, 'dateMin') ?>
        <?= $model->field($form, 'dateMax') ?>

        <div class="form-group">
            <hr>
            <?= Html::submitButton('Удалить', [
                'class' => 'btn btn-default',
                'name'  => 'contact-button',
                'style' => 'width:100%',
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php endif; ?>