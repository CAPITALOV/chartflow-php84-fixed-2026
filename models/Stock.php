<?php

namespace app\models;

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
 */
class Stock extends \cs\base\DbRecord
{
    const TABLE = 'cap_stock';

    const STATUS_NO_CALC     = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_READY       = 2;

    public function getStatus()
    {
        return $this->getField('status', 0);
    }

    public function getName()
    {
        return $this->getField('name', '');
    }

    public function getImage()
    {
        return $this->getField('logo', '');
    }

    /**
     * @param int $status \app\models\Stock::STATUS_*
     *
     * @return mixed
     */
    public function setStatus($status)
    {
        return $this->update(['status' => $status]);
    }

    /**
     * Оплачена ли котировка?
     *
     * @return bool
     */
    public function isPaid()
    {
        return UserStock::query([
            'stock_id' => $this->getId(),
            'user_id'  => \Yii::$app->user->id,
        ])
            ->andWhere(['>', 'date_finish', date('Y-m-d')])
            ->exists();
    }

    /**
     * Возвращает акции которые оплаченые
     *
     * @return \yii\db\Query
     */
    public static function getPaid()
    {
        return self::query()
            ->select([
                'cap_stock.id',
                'cap_stock.name',
                'cap_stock.logo',
                'cap_stock.description',
            ])
            ->innerJoin('cap_users_stock_buy', 'cap_users_stock_buy.stock_id = cap_stock.id')
            ->andWhere(['cap_users_stock_buy.user_id' => Yii::$app->user->id])
            ->orderBy(['cap_stock.name' => SORT_ASC]);
    }

    /**
     * Возвращает заготовку Query для создания запросов
     *
     * @param $condition
     * @param $params
     *
     * @return Query
     */
    public static function query($condition = null, $params = null)
    {
        return parent::query($condition, $params)->andWhere(['is_enabled' => 1]);
    }

    /**
     * Возвращает акции которые оплачены
     *
     * @return \yii\db\Query
     */
    public static function getNotPaid()
    {
        return self::query()
            ->select([
                'cap_stock.id',
                'cap_stock.name',
                'cap_stock.logo',
                'cap_stock.description',
            ])
            ->leftJoin('cap_users_stock_buy', 'cap_users_stock_buy.stock_id = cap_stock.id and cap_users_stock_buy.user_id = :uid', [':uid' => Yii::$app->user->id])
            ->orderBy(['cap_stock.name' => SORT_ASC])
            ->where(['cap_users_stock_buy.stock_id' => null]);
    }
}
