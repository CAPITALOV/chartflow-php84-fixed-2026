<?php

namespace app\controllers;


use app\models\Stock;
use app\models\StockMarket;
use cs\services\VarDumper;

class LandingController extends \cs\base\BaseController
{
    public $layout = 'landing';

    public function actionIndex()
    {
        $this->layout = 'blank';


        return $this->render('cap');
    }
} 