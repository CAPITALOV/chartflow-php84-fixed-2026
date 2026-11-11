<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 16.09.2015
 * Time: 21:59
 */

namespace cs\Widget\AmCharts;

use cs\services\Security;
use yii\base\BaseObject;
use yii\helpers\Html;
use yii\helpers\Json;

class CandleStick extends BaseObject
{
    public $id;
    public $width  = 400;
    public $height = 200;

    public $enableExport;

    /** @var  string название переменной JS */
    public $varName;
    /**
     * @var array
     * [[
     *     'date' => 'yyyy-mm-dd'
     *     'open' => float
     *     'high' => float
     *     'low' => float
     *     'close' => float
     * ],...]
     */
    public $lineArray;

    /**
     * @var \yii\web\JsExpression дополнительный js
     */
    public $js;

    public $chartOptions  = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        if (!$this->id) {
            $this->id = 'w_' . Security::generateRandomString();
        }
        $this->varName = 'graph_' . Security::generateRandomString();
        // $this->enableExport = \yii\helpers\ArrayHelper::getValue($this->chartOptions, 'export.enabled', false);
    }

    public function run()
    {
        $this->registerClientScript();


        return Html::tag('div', null, [
            'id' => $this->id,
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
        $am = Asset::register(\Yii::$app->view);
        if ($this->js) {
            \Yii::$app->view->registerJs($this->js);
        }
        // if ($this->enableExport) {
        //     \Yii::$app->view->registerJsFile($am->baseUrl . '/plugins/export/export.js', [
        //         'depends' => ['cs\Widget\AmCharts\Asset'],
        //     ]);
        //     \Yii::$app->view->registerCssFile($am->baseUrl . '/plugins/export/export.css');
        // }

        $optionsJson = $this->getClientOptions($am);
        \Yii::$app->view->registerJs(<<<JS
am4core.useTheme(am4themes_animated);
var {$this->varName} = am4core.createFromConfig( {$optionsJson}, "{$this->id}", "XYChart" );
{$this->varName}.language.locale = am4lang_ru_RU;
{$this->varName}.exporting.menu = new am4core.ExportMenu();
{$this->varName}.cursor = new am4charts.XYCursor();

{$this->varName}.scrollbarX = new am4charts.XYChartScrollbar();
{$this->varName}.scrollbarX.parent = {$this->varName}.topAxesContainer;
JS
);
//, ($this->enableExport)? \yii\web\View::POS_HEAD : \yii\web\View::POS_READY);
        \Yii::$app->view->registerCss(<<<CSS
#{$this->id} {
	width	: 100%;
	height	: {$this->height}px;
}
CSS
);
    }

    /**
     * @param \yii\web\AssetBundle $am
     *
     * @return string json
     */
    protected function getClientOptions($am)
    {
        $options = $this->chartOptions;
        
        if (!isset($options['data'])) {
            $options['data'] = $this->lineArray;
        }

        return Json::encode($options);
    }
}