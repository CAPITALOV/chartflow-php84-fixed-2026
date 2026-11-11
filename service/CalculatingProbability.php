<?php


namespace app\service;

use app\models\StockKurs;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use cs\services\VarDumper;
use yii\base\Exception;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class CalculatingProbability extends BaseObject
{
    /** @var  array массив значений курса [53.55, 56.32, ...] */
    public $rowKurs;

    /** @var  array массив значений курса [0.75, 0.78, ...] */
    public $rowPrognoz;

    public function init()
    {
    }

    /**
     * Загружает ряды в класс по идентификатору котировки
     *
     * @param int   $stockId   идентификатор котировки
     * @param int   $type      тип сравнения (1 - красный, 2 - синий)
     * @param array $dateRange периуд для расчета
     *                         ```php
     *                         [
     *                         'min' => 'yyyy-mm-dd'
     *                         'max' => 'yyyy-mm-dd'
     *                         ]
     *                         ```
     *
     * @return static
     * @throws \cs\web\Exception
     */
    public static function initStock($stockId, $type, $dateRange = null)
    {
        $where = null;
        if ($dateRange) {
            $where = ['between', 'date', $dateRange['min'], $dateRange['max']];
        }
        $kurs = StockKurs::query([
            'stock_id' => $stockId,
        ])->select(['`date`, `kurs` as `value`'])->orderBy(['date' => SORT_ASC]);
        if ($where) $kurs->where($where);
        $kurs = $kurs->all();
        $class = null;
        switch ($type) {
            case 1:
                $rows = StockPrognosisRed::query([
                    'stock_id' => $stockId,
                ])->select(['`date`, `delta` as `value`'])->orderBy(['date' => SORT_ASC]);
                if ($where) $rows->where($where);
                $class = \app\service\CalculatingProbability::initRows($kurs, $rows->all());
                break;
            case 2:
                $rows = StockPrognosisBlue::query([
                    'stock_id' => $stockId,
                ])->select(['`date`, `delta` as `value`'])->orderBy(['date' => SORT_ASC]);
                if ($where) $rows->where($where);
                $class = \app\service\CalculatingProbability::initRows($kurs, $rows->all());
                break;
            default:
                throw new \cs\web\Exception('Не верный тип $dateRange');
        }

        return $class;
    }

    /**
     * Загружает ряды в класс
     * ряды должны быть отсортированы по дате по возрастанию
     *
     * @param array $rowKurs    массив значений для курса
     *                          [
     *                          'value' => float
     *                          'date'  => 'yyyy-mm-dd'
     *                          ]
     * @param array $rowPrognoz массив значений для прогноза
     *                          [
     *                          'value' => float
     *                          'date'  => 'yyyy-mm-dd'
     *                          ]
     *
     * @return static
     */
    public static function initRows($rowKurs, $rowPrognoz)
    {
        // нормализуем ряды, каждая дата $rowKurs соответствует $rowPrognoz
        $dateArrayKurs = ArrayHelper::getColumn($rowKurs, 'date');
        $dateArrayPrognoz = ArrayHelper::getColumn($rowPrognoz, 'date');
        $dateArrayCorrect = array_intersect($dateArrayKurs, $dateArrayPrognoz);
        $rowKursNew = [];
        foreach ($rowKurs as $item) {
            if (in_array($item['date'], $dateArrayCorrect)) {
                $rowKursNew[] = $item['value'];
            }
        }
        $rowPrognozNew = [];
        $c = [];
        foreach ($rowPrognoz as $item) {
            if (in_array($item['date'], $dateArrayCorrect)) {
                $rowPrognozNew[] = $item['value'];
                $c[] = $item;
            }
        }

        $class = new static([
            'rowKurs'    => $rowKursNew,
            'rowPrognoz' => $rowPrognozNew,
        ]);

        return $class;
    }

    /**
     * расчитывает вероятность и выдает результат в виде числа от 0 до 1 которое соответствует от 0 до 100
     * стратегия расчета основана на следующем алгоритме:
     * берутся два соседних числа из одного ряда
     * если следующая точка из одного ряда (курс) идет вверх и из другого ряда (прогноз) тоже вверх то для этого
     * участка совпадение = 1 если обе идут вниз - то совпадение = 1 если направления не совпадают, то совпадение = 0
     * после этого среди всего массива вероятностей вычисляется среднее арифметическое и это и является общей
     * вероятностью для двух графиков
     *
     * используются ряды $this->rowKurs и $this->rowPrognoz
     *
     * @return float вероятность от 0 до 1
     */
    public function calc()
    {
        $ret = []; // сюда сохраняю вероятность на каждый отрезок
        for ($i = 0; $i < min(count($this->rowKurs), count($this->rowPrognoz)) - 1; $i++) {
            $kurs1 = $this->rowKurs[ $i ];
            $kurs2 = $this->rowKurs[ $i + 1 ];
            $deltaKurs = $kurs2 - $kurs1;
            $progoz1 = $this->rowPrognoz[ $i ];
            $progoz2 = $this->rowPrognoz[ $i + 1 ];
            $deltaProgoz = $progoz2 - $progoz1;
            if (($deltaKurs > 0 and $deltaProgoz > 0) or ($deltaKurs < 0 and $deltaProgoz < 0)) {
                $ret[] = 1;
            } else {
                $ret[] = 0;
            }
        }
        if (count($ret) == 0) return 0;
        $sum = 0;
        $count = 0;
        foreach ($ret as $i) {
            $sum += $i;
            $count++;
        }

        return $sum / $count;
    }
}