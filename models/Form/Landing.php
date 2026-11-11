<?php

namespace app\models\Form;

use Yii;
use cs\Widget\FileUpload2\FileUpload;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class Landing extends \cs\base\BaseForm
{
    const TABLE = 'cap_design';

    public $id;
    public $html;
    public $img1;
    public $img2;
    public $img3;

    function __construct($fields = [])
    {
        static::$fields = [
            [
                'html',
                'HTML',
                0,
                'string'
            ],
            [
                'img1',
                'Картинка 1',
                0,
                'string',
                [],
                '1900x1080 JPG',
                'widget' => [
                    FileUpload::className(),
                    [
                        'options' => [
                            'small' => [200,200]
                        ]
                    ]
                ]
            ],
            [
                'img2',
                'Картинка 2',
                0,
                'string',
                [],
                '1900x1080 JPG',
                'widget' => [
                    FileUpload::className(),
                    [
                        'options' => [
                            'small' => [200,200]
                        ]
                    ]
                ]
            ],
            [
                'img3',
                'Картинка 3',
                0,
                'string',
                [],
                '1900x1080 JPG',
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
