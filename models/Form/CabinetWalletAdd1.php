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
class CabinetWalletAdd1 extends \cs\base\BaseForm
{
    public $monthCounter;

    /** @var  int идентификатор катировки */
    public $stockId;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'monthCounter',
                'Количество месяцев',
                1,
                'integer',
            ],
            [
                'stockId',
                'Котировка',
                0,
                'string',
            ],
        ];
        parent::__construct($fields);
    }
}
