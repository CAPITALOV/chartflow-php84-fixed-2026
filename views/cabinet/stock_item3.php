<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\service\CalculatingProbability;

/* @var $this \yii\web\View */
/* @var $item  \app\models\Stock */
/* @var $lineArrayPast  array */
/* @var $lineArrayFuture  array */
/* @var $lineArrayCandels  array */
/* @var $isPaid  bool оплачена ли эта котировка? */

$this->title = $item->getField('name');
$this->registerJsFile("/js/actions.js", ['depends' => ['yii\web\JqueryAsset']]);

$monthList = [
    'января',
    'февраля',
    'марта',
    'апреля',
    'мая',
    'июня',
    'июля',
    'августа',
    'сентября',
    'октября',
    'ноября',
    'декабря',
];
if (empty($lineArrayFuture)) {
    echo '<p>По этой акции пока нет данных графика.</p>';
    return;
}
$dateMin = $lineArrayFuture[0]['date'];
$dateMax = $lineArrayFuture[count($lineArrayFuture)-1]['date'];
$dateMin = new \DateTime($dateMin);
$dateMax = new \DateTime($dateMax);
$dateMin = $dateMin->format('j') . ' ' . $monthList[$dateMin->format('n')-1];
$dateMax = $dateMax->format('j') . ' ' . $monthList[$dateMax->format('n')-1];
?>

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
             style="vertical-align:middle; font-size:20px; font-weight: bold; display:inline-block;">Chart for forecasting trends for the upcoming period 90+ days | График для прогнозирования тенденций на предстоящий период от текущей даты
        </div>
        <?print_r($item->getId());?>
        <div>[<?= $p1 = round((CalculatingProbability::initStock($item->getId(),1)->calc()) * 100) ?> / <?= $p2 = round((CalculatingProbability::initStock($item->getId(), 2)->calc()) * 100) ?> = <?= ($p2+$p1)/2 ?>]</div>
    </div>
</div>

