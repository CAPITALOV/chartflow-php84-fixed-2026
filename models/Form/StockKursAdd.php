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
 * ContactForm is the model behind the contact form.
 */
class StockKursAdd extends \cs\base\BaseForm
{
    const TABLE = 'cap_stock_kurs';

    public $stock_id;
    public $date1;
    public $date2;
    public $date3;
    public $date4;
    public $date5;
    public $date6;
    public $date7;
    public $date8;
    public $date9;
    public $date10;
    public $kurs1;
    public $kurs2;
    public $kurs3;
    public $kurs4;
    public $kurs5;
    public $kurs6;
    public $kurs7;
    public $kurs8;
    public $kurs9;
    public $kurs10;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'date1',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date2',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date3',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date4',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date5',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date6',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date7',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date8',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date9',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'date10',
                'Дата',
                0,
                'cs\Widget\DatePicker\Validator',
                'widget' => ['\cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                    ]
                ]
            ],
            [
                'kurs1',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs2',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs3',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs4',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs5',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs6',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs7',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs8',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs9',
                'Курс',
                0,
                'double',
            ],
            [
                'kurs10',
                'Курс',
                0,
                'double',
            ],
            [
                'stock_id',
                'Курс',
                0,
                'integer',
            ],
        ];
        parent::__construct($fields);
    }

    public function insert()
    {
        for ($c = 1; $c <= 10; $c++) {
            $fieldName = 'date' . $c;
            $date = $this->$fieldName;
            $fieldName = 'kurs' . $c;
            $kurs = $this->$fieldName;
            if ($date != '' && $kurs != '') {
                StockKurs::insert([
                    'stock_id' => $this->stock_id,
                    'date'     => $date->format('Ymd'),
                    'kurs'     => $kurs,
                ]);
            }
        }

        return true;
    }
}
