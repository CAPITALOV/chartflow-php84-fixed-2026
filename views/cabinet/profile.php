<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\UnionCategory;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\Form\Profile */

$this->title = 'Редактирование профиля';


?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="col-lg-8">
    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Успешно обновлено.
        </div>

    <?php else: ?>

        <?php $form = ActiveForm::begin([
            'id'      => 'contact-form',
            'options' => ['enctype' => 'multipart/form-data'],
            'layout'  => 'horizontal',
        ]); ?>
        <?= $model->field($form, 'name_org') ?>
        <?= $model->field($form, 'name_first') ?>
        <?= $model->field($form, 'name_last') ?>
        <?= $model->field($form, 'avatar') ?>
        <?= $model->field($form, 'phone') ?>

        <div class="form-group">
            <label class="control-label col-sm-3" for="profile-phone">Реферальная ссылка</label>

            <div class="col-sm-6">
                <div class="input-group input-group">
                    <input class="form-control" placeholder="" id="profile-ref-link" value="<?= Url::to(['auth/registration_referal', 'code' => $model->referal_code], true) ?>" style="font-family: 'courier new'">
                            <span class="input-group-btn">
                                <?php
                                \cs\assets\ZClip\Asset::register($this);
                                $zPath = \Yii::$app->assetManager->getBundle('cs\assets\ZClip\Asset')->baseUrl;
                                $this->registerJs(<<<JS
    $("#buttonCopyRefLink").zclip({
        path: '{$zPath}' + '/ZeroClipboard.swf',
        copy: $('#profile-ref-link').val(),
        beforeCopy: function () {
        },
        afterCopy: function () {
            showInfo('Скопировано');
        }
    });
JS
                                );
                                ?>
                                <button class="btn btn-info" type="button" id="buttonCopyRefLink" style="width: 50px;" title="Скопировать в буфер">
                                    <span class="glyphicon glyphicon-copyright-mark"></span>
                                </button>
                            </span>
                </div>

                <div class="help-block help-block-error"></div>
            </div>
        </div>


        <hr class="featurette-divider">
        <div class="form-group">
            <?= Html::submitButton('Обновить', [
                'class' => 'btn btn-primary',
                'name'  => 'contact-button',
                'style' => 'width: 100%;',
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>

    <?php endif; ?>


</div>



