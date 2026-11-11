<?php

namespace app\models\Form;

use app\models\NewsItem;
use app\models\Request;
use app\models\Stock;
use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\User;
use app\models\WalletHistory;
use app\services\GsssHtml;
use cs\Application;
use cs\services\Security;
use cs\services\Str;
use cs\services\VarDumper;
use Yii;
use yii\base\Model;
use cs\Widget\FileUpload2\FileUpload;
use yii\db\Query;
use yii\helpers\Html;

/**
 * ContactForm is the model behind the contact form.
 */
class CabinetWalletAdd extends \cs\base\BaseForm
{
    public $monthCounter;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'monthCounter',
                'Количество месяцев',
                1,
                'integer',
            ],
        ];
        parent::__construct($fields);
    }

    public function add($stock_id)
    {
        if ($this->validate()) {
            $stock = Stock::find($stock_id);
            $request = Request::insert([
                'stock_id' => $stock_id,
                'month'    => $this->monthCounter,
            ]);

            Application::mail(User::find(Yii::$app->params['chat']['consultant_id'])->getEmail(), 'Запрос на добавление услуги', 'request', [
                'stock'    => $stock,
                'user'     => \Yii::$app->user->identity,
                'request'  => $request,
            ]);

            return true;
        } else {
            return false;
        }
    }
}
