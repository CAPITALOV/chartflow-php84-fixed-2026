<?php


namespace app\service;


use cs\services\VarDumper;
use yii\helpers\ArrayHelper;

class LogReader
{
    /** @var string путь к файлу */
    public $file;

    /**
     * Инициализирует файл лога
     *
     * @param string $file полный путь к файлу
     *
     * @return self
     */
    public static function file($file)
    {
        return new self(\Yii::getAlias($file));
    }


    /**
     * Инициализирует файл лога
     *
     * @param string $file полный путь к файлу
     *
     * @return self
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Читает лог файла и возвращает структурированные данные
     * @return array
     * [
     *     [
     *          'uid' =>
     *          'datetime' =>
     *          'message' =>
     *     ], ...
     * ]
     */
    public function read($maxStrings = null)
    {
        $data = file_get_contents($this->file);
        $array = explode("\n", $data);
        $i = $this->findFirst($array);
        $items = [];
        $d = 0;
        do {
            $ret = $this->getItem($array, $i);
            $items[] = $ret['data'];
            $i = $ret['nextString'];
            $d++;
            if ($maxStrings) {
                if ($d == $maxStrings) {
                    break;
                }
            }
        } while (!$ret['isLast']);

        return $this->convert($items);
    }

    /**
     * Читает лог с конца
     *
     * @param array $options опции вывода
     * - maxStrings - int - количество записей лога которые надо вернуть, по умолчанию - все
     * - user_id - int - идентификатор пользователя
     * - type - string - тип сообщения info | debug | error | warning
     * - category - string - первые симводы категории
     *
     * @return array
     * последняя запись лога будет первой в массиве
     */
    public function readLast($options = [])
    {
        $maxStrings = ArrayHelper::getValue($options, 'maxStrings', null);
        $data = file_get_contents($this->file);
        $array = explode("\n", $data);
        $i = $this->findLast($array);
        $items = [];
        $d = 0;
        do {
            $ret = $this->getItemReverse($array, $i);
            $logItem = $this->convertItem($ret['data']);
            if ($this->isEqual($logItem, $options)) {
                $items[] = $logItem;
                $d++;
            }
            $i = $ret['nextString'];
            if ($maxStrings) {
                if ($d == $maxStrings) {
                    break;
                }
            }
        } while (!$ret['isLast']);

        return $items;
    }

    public function isEqual($logItem, $options)
    {
        foreach ($options as $n => $v) {
            switch ($n) {
                case 'user_id':
                    if ($logItem['user_id'] != $v) {
                        return false;
                    }
                    break;
                case 'category':
                    if (substr($logItem['category'], 0, strlen($options['category'])) != $options['category']) {
                        return false;
                    }
                    break;
                case 'type':
                    if ($options['type'] != $logItem['type']) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    public function convert($items)
    {
        $ret = [];
        foreach ($items as $item) {
            $ret[] = $this->convertItem($item);
        }

        return $ret;
    }

    /**
     * Преобразует набор строк файла лога в набор записей лога
     *
     *
     * @param array $data все строки файла
     * @param int $index указатель строки в файле на запись лога, исчисление от 0
     *
     * @return array
     * [
     *               'data' => array строки записи лога,
     *               'isLast' => Если это последняя запись лога то будет возвращено true
     *               'nextString' => int если isLast==false то здесть указатель на строку файла на следующую запись лога
     * ]
     */
    public function getItem($data, $index)
    {
        $ret = [];
        $c = 0;
        $isLast = false;
        do {
            $row = $data[ $c + $index ];
            $ret[] = $row;
            $c++;
            if ($c + $index >= count($data)) {
                $isLast = true;
                break;
            }

        } while (!$this->isBeginLogAction($data[ $c + $index ]));

        return [
            'data'       => $ret,
            'isLast'     => $isLast,
            'nextString' => $c + $index,
        ];
    }

    /**
     * Преобразует набор строк файла лога в набор записей лога
     *
     * @param array $data все строки файла
     * @param int $index указатель строки в файле на запись лога, исчисление от 0
     *
     * @return array
     * [
     *               'data' => array строки записи лога,
     *               'isLast' => Если это последняя запись лога то будет возвращено true
     *               'nextString' => int если isLast==false то здесть указатель на строку файла на следующую запись лога
     * ]
     */
    public function getItemReverse($data, $index)
    {
        $ret = [];
        $c = 0;
        $isLast = false;
        do {
            $row = $data[ $index + $c ];
            $ret[] = $row;
            $c++;
            if ($c + $index >= count($data)) {
                break;
            }
        } while (!$this->isBeginLogAction($data[ $index + $c ]));

        $i = 1;
        while (!$this->isBeginLogAction($data[ $index - $i ])) {
            $i++;
            if ($index - $i < 0) {
                $isLast = true;
                break;
            }
        }

        return [
            'data'       => $ret,
            'isLast'     => $isLast,
            'nextString' => $index - $i,
        ];
    }

    /**
     * Определяет является ли строка файла началом записи лога
     *
     * @param string $row
     *
     * @return bool;
     */
    public function isBeginLogAction($row)
    {
        if (strlen($row) < 20) return false;
        if ($row[4] == '-') {
            if ($row[7] == '-') {
                if ($row[10] == ' ') {
                    if ($row[13] == ':') {
                        if ($row[16] == ':') {
                            if ($row[19] == ' ') {
                                if ($row[20] == '[') {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Преобразует строки одной записи лога в структурные данные
     *
     * @param array $item
     *
     * @return array [
     * 'uid'      => $uid,
     * 'datetime' => $datetime,
     * 'type'     => $type,
     * 'message'  => $message,
     * ]
     */
    public function convertItem($item)
    {
        $firstRow = $item[0];
        $date = substr($firstRow, 0, 10);
        $time = substr($firstRow, 11, 8);
        $pos = strpos($firstRow, ']', 21);
        $ip = substr($firstRow, 21, $pos - 21);
        $start = $pos + 2;
        $pos = strpos($firstRow, ']', $start);
        $user_id = substr($firstRow, $start, $pos - $start);
        $start = $pos + 2;
        $pos = strpos($firstRow, ']', $start);
        $code = substr($firstRow, $start, $pos - $start);
        $start = $pos + 2;
        $pos = strpos($firstRow, ']', $start);
        $type = substr($firstRow, $start, $pos - $start);
        $start = $pos + 2;
        $pos = strpos($firstRow, ']', $start);
        $app = substr($firstRow, $start, $pos - $start);
        $start = $pos + 2;
        $message = substr($firstRow, $start);
        for ($i = 1; $i < count($item); $i++) {
            $message .= "\n" . $item[ $i ];
        }

        return [
            'date'     => $date,
            'time'     => $time,
            'ip'       => $ip,
            'user_id'  => $user_id,
            'code'     => $code,
            'type'     => $type,
            'category' => $app,
            'message'  => $message,
        ];
    }

    /**
     * Возвращает индекс строки первой записи лога
     *
     * @param array $data
     *
     * @return int|null
     */
    public function findFirst($data)
    {
        for ($c = 0; $c < count($data); $c++) {
            $row = $data[ $c ];
            if ($this->isBeginLogAction($row)) {
                return $c;
            }
        }

        return null;
    }

    /**
     * Возвращает индекс строки последней записи лога
     *
     * @param array $data
     *
     * @return int|null
     */
    public function findLast($data)
    {
        for ($c = count($data) - 1; $c >= 0; $c--) {
            $row = $data[ $c ];
            if ($this->isBeginLogAction($row)) {
                return $c;
            }
        }

        return null;
    }
}