<div id="chart2" class="center-block" style=" margin-left:75px; margin-right:75px;">
    <?php if (!is_null($lineArrayPast)) { ?>
        <?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 500,
        'chartOptions' => [
             'series' => [
                [
                    'type' => 'LineSeries',
                    'yAxis' => 'v2',
                    'stroke' => '#ff1000',
                    'strokeWidth' => 2,
                    'name' => 'change in the closing price',
                    'minBulletDistance' => 30,
                    'bullets' => [
                        [
                            'type' => 'CircleBullet',
                            'states' => [
                                'hover' => [
                                    'properties' => [
                                        'scale' => 1.2,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'tooltipText' => '[bold]{valueY}[/]',
                    'fill' => '#ff1000',
                    'tensionX' => 0.8,
                    'showOnInit' => 'true',
                    'dataFields' => [
                        'valueY' => 'red',
                        'categoryX' => 'date',
                    ]
                ],
                [
                    'type' => 'LineSeries',
                    'stroke' => '#0010ff',
                    'strokeWidth' => 2,
                    'name' => 'weighted average price',
                    'minBulletDistance' => 30,
                    'bullets' => [
                        [
                            'type' => 'CircleBullet',
                            'states' => [
                                'hover' => [
                                    'properties' => [
                                        'scale' => 1.2,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'tooltipText' => '[bold]{valueY}[/]',
                    'fill' => '#0010ff',
                    'tensionX' => 0.8,
                    'showOnInit' => 'true',
                    'dataFields' => [
                        'valueY' => 'blue',
                        'categoryX' => 'date',
                    ]
                ],
                [
                    'type' => 'LineSeries',
                    'yAxis' => 'v3',
                    'stroke' => '#10ff00',
                    'strokeWidth' => 2,
                    'name' => 'real price',
                    'minBulletDistance' => 30,
                    'bullets' => [
                        [
                            'type' => 'CircleBullet',
                            'states' => [
                                'hover' => [
                                    'properties' => [
                                        'scale' => 1.2,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'tooltipText' => '[bold]{valueY}[/]',
                    'fill' => '#10ff00',
                    'tensionX' => 0.8,
                    'showOnInit' => 'true',
                    'dataFields' => [
                        'valueY' => 'kurs',
                        'categoryX' => 'date',
                    ]
                ]
             ],
            'xAxes' => [[
                'renderer'=>[
                    'grid'=>[
                        'strokeWidth' => 0
                    ],
                ],
                'start' => 0.1,
                'end' => 1,
                'keepSelection' =>true,
                'type' => 'CategoryAxis',
                'dataFields' => [
                    'category' => 'date'
                ]
            ]],
            'yAxes' => [
                [
                    'type' => 'ValueAxis',
                    'renderer'=>[
                        'grid'=>[
                            'strokeWidth' => 0
                        ],
                        'line' => [
                            'strokeOpacity' => 1,
                            'strokeWidth' => 2,
                            'stroke' => '#0010ff',
                        ],
                        'labels' => [
                            'fill' => '#0010ff',
                        ]
                    ],
                    'dataFields' => [
                        'category' => 'blue'
                    ]
                ],
                [
                'type' => 'ValueAxis',
                'id' => 'v2',
                    'renderer'=>[
                        'grid'=>[
                            'strokeWidth' => 0
                        ],
                        'opposite' => true,
                        'line' => [
                            'strokeOpacity' => 1,
                            'strokeWidth' => 2,
                            'stroke' => '#ff1000',
                        ],
                        'labels' => [
                            'fill' => '#ff1000',
                        ]
                    ],
                    'dataFields' => [
                        'category' => 'red'
                    ]
                ],
                [
                'type' => 'ValueAxis',
                'id' => 'v3',
                    'renderer'=>[
                        'grid'=>[
                            'strokeWidth' => 0
                        ],
                        'opposite' => true,
                        'line' => [
                            'strokeOpacity' => 1,
                            'strokeWidth' => 2,
                            'stroke' => '#10ff00',
                        ],
                        'labels' => [
                            'fill' => '#10ff00',
                        ]
                    ],
                    'dataFields' => [
                        'category' => 'kurs'
                    ]
                ]
            ],
            'data'    => array_merge($lineArrayPast,$lineArrayFuture),
            'legend'       => []
        ],
    ]) ?>
    <?php } else { ?>
        <div class="alert alert-danger">
            Нет данных
        </div>
    <?php } ?>
    <p class="center-text text-center" style="margin-bottom: 65px;">Change in the closing price - (<b>CLS Red curve-красная</b>) | Weighted average price - (<b>RPO Blue curve-синяя</b>) | Real price (<b>Close Green curve-зеленая</b>) - (история торгов эмитента на бирже по цене закрытия) | </b> Choose the direction of the trend with help of  two forecast lines.They should be in the same direction | Выберите направление тренда с помощью двух линий прогноза. Они должны быть в одном направлении | </p>
</div>

<hr class="clearfix" style="color:#489F46; background-color:#489F46; height:3px; margin-top: 0px;margin-bottom: 0px;">
<div class="container-fluid flexcontainer" style="margin-bottom:0px;background-color:#ededed; height:90px;">
    <div onclick="javascript:toggleChart('chart3')"><img src="/images/collapseIcon.png"
                                                         style="height:35px;padding-right:35px;padding-left:10px;">
    </div>
    <div style="float:left;">
        <img src="/images/icon-info.png" style="height:30px;padding-right:35px;">
    </div>
    <div class="col-md-auto" style="float:right; ">
        <img src="/images/icon-index-history.png" style="height:35px;padding-right:15px;">

        <div class="text-nowrap"
             style="vertical-align:middle; font-size:20px; font-weight: bold; display:inline-block;">TradingView
        </div>
        <div>[<?= $p1 = round((CalculatingProbability::initStock($item->getId(),1)->calc()) * 100) ?> / <?= $p2 = round((CalculatingProbability::initStock($item->getId(), 2)->calc()) * 100) ?> = <?= ($p2+$p1)/2 ?>]</div>
    </div>
</div>

<div id="chart3" class="center-block" style=" margin-left:75px; margin-right:75px;">
    <!-- TradingView Widget BEGIN -->
    <?
    $code = $item->get('finam_code');
    if (strripos($code,'.') !== false) {
        $code = explode('.', $code)[0];
    }
    ?>
<div class="tradingview-widget-container">
  <div id="tradingview_95616"></div>
  <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/NASDAQ-AAPL/" rel="noopener" target="_blank"><span class="blue-text"><?=$code?> Chart</span></a> by TradingView</div>
  <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
  <script type="text/javascript">
  new TradingView.widget(
  {
  "width": '100%',
  "height": 500,
  "symbol": "<?=$code?>",
  "interval": "D",
  "timezone": "Etc/UTC",
  "theme": "light",
  "style": "1",
  "locale": "ru",
  "toolbar_bg": "#f1f3f6",
  "enable_publishing": false,
  "allow_symbol_change": true,
  "container_id": "tradingview_95616"
}
  );
  console.log(TradingView.widget);
  </script>
</div>
<!-- TradingView Widget END -->
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