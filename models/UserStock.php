<?php

namespace app\models;

use cs\services\VarDumper;
use Yii;

/**
 * Обслуживает таблицу cap_users_stock_buy, которая хранит информацию до какого времени была оплачена акция конкретного пользователя
 */
class UserStock extends \cs\base\DbRecord
{
    const TABLE = 'cap_users_stock_buy';

    public static function get2($userId, $stockId)
    {
        return self::find([
            'user_id'  => $userId,
            'stock_id' => $stockId,
        ]);
    }

    /**
     * Возвращает временную метку до какого времени проплачена акция
     *
     * @param int $userId
     * @param int $stockId
     *
     * @return integer|false
     * false - если нет значения (не найдено)
     */
    public static function getDateFinish($userId, $stockId)
    {
        return self::query([
            'user_id'  => $userId,
            'stock_id' => $stockId,
        ])->select(['date_finish'])->scalar();
    }

    /**
     * Оплачена акиця на настоящий момент?
     *
     * @return bool
     */
    public function isPaid()
    {
        $datetime = $this->getField('date_finish', false);
        if ($datetime === false) return false;

        $nextMonth = self::addMonthCounter($datetime, 1);
        $now = \Yii::$app->formatter->asDate(time(), 'php:Y-m-d');

        return (self::dateCompare($nextMonth, $now) > 0);
    }

    /**
     * Оплачена акиця на настоящий момент?
     *
     * @param int $userId
     * @param int $stockId
     *
     * @return bool
     */
    public static function isPaidStatic($userId, $stockId)
    {
        $datetime = self::getDateFinish($userId, $stockId);
        if ($datetime === false) return false;
        $now = \Yii::$app->formatter->asDate(time(), 'php:Y-m-d');

        return (self::dateCompare($datetime, $now) > 0);
    }

    /**
     * Добавляет количество оплаченных месяцев
     *
     * @param int $userId       идентификатор пользователя
     * @param int $stockId      идентификатор акции
     * @param int $monthCounter количество месяцев которые надо добавить
     *
     * @return string дату до какого было оплачено в формате 'yyyy-mm-dd'
     *
     * @throws \yii\db\Exception
     */
    public static function add($userId, $stockId, $monthCounter, $timeStart = null)
    {
        if (is_null($timeStart)) {
            $timeStart = time();
        }
        $item = self::find([
            'stock_id' => $stockId,
            'user_id'  => $userId,
        ]);
        $dateFinish = '';
        if (is_null($item)) {
            $dateFinish = self::addMonthCounter(\Yii::$app->formatter->asDate($timeStart, 'php:Y-m-d'), $monthCounter + 1);
            self::insert([
                'stock_id'    => $stockId,
                'user_id'     => $userId,
                'date_finish' => $dateFinish,
            ]);
        } else {
            if ($item->isPaid()) {
                $dateFinish = self::addMonthCounter($item->getField('date_finish'), $monthCounter);
                $item->update([
                    'date_finish' => $dateFinish,
                ]);
            } else {
                $dateFinish = self::addMonthCounter(\Yii::$app->formatter->asDate($timeStart, 'php:Y-m-d'), $monthCounter + 1);
                $item->update([
                    'date_finish' => $dateFinish,
                ]);
            }
        }

        return $dateFinish;
    }

    /**
     * Прибавляет к текущей дате $monthCounter оплаченных месяцев
     * оплаченные месяца идут с начала следующего календарного месяца
     * Возвращаемая дата содержит первый день месяца сдедующего за последним оплаченым
     *
     * @param string  $date формат 'yyyy-mm-dd'
     * @param int $monthCounter
     *
     * @return string дата содержащая год и месяц до которого оплачен курс, формат 'yyyy-mm-dd'
     */
    public static function addMonthCounter($date, $monthCounter)
    {
        $date = new \DateTime($date);
        $month = (int)$date->format('n');
        $year = (int)$date->format('Y');
        $month += $monthCounter;
        $monthFullYear = (int)(($month - 1) / 12);
        if ($monthFullYear > 0) $year += $monthFullYear;
        $month -= $monthFullYear * 12;
        if ($month == 0) {
            $month = 1;
        }
        if ($month < 10) $month = '0' . $month;

        return $year . '-' . $month . '-01';
    }

    /**
     * Сравнивает даты
     *
     * @param string $d1 в формате 'yyyy-mm-dd'
     * @param string $d2 в формате 'yyyy-mm-dd'
     *
     * @return int
     * 1 - d1 больше d2
     * 0 - d1=d2
     * -1 - d1 меньше d2
     */
    public static function dateCompare($d1, $d2)
    {
        $delta = (new \DateTime($d1))->format('U') - (new \DateTime($d2))->format('U');
        if ($delta > 0) return 1;
        if ($delta < 0) return -1;
        return 0;
    }
}
