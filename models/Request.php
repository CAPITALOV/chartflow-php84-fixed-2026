<?php

namespace app\models;

use app\models\NewsItem;
use app\models\User;
use app\services\GsssHtml;
use cs\Application;
use cs\services\Security;
use cs\services\Str;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\Model;
use cs\Widget\FileUpload2\FileUpload;
use yii\db\Query;
use yii\helpers\Html;

/**
 * Запрос на добавление услуги
 */
class Request extends \cs\base\DbRecord
{
    const TABLE = 'cap_requests';

    /**
     * Добавляет запрос на добавление услуги
     *
     * @param array $fields
     *    [
     *    'stock_id' => int
     *    'month'    => int
     *    ]
     *
     * @return static
     */
    public static function insert($fields)
    {
        $fields['user_id'] = Yii::$app->user->id;
        $fields['datetime'] = time();
        $fields['hash'] = Security::generateRandomString(60);

        return parent::insert($fields);
    }

    public function getUserId()
    {
        return $this->getField('user_id');
    }

    /**
     * Актвирует услугу
     * Добавляет купленное количество месяцев
     * Добавляет запись в историю покупок
     * Высылает письмо о готовности заказа
     *
     * @return array
     * [
     *   'stock'      => \app\models\Stock
     *   'dateFinish' => string - дата до которого оплачена услуга в формате 'yyyy-mm-dd'
     * ]
     */
    public function activate()
    {
        $stock_id = $this->getField('stock_id');
        $stock = Stock::find($stock_id);
        $monthCounter = $this->getField('month');
        $dateFinish = \app\models\UserStock::add($this->getUserId(), $stock_id, $monthCounter, $this->getField('datetime'));
        $dateFinishStr = Yii::$app->formatter->asDate($dateFinish);
        WalletHistory::insert("Услуга включена, котировка: {$stock->getName()}, месяцев: {$monthCounter}, до: {$dateFinishStr}");
        $this->delete();
        // Высылаю письмо клиенту что ваш заказ готов и можете пользоваться
        Application::mail(User::find($this->getUserId())->getEmail(), 'Ваш заказ готов и можете пользоваться', 'request_ready', [
            'request' => $this,
        ]);

        return [
            'stock'      => $stock,
            'dateFinish' => $dateFinish,
        ];
    }
}
