<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\service\CalculatingProbability;

/* @var $this \yii\web\View */
/* @var $item  \app\models\Stock */
/* @var $lineArrayKurs  array */
/* @var $lineArrayRed  array */
/* @var $lineArrayBlue  array */
/* @var $lineArrayUnion  array */
/* @var $lineArrayUnion2  array */
/* @var $isPaid  bool опачена ли эта акция? */

$this->title = $item->getField('name');

$model = new \app\models\Form\StockItemGraph();

\app\assets\Slider\Asset::register($this);
$timeEnd = time();
$timeStart = $timeEnd - 60 * 60 * 24 * 30 * 3;
$defaultEnd = $timeEnd;
$defaultStart = $defaultEnd - 60*60*24*30;
$this->registerJs(<<<JS
$('#slider').rangeSlider({
    bounds: {min: {$timeStart}, max: {$timeEnd}},
    formatter: function(val) {
        var d = new Date();
        d.setTime(parseInt(val) + '000');
        var out = d.getDate() + '.' + (d.getMonth() + 1) + '.' + d.getFullYear();

        return out;
    },
    defaultValues:{min: {$defaultStart}, max: {$defaultEnd}}
});
JS
);

$colorGreen = [
    'label'                => "Курс",
    'fillColor'            => "rgba(220,220,220,0.2)",
    'strokeColor'          => "rgba(120,255,120,1)",
    'pointColor'           => "rgba(70,255,70,1)",
    'pointStrokeColor'     => "#fff",
    'pointHighlightFill'   => "#fff",
    'pointHighlightStroke' => "rgba(220,220,220,1)",
];
$colorRed = [
    'label'                => "Прогноз CLOSE",
    'fillColor'            => "rgba(220,220,220,0)",
    'strokeColor'          => "rgba(255,120,120,1)",
    'pointColor'           => "rgba(255,70,70,1)",
    'pointStrokeColor'     => "#fff",
    'pointHighlightFill'   => "#fff",
    'pointHighlightStroke' => "rgba(220,220,220,1)",
];
$colorBlue = [
    'label'                => "Прогноз RPO",
    'fillColor'            => "rgba(220,220,220,0)",
    'strokeColor'          => "rgba(120,120,255,1)",
    'pointColor'           => "rgba(70,70,255,1)",
    'pointStrokeColor'     => "#fff",
    'pointHighlightFill'   => "#fff",
    'pointHighlightStroke' => "rgba(220,220,220,1)",
];
?>

<h1 class="page-header"><?= $this->title ?></h1>

<?php
$logo = $item->getField('logo', '');
if ($logo) {
    ?>
<img src="<?= $logo ?>" class="thumbnail">
<?php
}
?>
<h2 class="page-header">Прогноз (красный CLOSE)</h2>
<?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 500,
        'chartOptions' => [
             'series' => [
                [
                    'type' => 'LineSeries',
                    'stroke' => '#ff1000',
                    'strokeWidth' => 2,
                    'minBulletDistance' => 20,
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
                    'name' => 'Прогноз (красный CLOSE)',
                    'tooltipText' => '[bold]{valueY}[/]',
                    'fill' => '#ff1000',
                    'tensionX' => 0.8,
                    'showOnInit' => 'true',
                    'dataFields' => [
                        'valueY' => 'red',
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
             	'start' => 0.95,
            	'end' => 1,
            	'keepSelection' =>true,
                'type' => 'CategoryAxis',
                'minLabelPosition' => 0,
                'maxLabelPosition'=>1,
                'dataFields' => [
                    'category' => 'date'
                ]
            ]],
            'yAxes' => [[
            	'grid'=>[
            		'strokeWidth' => 0.
            	],
                'type' => 'ValueAxis',
                'renderer'=>[
                	'grid'=>[
	            		'strokeWidth' => 0
	            	],
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
            ]],
            'data'    => $lineArrayRed,
            'legend'       => []
        ],
    ]) ?>
