<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 16.09.2015
 * Time: 21:59
 */

namespace cs\Widget\AmCharts;


use yii\base\BaseObject;
use yii\helpers\Html;
use yii\helpers\Json;

class Graph1 extends BaseObject
{
    public $id;
    public $width  = 400;
    public $height = 400;

    /** @var  string название переменной JS */
    public $varName;
    /**
     * @var array
     * [
     *     'x' => ["January", "February", "March", "April", "May", "June", "July"],
     *     'y' => [
     *               [65, 59, 80, 81, 56, 55, 40],
     *               [null, null, 48, 40, 19, 86, 27],
     *            ]
     * ]
     */
    public $lineArray;
    public $data;

    public $chartOptions  = [];
    public $globalOptions = [
        'animation' => false,
    ];

    public function run()
    {
        $this->registerClientScript();
        $this->getClientOptions();

        return Html::tag('div', Html::tag('div', Html::tag('div', 'Loading data...', [
            'class' => 'loading-data',
        ]), [
            'id' => 'chartdiv',
        ]), [
            'id' => 'chartwrapper',
        ]);
    }

    public static function widget($config = [])
    {
        $class = new self($config);
        return $class->run();
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        Asset::register(\Yii::$app->view);
        // \Yii::$app->view->registerJsFile('/amChart/default.js', ['depends' => 'cs\Widget\AmCharts\Asset']);
        // \Yii::$app->view->registerCssFile('/amChart/default.css');
    }

    protected function getClientOptions()
    {
//        $optionsJson = Json::encode($this->globalOptions);
//        if (count($this->globalOptions) > 0){
//            $js[] = "Chart.defaults.global = $.extend(Chart.defaults.global, {$optionsJson});";
//        }
//        $optionsJson = Json::encode($this->chartOptions);
//        $dataJson = Json::encode($this->getData());
//        $js[] = <<<JS
//var {$this->varName} = new Chart(document.getElementById('{$this->id}').getContext('2d')).Line({$dataJson}, {$optionsJson});
//JS
//        ;
//        \Yii::$app->view->registerJs(join("\n", $js));
    }
}