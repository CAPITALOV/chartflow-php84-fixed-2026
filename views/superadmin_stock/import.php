<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\UnionCategory;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */

$this->title = 'Импортировать прогноз';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        Успешно добавлено.
    </div>

<?php else: ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id'      => 'contact-form',
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>
            <?= $model->field($form, 'fileRed')->fileInput() ?>
            <?= $model->field($form, 'fileBlue')->fileInput() ?>
            <?= $model->field($form, 'isReplaceExisting') ?>

            <div class="form-group">
                <hr>
                <?= Html::submitButton('Импортировать', [
                    'class' => 'btn btn-default',
                    'name'  => 'contact-button',
                    'style' => 'width:100%',
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php endif; ?>