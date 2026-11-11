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

$this->title = 'Оплата прогноза Международный рынок';



?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>


<?php if (\Yii::$app->user->identity->getField('is_confirm', 0) == 0) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">Ваш email не подтвержден</div>
        <div class="panel-body">
            <p>Для того чтобы сделать заказ вам необходимо подтвердить свой email.</p>

            <p>Вам было выслано письмо ранее на ваш ящик указанный при регистрации.</p>

            <p>Если его нет, то нажмите</p>
            <button class="btn btn-primary " id="buttonSend">Отправить еще раз</button>
            <?php
            $url = \yii\helpers\Url::to(['cabinet_wallet/send_mail']);
            $this->registerJs(<<<JS
$('#buttonSend').click( function() {
    ajaxJson({
        url: '{$url}',
        success: function(ret) {
            showInfo('Успешно');
        }
    });
});
JS
            );
            ?>
        </div>
    </div>
<?php } else { ?>
    <?php
    \app\assets\FuelUX\Asset::register($this);
    $url = \yii\helpers\Url::to(['cabinet_wallet/add2_ajax']);
    $this->registerJs(<<<JS
    $('#myWizard').wizard({
        disablePreviousStep: false
    });
    $('#buttonNext').click(function() {
        ajaxJson({
            url: '{$url}',
            data: {
                monthcounter: $('#cabinetwalletadd1-monthcounter').val(),
                stock_id: $('input[name="{$model->formName()}[stock_id]"]').val()
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


    <div class="wizard" data-initialize="wizard" id="myWizard">
        <div class="steps-container">
            <ul class="steps">
                <li data-step="1" data-name="campaign" class="active"><span class="badge">1</span>Количество
                    месяцев<span
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


                    <input type="hidden" name="<?= $model->formName() ?>[stock_id]">


                    <?= $model->field($form, 'stockId')
                        ->label('Котировка')
                        ->widget('yii\jui\AutoComplete', [
                            'clientOptions' => [
                                'source' => \app\models\Stock::query(['not', ['finam_market' => 1]])->select(['id', 'name as value'])->orderBy(['name' => SORT_ASC])->all(),
                                'select' => new \yii\web\JsExpression(<<<JS
    function(event, ui) {
        $('input[name="{$model->formName()}[stock_id]"]').val(ui.item.id);
    }
JS
                                ),
                            ],
                            'options'       => [
                                'class'       => 'form-control',
                                'placeholder' => 'Найти...',
                            ]
                        ])
                    ?>
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
                    <input type="hidden" name="customerNumber" value="" id="customerNumber">

                    <!--                Сумма:<br>-->
                    <input type="hidden" name="sum" value="" id="sum">

                    <!--                ФИО плательщика:<br>-->
                    <input name="custName" type="hidden"  id="custName">

                    <!--                Email:<br>-->
                    <input name="custEmail" type="hidden"  id="custEmail">

                    <!--                Адрес доставки:<br>-->
                    <input name="custAddr" type="hidden">

                    <!--                Телефон плательщика<br>-->
                    <input name="orderDetails" type="hidden">

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

                    <input type="submit" value="Заплатить" class="btn btn-default"
                           style="width: 100%; max-width: 400px;  "/>
                </form>

            </div>
        </div>
    </div>

    <hr>
    <div class="panel panel-info">
        <div class="panel-heading">Условия предоставления услуги</div>
        <div class="panel-body">
            <p>Заказывая услуги после 15 числа текущего месяца, вы получаете услугу до конца следующего.</p>
            <p>Фактически оплачивая следующий месяц авансовым платежом.</p>
            <p>Срок расчёта котировки национального рынка от 1 до 5 дней, зарубежного до 5 после оплаты.</p>
        </div>
    </div>
<?php } ?>