<?//= \cs\Widget\ChartJs\Line::widget([
  //  'width'     => 800,
  //  'lineArray' => $lineArrayRed,
  //  'colors' => [$colorRed],
//]) ?>

<h2 class="page-header">Прогноз (синий RPO)</h2>
<?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 500,
        'chartOptions' => [
            'series' => [
                [
                    'type' => 'LineSeries',
                    'stroke' => '#0010ff',
                    'strokeWidth' => 2,
                    'name' => 'Прогноз (синий RPO)',
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
                ]
            ],
            'xAxes' => [[
            	'renderer'=>[
                	'grid'=>[
	            		'strokeWidth' => 0
	            	],
                ],
            	'start' => 0.95,
            	'end' => 1,
            	'keepSelection' =>true,
                'type' => 'CategoryAxis',
                'dataFields' => [
                    'category' => 'date'
                ]
            ]],
            'yAxes' => [[
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
                'fillWidth' => 2,
                'dataFields' => [
                    'category' => 'blue'
                ]
            ]],
            'data'    => $lineArrayBlue,
            'legend'       => []
        ],
    ]) ?>
<?//= \cs\Widget\AmCharts\CandleStick::widget([
        // 'enableExport' => true,
        // 'lineArray'    => $lineArrayBlue,
        // 'height'       => 500,
        // 'chartOptions' => [
        //     'language'       => 'ru',
        //     "type"           => "serial",
        //     "theme"          => "light",
        //     "legend"         => [
        //         "useGraphSettings" => true
        //     ],
        //     "valueAxes"      => [
        //         [
        //             "id"            => "v2",
        //             "axisColor"     => "#0000ff",
        //             "axisThickness" => 2,
        //             "gridAlpha"     => 0,
        //             "axisAlpha"     => 1,
        //             "position"      => "right"
        //         ],
        //     ],
        //     "graphs"         => [
        //         [
        //             "valueAxis"             => "v2",
        //             "lineColor"             => "#0010ff",
        //             "bullet"                => "round",
        //             "bulletBorderThickness" => 1,
        //             "hideBulletsCount"      => 30,
        //             "title"                 => "синий",
        //             "valueField"            => "blue"
        //         ],
        //     ],
        //     "chartScrollbar" => [],
        //     "chartCursor"    => [
        //         "cursorPosition" => "mouse"
        //     ],
        //     "categoryField"  => "date",
        //     "categoryAxis"   => [
        //         "parseDates"       => true,
        //         "axisColor"        => "#DADADA",
        //         "minorGridEnabled" => true
        //     ],
        //     "export"         => [
        //         "enabled"  => true,
        //         "position" => "bottom-right"
        //     ]
        // ],
    // ]) ?>
<?//= \cs\Widget\ChartJs\Line::widget([
   // 'width'     => 800,
    //'lineArray' => $lineArrayBlue,
   // 'colors' => [$colorBlue],
//]) ?>

