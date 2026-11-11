<?php

/**
 * Класс для действий Superadmin
 *
 */

namespace app\controllers;

use app\models\LoginForm;
use app\models\Registration;
use app\models\Request;
use app\models\Stock;
use app\models\StockKurs;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use app\models\User;
use app\models\UserStock;
use cs\services\VarDumper;
use Yii;
use app\service\CalculatingProbability;

class SuperadminController extends SuperadminBaseController
{
    public function actionIndex()
    {
        return $this->redirect(['superadmin_stock/index']);
    }

    public function actionReferal()
    {
        return $this->render();
    }

    public function actionStock_calc()
    {
        return $this->render([]);
    }

    /**
     * Публикует котировку
     * Все оплаченные заказы активируются
     * AJAX
     *
     * REQUEST:
     * - id - int -           идентификатор котировки
     *
     * @return string
     */
    public function actionStock_calc_activate()
    {
        $stock_id = self::getParam('id');
        $stock = Stock::find($stock_id);
        $requestList = Request::query([
            'stock_id' => $stock->getId(),
            'is_paid'  => 1,
        ])->all();
        foreach ($requestList as $request) {
            $requestObject = new Request($request);
            $requestObject->activate();
        }
        $stock->setStatus(Stock::STATUS_READY);

        return self::jsonSuccess();
    }

    /**
     * Удаление информации о регистрации по реферальной ссылке
     *
     * REQUEST:
     * - id - int - идентификатор cap_registration.id
     *
     * @return string
     */
    public function actionReferal_delete()
    {
        $id = self::getParam('id');
        $item = Registration::find($id);
        if (is_null($item)) {
            return self::jsonError('Нет такой записи');
        }
        $item->delete();

        return self::jsonSuccess();
    }

    public function actionCalc()
    {
        $blue = CalculatingProbability::initStock(1, 2);
        $red = CalculatingProbability::initStock(1, 1);
        VarDumper::dump([$blue->calc(), $red->calc()]);
    }

    /**
     * Все пользователи
     *
     * @return string
     */
    public function actionUsers()
    {
        return $this->render([
            'query' => User::query()
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['superadmin/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['superadmin/index']);
        }
        else {
            return $this->render([
                'model' => $model,
            ]);
        }
    }


    /**
     * Показывает "Текущие заказы пользователей"
     */
    public function actionUsers_stock()
    {
        $users = UserStock::query()
            ->select([
                'cap_users_stock_buy.user_id',
                'cap_users.name_first',
                'cap_users.name_last',
                'cap_users.email',
                'cap_users.avatar',
            ])
            ->groupBy(['cap_users_stock_buy.user_id'])
            ->innerJoin('cap_users', 'cap_users.id = cap_users_stock_buy.user_id')
            ->all();

        for ($i = 0; $i < count($users); $i++) {
            $item = &$users[$i];

            $item['stockList'] = UserStock::query()
                ->select([
                    'cap_stock.id',
                    'cap_stock.name',
                    'cap_stock.logo',
                    'cap_stock.description',
                    'cap_users_stock_buy.date_finish',
                ])
                ->andWhere(['cap_users_stock_buy.user_id' => $item['user_id']])
                ->andWhere(['>', 'cap_users_stock_buy.date_finish', Yii::$app->formatter->asDate(time(), 'php:Y-m-d')])
                ->innerJoin('cap_stock', 'cap_stock.id = cap_users_stock_buy.stock_id')
                ->orderBy(['cap_stock.name' => SORT_ASC])
                ->all();
        }

        return $this->render([
            'users'  => $users,
        ]);
    }
}
