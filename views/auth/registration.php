<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\Form\Registration */

$this->title = 'Регистрация (Российский рынок акций) AI rus.chartflow.ru';

?>

   <h1 class="page-header text-center" style="ml"><?= Html::encode($this->title) ?><a class="btn btn-primary" style="display:inline-block;margin-left: 20px;background-color: #c81c38;border: none;border-radius: 24px;" href="https://rus.chartflow.ru/registration">Регистрация (US international market Nasdaq-NYSE) AI chartflow.ru</a></h1>

<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        <p>Благодарим за регистрацию AI chartflow.ru</p>
    </div>
<?php else: ?>

    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <?php $form = ActiveForm::begin([
                'id'                   => 'contact-form',
                'enableAjaxValidation' => true,
            ]); ?>
            <?php $field = $form->field($model, 'email',['inputOptions' => ['placeholder' => 'email']])->label('Почта', ['class' => 'hide']);
            $field->validateOnBlur = true;
            $field->validateOnChange = true;
            echo $field;
            ?>
            <?= $form->field($model, 'password1', ['inputOptions' => ['placeholder' => 'Пароль']])->passwordInput()->label('Пароль', ['class' => 'hide']) ?>
            <?= $form->field($model, 'password2', ['inputOptions' => ['placeholder' => 'Повторите пароль еще раз']])->passwordInput()->label('Пароль повтор', ['class' => 'hide']) ?>


            <hr>
            <div class="form-group">
                <?= Html::submitButton('Зарегистрироваться', [
                    'class' => 'btn btn-primary btn-lg',
                    'name' => 'contact-button',
                    'style' => 'width: 100%;background-color: #c81c38;border: none;border-radius: 24px;',
                ]) ?>
            </div>
            <hr>
            <div class="form-group">
                <a class="btn btn-primary btn-lg" href="<?= \yii\helpers\Url::to(['auth/login']) ?>" style="width: 100%;background-color: #c81c38;border: none;border-radius: 24px;">Войти в систему AI rus.chartflow.ru</a>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php endif; ?>