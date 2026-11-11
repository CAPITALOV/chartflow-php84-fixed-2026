<?php

namespace app\models\Form;

use app\models\User;
use app\service\EmailChangeDispatcher;
use cs\Application;
use cs\base\BaseForm;
use cs\services\dispatcher\EmailChange;
use cs\web\Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 *
 */
class NewsItem extends BaseForm
{
    const TABLE = 'cap_news';

    public $id;
    public $name;
    public $content;
    public $datetime;

    function __construct($fields = [])
    {

        static::$fields = [
            ['name', 'Заголовок', 1, 'string'],
            [
                'content',
                'Содержание',
                0,
                'string',
                'widget' => [
                    'cs\Widget\HtmlContent\HtmlContent',
                    [
                    ]
                ]
            ],
        ];
        parent::__construct($fields);
    }

    public function insert($fields = null)
    {
        return parent::insert([
            'beforeInsert' => function ($fields) {
                $fields['datetime'] = date('Y-m-d H:i:s', time());

                return $fields;
            }
        ]);
    }
}