<h2 class="page-header">Прогноз (кр+син)</h2>
<?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 500,
        'chartOptions' => [
             'series' => [
                [
                    'type' => 'LineSeries',
                    'yAxis' => 'v2',
                    'stroke' => '#ff1000',
                    'strokeWidth' => 2,
                    'name' => 'Прогноз (красный)',
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
                    'name' => 'Прогноз (синий RPO)',
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
                ]
            ],
            'xAxes' => [[
            	'renderer'=>[
                	'grid'=>[
	            		'strokeWidth' => 0
	            	],
                ],
            	'start' => 0.95,
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
	            ]
	        ],
            'data'    => $lineArrayUnion,
            'legend'       => []
        ],
    ]) ?>
 <?//= \cs\Widget\AmCharts\CandleStick::widget([
            //     'enableExport' => true,
            //     'lineArray'    => $lineArrayUnion,
            //     'height'       => 500,
            //     'chartOptions' => [
            //         'language'       => 'ru',
            //         "type"           => "serial",
            //         "theme"          => "light",
            //         "legend"         => [
            //             "useGraphSettings" => true
            //         ],
            //         "valueAxes"      => [
            //             [
            //                 "id"            => "v1",
            //                 "axisColor"     => "#FF0000",
            //                 "axisThickness" => 2,
            //                 "gridAlpha"     => 0,
            //                 "axisAlpha"     => 1,
            //                 "position"      => "left"
            //             ],
            //             [
            //                 "id"            => "v2",
            //                 "axisColor"     => "#0000ff",
            //                 "axisThickness" => 2,
            //                 "gridAlpha"     => 0,
            //                 "axisAlpha"     => 1,
            //                 "position"      => "right"
            //             ],
            //         ],
            //         "graphs"         => [
            //             [
            //                 "valueAxis"             => "v1",
            //                 "lineColor"             => "#FF1000",
            //                 "bullet"                => "round",
            //                 "bulletBorderThickness" => 1,
            //                 "hideBulletsCount"      => 30,
            //                 "title"                 => "красный",
            //                 "valueField"            => "red"
            //             ],
            //             [
            //                 "valueAxis"             => "v2",
            //                 "lineColor"             => "#0010ff",
            //                 "bullet"                => "round",
            //                 "bulletBorderThickness" => 1,
            //                 "hideBulletsCount"      => 30,
            //                 "title"                 => "синий",
            //                 "valueField"            => "blue"
            //             ],
            //         ],
            //         "chartScrollbar" => [],
            //         "chartCursor"    => [
            //             "cursorPosition" => "mouse"
            //         ],
            //         "categoryField"  => "date",
            //         "categoryAxis"   => [
            //             "parseDates"       => true,
            //             "axisColor"        => "#DADADA",
            //             "minorGridEnabled" => true
            //         ],
            //         "export"         => [
            //             "enabled"  => true,
            //             "position" => "bottom-right"
            //         ]
            //     ],
            // ]) ?>
<?//= \cs\Widget\ChartJs\Line::widget([
   // 'width'     => 800,
   // 'lineArray' => $lineArrayUnion,
   // 'colors' => [
   //     $colorRed,$colorBlue
 //   ],
//]) ?>

<h2 class="page-header">Общий</h2>
<?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 500,
        'chartOptions' => [
             'series' => [
                [
                    'type' => 'LineSeries',
                    'yAxis' => 'v2',
                    'stroke' => '#ff1000',
                    'strokeWidth' => 2,
                    'name' => 'Прогноз (красный CLOSE)',
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
                    'name' => 'Прогноз (синий RPO)',
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
                    'name' => 'Курс',
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
            	'start' => 0.95,
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
            'data'    => $lineArrayUnion2,
            'legend'       => []
        ],
    ]) ?>
 <?//= \cs\Widget\AmCharts\CandleStick::widget([
        //     'height'       => 500,
        //     'lineArray'    => $lineArrayUnion2,
        //     'chartOptions' => [
        //         'language'       => 'ru',
        //         "type"           => "serial",
        //         "theme"          => "light",
        //         "legend"         => [
        //             "useGraphSettings" => true
        //         ],
        //         "valueAxes"      => [
        //             [
        //                 "id"            => "v1",
        //                 "axisColor"     => "#FF1000",
        //                 "axisThickness" => 2,
        //                 "gridAlpha"     => 0,
        //                 "axisAlpha"     => 1,
        //                 "position"      => "left"
        //             ],
        //             [
        //                 "id"            => "v2",
        //                 "axisColor"     => "#0010ff",
        //                 "axisThickness" => 2,
        //                 "gridAlpha"     => 0,
        //                 "axisAlpha"     => 1,
        //                 "position"      => "right"
        //             ],
        //             [
        //                 "id"            => "v3",
        //                 "axisColor"     => "#10ff00",
        //                 "axisThickness" => 2,
        //                 "gridAlpha"     => 0,
        //                 "offset"        => 50,
        //                 "axisAlpha"     => 1,
        //                 "position"      => "left"
        //             ]
        //         ],
        //         "graphs"         => [
        //             [
        //                 "valueAxis"             => "v1",
        //                 "lineColor"             => "#FF1000",
        //                 "bullet"                => "round",
        //                 "bulletBorderThickness" => 1,
        //                 "hideBulletsCount"      => 30,
        //                 "title"                 => "красный",
        //                 "valueField"            => "red",
        //                 "fillAlphas"            => 0
        //             ],
        //             [
        //                 "valueAxis"             => "v2",
        //                 "lineColor"             => "#0010ff",
        //                 "bullet"                => "round",
        //                 "bulletBorderThickness" => 1,
        //                 "hideBulletsCount"      => 30,
        //                 "title"                 => "синий",
        //                 "valueField"            => "blue",
        //                 "fillAlphas"            => 0
        //             ],
        //             [
        //                 "valueAxis"             => "v3",
        //                 "lineColor"             => "#10ff00",
        //                 "bullet"                => "round",
        //                 "bulletBorderThickness" => 1,
        //                 "hideBulletsCount"      => 30,
        //                 "title"                 => "зеленый",
        //                 "valueField"            => "kurs",
        //                 "fillAlphas"            => 0
        //             ]
        //         ],
        //         "chartScrollbar" => [],
        //         "chartCursor"    => [
        //             "cursorPosition" => "mouse"
        //         ],
        //         "categoryField"  => "date",
        //         "categoryAxis"   => [
        //             "parseDates"       => true,
        //             "axisColor"        => "#DADADA",
        //             "minorGridEnabled" => true
        //         ],
        //         "export"         => [
        //             "enabled"  => true,
        //             "position" => "bottom-right"
        //         ]
        //     ],
        // ]) ?>
