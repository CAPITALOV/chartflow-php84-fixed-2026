<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $item  \app\models\Stock */
/* @var $lineArrayPast  array */
/* @var $lineArrayFuture  array */
/* @var $lineArrayCandels  array */

$this->title = $item->getField('name');
$this->registerJsFile("/js/actions.js", ['depends' => ['yii\web\JqueryAsset']]);
//$this->registerCssFile("/css/site.css");
$this->registerCss("
.flexcontainer {
    display: -webkit-flex;
    display: flex;
    -webkit-flex-direction: row /* works with row or column */
    flex-direction: row;
    -webkit-align-items: center;
    align-items: center;
    -webkit-justify-content: flex-start;
    justify-content: flex-start;
}
")
?>


<hr class="clearfix" style="color:#489F46; background-color:#489F46; height:3px; margin-top: 0px; margin-bottom: 0px;">
<div class="container-fluid flexcontainer" style="margin-bottom:0; background-color:#ededed; height:90px;">
    <div onclick="javascript:toggleChart('chart3')"><img src="/images/collapseIcon.png"
                                                         style="height:35px;padding-right:35px;padding-left:10px;">
    </div>
    <div style="float:left;">
        <img src="/images/icon-info.png" style="height:30px;padding-right:35px;">
    </div>
    <div class="col-md-auto" style="float:right; ">
        <img src="/images/icon-history.png" style="height:35px;padding-right:15px;">

        <div class="text-nowrap"
             style="vertical-align:middle; font-size:18px; font-weight: bold; display:inline-block;">Просмотр архивных
            котировок
        </div>
    </div>
</div>

<div id="chart3" class="center-block" style="margin-left:75px; margin-right:75px; margin-bottom:0">
    <?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 600,
        'lineArray'    => $lineArrayCandels,
        'chartOptions' => [
            'language'       => 'ru',
            "type"           => "serial",
            "theme"          => "light",
            "dataDateFormat" => 'YYYY-MM-DD',
            "valueAxes"      => [[
                "position" => "left"
            ]],
            "graphs"         => [
                [
                    "id"                 => "g1",
                    "balloonText"        => "Открытие:<b>[[open]]</b><br>min:<b>[[low]]</b><br>max:<b>[[high]]</b><br>Закрытие:<b>[[close]]</b><br>",
                    "closeField"         => "close",
                    "fillColors"         => "#7f8da9",
                    "highField"          => "high",
                    "lineColor"          => "#7f8da9",
                    "lineAlpha"          => 1,
                    "lowField"           => "low",
                    "fillAlphas"         => 0.9,
                    "negativeFillColors" => "#db4c3c",
                    "negativeLineColor"  => "#db4c3c",
                    "openField"          => "open",
                    "title"              => "Price:",
                    "type"               => "candlestick",
                    "valueField"         => "close"
                ]
            ],
            "chartScrollbar" => [
                "graph"           => "g1",
                "graphType"       => "line",
                "scrollbarHeight" => 30
            ],
            "chartCursor"    => [
                "valueLineEnabled"        => true,
                "valueLineBalloonEnabled" => true
            ],
            "categoryField"  => "date",
            "categoryAxis"   => [
                "parseDates" => true
            ],
            "export"         => [
                "enabled"  => true,
                "position" => "bottom-right"
            ]
        ]
    ]) ?>

</div>


<hr class="clearfix" style="color:#489F46; background-color:#489F46; height:3px; margin-top: 0px;margin-bottom: 0px;">
<div class="container-fluid flexcontainer" style="margin-bottom:0px;background-color:#ededed; height:90px;">
    <div onclick="javascript:toggleChart('chart2')"><img src="/images/collapseIcon.png"
                                                         style="height:35px;padding-right:35px;padding-left:10px;">
    </div>
    <div style="float:left;">
        <img src="/images/icon-info.png" style="height:30px;padding-right:35px;">
    </div>
    <div class="col-md-auto" style="float:right; ">
        <img src="/images/icon-index-history.png" style="height:35px;padding-right:15px;">

        <div class="text-nowrap"
             style="vertical-align:middle; font-size:20px; font-weight: bold; display:inline-block;">Просмотр истории
            индексов прошедших торгов
        </div>
    </div>
</div>

<div id="chart2" class="center-block" style="margin-top:0px; margin-left:75px; margin-right:75px;">
    <?php if ($lineArrayPast) { ?>
        <?= \cs\Widget\AmCharts\CandleStick::widget([
            'height'       => 600,
            'lineArray'    => $lineArrayPast,
            'chartOptions' => [
                'language'       => 'ru',
                "type"           => "serial",
                "theme"          => "light",
                "legend"         => [
                    "useGraphSettings" => true
                ],
                "valueAxes"      => [
                    [
                        "id"            => "v1",
                        "axisColor"     => "#FF1000",
                        "axisThickness" => 2,
                        "gridAlpha"     => 0,
                        "axisAlpha"     => 1,
                        "position"      => "left"
                    ],
                    [
                        "id"            => "v2",
                        "axisColor"     => "#0010ff",
                        "axisThickness" => 2,
                        "gridAlpha"     => 0,
                        "axisAlpha"     => 1,
                        "position"      => "right"
                    ],
                    [
                        "id"            => "v3",
                        "axisColor"     => "#10ff00",
                        "axisThickness" => 2,
                        "gridAlpha"     => 0,
                        "offset"        => 50,
                        "axisAlpha"     => 1,
                        "position"      => "left"
                    ]
                ],
                "graphs"         => [
                    [
                        "valueAxis"             => "v1",
                        "lineColor"             => "#FF1000",
                        "bullet"                => "round",
                        "bulletBorderThickness" => 1,
                        "hideBulletsCount"      => 30,
                        "title"                 => "красный",
                        "valueField"            => "red",
                        "fillAlphas"            => 0
                    ],
                    [
                        "valueAxis"             => "v2",
                        "lineColor"             => "#0010ff",
                        "bullet"                => "round",
                        "bulletBorderThickness" => 1,
                        "hideBulletsCount"      => 30,
                        "title"                 => "синий",
                        "valueField"            => "blue",
                        "fillAlphas"            => 0
                    ],
                    [
                        "valueAxis"             => "v3",
                        "lineColor"             => "#10ff00",
                        "bullet"                => "round",
                        "bulletBorderThickness" => 1,
                        "hideBulletsCount"      => 30,
                        "title"                 => "зеленый",
                        "valueField"            => "kurs",
                        "fillAlphas"            => 0
                    ]
                ],
                "chartScrollbar" => [],
                "chartCursor"    => [
                    "cursorPosition" => "mouse"
                ],
                "categoryField"  => "date",
                "categoryAxis"   => [
                    "parseDates"       => true,
                    "axisColor"        => "#DADADA",
                    "minorGridEnabled" => true
                ],
                "export"         => [
                    "enabled"  => true,
                    "position" => "bottom-right"
                ]
            ],
        ]) ?>
    <?php } else { ?>
        <div class="alert alert-danger">
            Нет данных
        </div>
    <?php } ?>
</div>

<div class="modal fade" id="myModalBlue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Относительный ценовой генератор</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalRed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Цена по закрытию торгов</h4>
            </div>
            <div class="modal-body">
                <p>
                    ...
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

