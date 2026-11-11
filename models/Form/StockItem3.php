<?php

namespace app\models\Form;

use app\models\NewsItem;
use app\models\StockKurs;
use app\models\User;
use app\services\GsssHtml;
use cs\services\Str;
use cs\services\VarDumper;
use Yii;
use yii\base\Model;
use cs\Widget\FileUpload2\FileUpload;
use yii\db\Query;
use yii\helpers\Html;

/**
 *
 *
 */
class StockItem3 extends \cs\base\BaseForm
{
    public $isRed = true;
    public $isBlue = true;
    public $isKurs = true;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'isRed',
                'Есть красный',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => ['cs\Widget\CheckBox2\CheckBox',
                    [
                    ]
                ]
            ],
            [
                'isBlue',
                'Есть синий',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => ['cs\Widget\CheckBox2\CheckBox',
                    [
                    ]
                ]
            ],
            [
                'isGreen',
                'Есть курс',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => ['cs\Widget\CheckBox2\CheckBox',
                    [
                    ]
                ]
            ],

        ];

        parent::__construct($fields);
    }
}
