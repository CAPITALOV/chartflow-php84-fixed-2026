<?php

namespace app\models\Form;

use app\models\NewsItem;
use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\StockPrognosisRed;
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
 */
class StockPrognosisRedDelete extends \cs\base\BaseForm
{
    const TABLE = 'cap_stock_prognosis_red';

    /** @var  \DateTime */
    public $dateMin;

    /** @var  \DateTime */
    public $dateMax;

    function __construct($fields = [])
    {
        $year = date('Y');
        $range = ($year - 9) . ':' . ($year + 9);
        static::$fields = [
            [
                'dateMin',
                'Начало',
                1,
                'validateDates',
                'widget' => ['cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                        'clientOptions' => [
                            'yearRange' => $range
                        ],
                    ]
                ]
            ],
            [
                'dateMax',
                'Окончание',
                1,
                'validateDates',
                'widget' => ['cs\Widget\DatePicker\DatePicker',
                    [
                        'dateFormat' => 'php:d.m.Y',
                        'clientOptions' => [
                            'yearRange' => $range
                        ],
                    ]
                ]
            ],
        ];
        parent::__construct($fields);
    }

    public function validateDates($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (is_null($this->dateMin)) {
                $this->addError('dateMin', 'Дата не может быть пустой');
                return;
            }
            if (is_null($this->dateMax)) {
                $this->addError('dateMax', 'Дата не может быть пустой');
                return;
            }
            if (!is_null($this->dateMin) and !is_null($this->dateMax)) {
                if ($this->dateMax->format('U') - $this->dateMin->format('U') < 0) {
                    $this->addError($attribute, 'Дата начала не может быть больше конца');
                }
            }
        }
    }

    public function remove($id)
    {
        if ($this->validate()) {
            StockPrognosisRed::deleteByCondition(
                "(`date` BETWEEN :dateMin and :dateMax) and (`stock_id` = :stock_id)", [
                    ':stock_id' => $id,
                    ':dateMin' => $this->dateMin->format('Y-m-d'),
                    ':dateMax' => $this->dateMax->format('Y-m-d'),
                ]
            );

            return true;
        } else {
            return false;
        }

    }
}
