<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $item  \app\models\Stock */
/* @var $lineArrayPast  array */
/* @var $lineArrayFuture  array */
/* @var $isPaid  bool опачена ли эта акция? */

$this->title = $item->getField('name');

$colorGreen = [
    'label'                => "Курс биржевой",
    'fillColor'            => "rgba(220,220,220,0.2)",
    'strokeColor'          => "rgba(229,255,229,1)",
    'pointColor'           => "rgba(204,255,204,1)",
    'pointStrokeColor'     => "#fff",
    'pointHighlightFill'   => "#fff",
    'pointHighlightStroke' => "rgba(220,220,220,1)",
];
$colorRed = [
    'label'                => "Прогноз CLOSE RED",
    'fillColor'            => "rgba(220,220,220,0)",
    'strokeColor'          => "rgba(255,229,229,1)",
    'pointColor'           => "rgba(255,204,204,1)",
    'pointStrokeColor'     => "#fff",
    'pointHighlightFill'   => "#fff",
    'pointHighlightStroke' => "rgba(220,220,220,1)",
];
$colorBlue = [
    'label'                => "Прогноз RPO BLUE",
    'fillColor'            => "rgba(220,220,220,0)",
    'strokeColor'          => "rgba(229,229,255,1)",
    'pointColor'           => "rgba(204,204,255,1)",
    'pointStrokeColor'     => "#fff",
    'pointHighlightFill'   => "#fff",
    'pointHighlightStroke' => "rgba(220,220,220,1)",
];
?>

<h1 class="page-header"><?= $this->title ?></h1>

<h2 class="page-header">Прошлое</h2>
<?= \cs\Widget\ChartJs\Line::widget([
    'width'     => 800,
    'lineArray' => $lineArrayPast,
    'colors'    => [
        $colorGreen,
        $colorRed,
        $colorBlue,
    ],
]) ?>

<h2 class="page-header">Будущее</h2>
<?php if ($isPaid) { ?>
    <?= \cs\Widget\ChartJs\Line::widget([
        'width'     => 800,
        'lineArray' => $lineArrayFuture,
        'colors'    => [
            $colorRed,
            $colorBlue,
        ],
    ]) ?>
<?php } else { ?>
    <div class="form-group">
        <p><span class="label label-danger">График не оплачен</span></p>
    </div>
    <a
        href="<?= Url::to(['cabinet_wallet/add', 'id' => $item->getId()]) ?>"
        class="btn btn-default"
        style="width: 100%"
        >Купить</a>
<?php } ?>
