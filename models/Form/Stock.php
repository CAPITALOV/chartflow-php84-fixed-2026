<?php

namespace app\models\Form;

use app\models\NewsItem;
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
class Stock extends \cs\base\BaseForm
{
    const TABLE = 'cap_stock';

    public $id;
    public $name;
    public $logo;
    public $description;
    public $finam_em;
    public $finam_market;
    public $finam_code;
    public $status;
    public $is_kurs;
    public $is_enabled;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'name',
                'Название',
                1,
                'string'
            ],
            [
                'description',
                'Описание',
                0,
                'string',
                [],
                'до 255 символов'
            ],
            // [
            //     'finam_em',
            //     'Идентификатор котировки по Finam',
            //     0,
            //     'integer',
            // ],
            [
                'finam_code',
                // 'CODE по Finam',
                'CODE по Marketstack',
                0,
                'string',
            ],
            [
                'finam_market',
                'Идентификатор рынка',
                0,
                'integer',
            ],
            [
                'is_enabled',
                'Действует?',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => [
                    'cs\Widget\CheckBox2\CheckBox', []
                ]
            ],
            [
                'logo',
                'Картинка',
                0,
                'string',
                'widget' => [
                    FileUpload::className(),
                    [
                        'options' => [
                            'small' => [200,200]
                        ]
                    ]
                ]
            ],
        ];
        parent::__construct($fields);
    }
}
