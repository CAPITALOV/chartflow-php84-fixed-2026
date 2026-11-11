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

$this->title = 'Оплата прогноза Национальный рынок (Московская биржа MOEX)';



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

    <!-- <div class="row">
        <div class="col-lg-12"> -->
            <?php
            \app\assets\FuelUX\Asset::register($this);
            $url = \yii\helpers\Url::to(['cabinet_wallet/add1_ajax']);
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
                $('#shopSuccessURL').val(ret.request.shopSuccessURL);
                $('#shopFailURL').val(ret.request.shopFailURL);
            }
        })
    })
    $('form#ddd1').on('submit', function(e) {
        console.log(this);
        e.preventDefault();
        var url = $(this).attr('action');
        window.open(url, '_blank');
    });
    $('.radioList').radio();
JS
            );
            ?>


            <div class="wizard" data-initialize="wizard" id="myWizard">
                <div class="steps-container">
                    <ul class="steps">
                        <li data-step="1" data-name="campaign" class="active"><span class="badge">1</span>Количество месяцев<span
                                class="chevron"></span></li>
                        <li data-step="2"><span class="badge">2</span>Оплата<span class="chevron"></span></li>
                    </ul>
                </div>
                <div class="step-content">
                    <div class="step-pane active sample-pane alert" data-step="1">
                        <div class="col-lg-5 row">
                            <?php $form = ActiveForm::begin([
                                'id' => 'contact-form',
                            ]); ?>
                            <?= $model->field($form, 'monthCounter')->label('Выберите сколько месяцев вы хотите оплатить') ?>
                            <input type="hidden" name="<?= $model->formName() ?>[stock_id]">


                            <?= $model->field($form, 'stockId')
                                ->label('Котировка выбирите из списка по коду и тикеру биржи Stock Tickers & Exchanges')
                                ->widget('yii\jui\AutoComplete', [
                                    'clientOptions' => [
                                        'source' => \app\models\Stock::query(['finam_market' => 1])->select(['id', 'name as value'])->orderBy(['name' => SORT_ASC])->all(),
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
        <h4>Заявка принята</h4>

        <p>Ваша заявка отправлена администратору. На указанный email придёт письмо
        с реквизитами для оплаты. Срок расчёта котировки — от 1 до 5 дней после оплаты.</p>

        <a href="/cabinet" class="btn btn-default" style="width: 100%; max-width: 400px;">
        Вернуться в кабинет
        </a>

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
        <!-- </div>
        <div class="col-lg-4">
            <img src="/images/index/snapshot.png" width="100%" class="thumbnail">
        </div>
    </div> -->





<?php } ?>




