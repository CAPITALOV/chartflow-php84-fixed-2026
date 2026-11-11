<?php

namespace app\models\Form;

use Yii;

/**
 */
class StockItemGraph extends \cs\base\BaseForm
{
    const TABLE = 'cap_stock';

    public $dateMin;
    public $dateMax;
    public $isUseRed = true;
    public $isUseBlue = true;
    public $isUseKurs = true;
    public $y;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'dateMin',
                'От',
                1,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'dateMax',
                'До',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'isUseRed',
                'Использовать красный график',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => ['cs\Widget\CheckBox2\CheckBox',
                ]
            ],
            [
                'isUseBlue',
                'Использовать синий график',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => ['cs\Widget\CheckBox2\CheckBox',
                ]
            ],
            [
                'isUseKurs',
                'Использовать курс',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => ['cs\Widget\CheckBox2\CheckBox',
                ]
            ],
            [
                'y',
                'Какую шкалу использовать?',
                0,
                'integer',
            ],
        ];
        parent::__construct($fields);
    }
}
