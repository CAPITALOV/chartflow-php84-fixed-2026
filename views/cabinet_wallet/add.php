<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model cs\base\BaseForm */
/* @var $stock \app\models\Stock */

$this->title = 'Оплата прогноза';
\app\assets\FuelUX\Asset::register($this);

$url = \yii\helpers\Url::to(['cabinet_wallet/add_step1']);
$this->registerJs(<<<JS
    $('#myWizard').wizard({
        disablePreviousStep: false
    });
    $('#buttonNext').click(function() {
        ajaxJson({
            url: '{$url}',
            data: {
                monthcounter: $('#cabinetwalletadd-monthcounter').val(),
                stock_id: $('#stock_id').html()
            },
            success: function(ret) {
                $('#customerNumber').val(ret.request.id);
                $('#sum').val(ret.request.sum);
                $('#custName').val(ret.user.fio);
                $('#custEmail').val(ret.user.email);
                $('#myWizard').wizard('next');
            }
        })
    })
    $('.radioList').radio();
JS
);

?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>



<div class="hide" id="stock_id"><?= $stock->getId() ?></div>
<div class="wizard" data-initialize="wizard" id="myWizard">
    <div class="steps-container">
        <ul class="steps">
            <li data-step="1" data-name="campaign" class="active"><span class="badge">1</span>Количество месяцев<span
                    class="chevron"></span></li>
            <li data-step="2"><span class="badge">2</span>Оплата<span class="chevron"></span></li>
        </ul>
    </div>
    <!--    <div class="actions">-->
    <!--        <button type="button" class="btn btn-default btn-prev"><span class="glyphicon glyphicon-arrow-left"></span>Назад</button>-->
    <!--        <button type="button" class="btn btn-default btn-next" data-last="Complete">Далее<span class="glyphicon glyphicon-arrow-right"></span></button>-->
    <!--    </div>-->
    <div class="step-content">
        <div class="step-pane active sample-pane alert" data-step="1">
            <div class="col-lg-5 row">
                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                ]); ?>
                <?= $model->field($form, 'monthCounter')->label('Выберите сколько месяцев вы хотите оплатить') ?>

                <div class="form-group">
                    <hr>
                    <?= Html::button('Далее', [
                        'class' => 'btn btn-default',
                        'name'  => 'contact-button',
                        'style' => 'width:100%',
                        'id'    => 'buttonNext'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="step-pane sample-pane bg-info alert" data-step="2">
            <h4>Выберите способ оплаты</h4>

            <form target="_blank" action="https://money.yandex.ru/eshop.xml" method="post" id="ddd1">

                <!-- ОБЯЗАТЕЛЬНАНЫЕ ПОЛЯ (все параметры яндекс.кассы регистрозависимые) -->
                <input type="hidden" name="shopId" value="100142">
                <input type="hidden" name="scid" value="34126">

                <!--                Идентификатор клиента/Номер заказа:<br>-->
                <input type="hidden" name="customerNumber" size="43" placeholder="" value="" id="customerNumber">

                <!--                Сумма:<br>-->
                <input type="hidden" name="sum" value="" size="43" placeholder="введите сумму заказа в рублях" id="sum">

                <!--                ФИО плательщика:<br>-->
                <input name="custName" type="hidden" size="43" placeholder="" id="custName">

                <!--                Email:<br>-->
                <input name="custEmail" type="hidden" size="43" placeholder="" value="" id="custEmail">

                <!--                Адрес доставки:<br>-->
                <input name="custAddr" type="hidden" size="43" placeholder="город, улица, номер дома, квартира">

                <!--                Телефон плательщика<br>-->
                <input name="orderDetails" type="hidden" size="43" placeholder="пример +79031234567">

                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="PC">
                            Оплата со счета в Яндекс.Деньгах
                    </label>
                </div>
                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="AC">
                            Оплата банковской картой
                    </label>
                </div>
                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="GP">
                            Оплата по коду через терминал
                    </label>
                </div>
                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="WM">
                            Оплата cо счета WebMoney
                    </label>
                </div>
                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="AB">
                            Оплата через Альфа-Клик
                    </label>
                </div>
                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="PB">
                            Оплата через Промсвязьбанк
                    </label>
                </div>
                <div class="radio">
                        <label class="radio-custom radioList" data-initialize="radio">
                        <input class="sr-only" name="paymentType" type="radio" value="MA">
                            Оплата через MasterPass
                    </label>
                </div>

                <input type="submit" value="Заплатить" class="btn btn-default" style="width: 100%; max-width: 400px;  "/>
            </form>

        </div>
    </div>
</div>

<p style="margin-top: 20px;">Оплачиваемая акция: <?= $stock->getName() ?></p>
<p><img src="<?= $stock->getImage() ?>" class="thumbnail" width="200"></p>