<?//= \cs\Widget\ChartJs\Line::widget([
   // 'width'     => 800,
   // 'lineArray' => $lineArrayUnion2,
   // 'colors' => [
     //  $colorRed,$colorBlue,$colorGreen,
 //   ],
//]) ?>

<h2 class="page-header">Курс</h2>
<?= \cs\Widget\AmCharts\CandleStick::widget([
        'height'       => 500,
        'chartOptions' => [
             'series' => [
                [
                    'type' => 'LineSeries',
                    'stroke' => '#10ff00',
                    'strokeWidth' => 2,
                    'name' => 'Курс',
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
            	'start' => 0.95,
            	'end' => 1,
            	'keepSelection' =>true,
                'type' => 'CategoryAxis',
                'dataFields' => [
                    'category' => 'date'
                ]
            ]],
            'yAxes' => [[
                'type' => 'ValueAxis',
                'renderer'=>[
                	'grid'=>[
	            		'strokeWidth' => 0
	            	],
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
            ]],
            'data'    => $lineArrayKurs,
            'legend'       => []
        ],
    ]) ?>
 <?//= \cs\Widget\AmCharts\CandleStick::widget([
        //     'height'       => 500,
        //     'lineArray'    => $lineArrayKurs,
        //     'chartOptions' => [
        //         'language'       => 'ru',
        //         "type"           => "serial",
        //         "theme"          => "light",
        //         "legend"         => [
        //             "useGraphSettings" => true
        //         ],
        //         "valueAxes"      => [
        //             [
        //                 "id"            => "v3",
        //                 "axisColor"     => "#10ff00",
        //                 "axisThickness" => 2,
        //                 "gridAlpha"     => 0,
        //                 "offset"        => 50,
        //                 "axisAlpha"     => 1,
        //                 "position"      => "left"
        //             ]
        //         ],
        //         "graphs"         => [
        //             [
        //                 "valueAxis"             => "v3",
        //                 "lineColor"             => "#10ff00",
        //                 "bullet"                => "round",
        //                 "bulletBorderThickness" => 1,
        //                 "hideBulletsCount"      => 30,
        //                 "title"                 => "зеленый",
        //                 "valueField"            => "kurs",
        //                 "fillAlphas"            => 0
        //             ]
        //         ],
        //         "chartScrollbar" => [],
        //         "chartCursor"    => [
        //             "cursorPosition" => "mouse"
        //         ],
        //         "categoryField"  => "date",
        //         "categoryAxis"   => [
        //             "parseDates"       => true,
        //             "axisColor"        => "#DADADA",
        //             "minorGridEnabled" => true
        //         ],
        //         "export"         => [
        //             "enabled"  => true,
        //             "position" => "bottom-right"
        //         ]
        //     ],
        // ]) ?>
<?php

//$graph3 = new \cs\Widget\ChartJs\Line([
   // 'width'     => 800,
   // 'lineArray' => $lineArrayKurs,
   // 'colors' => [
   //     $colorGreen,
  //  ],
//]);
//echo $graph3->run();
// $url = Url::to(['cabinet/graph_ajax']);
// $this->registerJs(<<<JS
//     $('#buttonRecalculate').click(function() {
//         if ($('#stockitemgraph-datemin').val() == '') {
//             showInfo('Нужно заполнить дату начала');
//             return;
//         }
//         if ($('#stockitemgraph-datemax').val() == '') {
//             showInfo('Нужно заполнить дату начала');
//             return;
//         }
//         {$graph3->varName}.destroy();
//         var start = $('#stockitemgraph-datemin').val();
//         var end = $('#stockitemgraph-datemax').val();
//         start = start.substring(6,10) + '-' + start.substring(3,5) + '-' + start.substring(0,2);
//         end = end.substring(6,10) + '-' + end.substring(3,5) + '-' + end.substring(0,2);
//         ajaxJson({
//             url: '$url',
//             data: {
//                 'min': start,
//                 'max': end,
//                 'id': {$item->getId()},
//                 'isUseRed': $('#stockitemgraph-isusered').is(':checked')? 1: 0,
//                 'isUseBlue': $('#stockitemgraph-isuseblue').is(':checked')? 1: 0,
//                 'isUseKurs': $('#stockitemgraph-isusekurs').is(':checked')? 1: 0,
//                 'y': $('#stockitemgraph-y').val()
//             },
//             success: function(ret) {
//                 {$graph3->varName} = new Chart(document.getElementById('$graph3->id').getContext('2d')).Line(ret, []);
//             }
//         })
//     })
// JS
// );
?>

<?php if (!$isPaid) { ?>
    <hr>

    <a
        href="<?= Url::to(['cabinet_wallet/add', 'id' => $item->getId()]) ?>"
        class="btn btn-default"
        style="width: 100%;"
        >Купить</a>
<?php } ?>

<!-- <div class="row hidden-print">
    <div class="col-lg-8"> -->
        <?php //$form = ActiveForm::begin(['id' => 'contact-form']); ?>
        <?//= $model->field($form, 'dateMin') ?>
        <?//= $model->field($form, 'dateMax') ?>
        <?//= $model->field($form, 'isUseRed') ?>
        <?//= $model->field($form, 'isUseBlue') ?>
        <?//= $model->field($form, 'isUseKurs') ?>
        <?//= $model->field($form, 'y')->dropDownList([
          //  1 => 'Курс',
          //  2 => 'Красный прогноз',
          //  3 => 'Синий прогноз',
        //]); ?>
        <!-- <hr>
        <div class="form-group"> -->
            <?//= Html::button('Показать', [
                //'class' => 'btn btn-default',
               // 'name'  => 'contact-button',
               // 'style' => 'width:100%',
              //  'id'    => 'buttonRecalculate',
           // ]) ?>
        <!-- </div> -->
        <?php // ActiveForm::end(); ?>
    <!-- </div>
</div> -->



<!--<h2 class="page-header">Экспорт</h2>-->


<!--<div class="col-lg-6 row">-->
<!--    <div style="margin: 10px 0px 20px 0px;">-->
<!--        <div id="slider"></div>-->
<!--    </div>-->
<!--    <button class="btn btn-default" style="width: 100%;">Экспортировать</button>-->
<!--</div>-->
