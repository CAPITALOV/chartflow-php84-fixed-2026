<?php

namespace app\controllers;

use app\models\Registration;
use app\models\Request;
use app\models\Stock;
use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\UserException;

class Superadmin_requestsController extends SuperadminBaseController
{

    public function actionIndex()
    {
        $items = Request::query()
            ->select([
                'cap_requests.*',
                'cap_users.avatar      as cap_users_avatar',
                'cap_users.name_first  as cap_users_name_first',
                'cap_users.name_last   as cap_users_name_last',
                'cap_users.email       as cap_users_email',
                'cap_stock.name        as cap_stock_name',
                'cap_stock.logo        as cap_stock_logo',
            ])
            ->innerJoin('cap_users', 'cap_users.id = cap_requests.user_id')
            ->innerJoin('cap_stock', 'cap_stock.id = cap_requests.stock_id')
            ->orderBy(['datetime' => SORT_DESC])
            ->all();

        return $this->render([
            'items' => $items,
        ]);
    }

    /**
     * Активирует услугу по прямой ссылке
     */
    public function actionActivate($hash)
    {
        $request = Request::find(['hash' => $hash]);
        if (is_null($request)) {
            throw new Exception('Нет такого кода или заявка уже активирована');
        }

        $return = $this->activate($request);

        return $this->render($return);
    }

    /**
     * @param \app\models\Request $request
     *
     * @return null|array
     * [
     *   'stock'      => \app\models\Stock
     *   'dateFinish' => string - дата до которого оплачена услуга в формате 'yyyy-mm-dd'
     * ]
     */
    private function activate($request)
    {
        $stock = Stock::find($request->getField('stock_id'));
        // ставлю флаг что заказ оплачен
        $request->update(['is_paid' => 1]);

        // ставлю флаг для пользователя если он регистрировался по реферальной ссылке
        {
            $r = Registration::find(['user_id' => $request->getField('user_id')]);
            if ($r) {
                $r->update(['is_paid' => 1]);
            }
        }

        if ($stock->getStatus() == Stock::STATUS_READY) {
            return $request->activate();
        }

        return null;
    }

    /**
     * AJAX
     * Активирует запрос
     *
     * REQUEST:
     * - id - int - идентификатор заказа
     */
    public function actionActivate_ajax()
    {
        $request = Request::find(self::getParam('id'));
        if (is_null($request)) {
            return self::jsonError('Нет такого запроса');
        }
        $this->activate($request);

        return self::jsonSuccess();
    }

    /**
     * AJAX
     * Удаляет запрос
     */
    public function actionDelete_ajax()
    {
        $request = Request::find(self::getParam('id'));
        if (is_null($request)) {
            return self::jsonError('Нет такого запроса');
        }
        $request->delete();

        return self::jsonSuccess();
    }


}
