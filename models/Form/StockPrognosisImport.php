<?php

namespace app\models\Form;

use app\models\StockKurs;
use app\models\StockPrognosis;
use app\models\StockPrognosisBlue;
use app\models\StockPrognosisRed;
use app\models\User;
use cs\base\BaseForm;
use cs\services\VarDumper;
use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 *
 */
class StockPrognosisImport extends BaseForm
{
    public $fileRed;
    public $fileBlue;
    /** @var  bool Заменять уже имеющиеся данные?
     *                                  true - если в таблице уже есть курс на эту дату то он будет перезатерт
     *                                  false - если в таблице уже есть курс на эту дату то он сохранится
     */
    public $isReplaceExisting;
    /** @var  array массив строк для полей fileRed и fileBlue
     * [
     *     'fileRed' => [],
     *     'fileBlue' => [],
     * ]
     */
    public $lines;

    public function rules()
    {

        return ArrayHelper::merge([
            [['fileRed', 'fileBlue'], 'validateFile']
        ], $this->rulesAdd());
    }

    public function __construct($fields = []){
        self::$fields = [
            [
                'fileRed',
                'Красная линия',
                0,
                'string'
            ],
            [
                'fileBlue',
                'Синяя линия',
                0,
                'string'
            ],
            [
                'isReplaceExisting',
                'Заменять уже имеющиеся данные?',
                0,
                'cs\Widget\CheckBox2\Validator',
                'widget' => [
                    'cs\Widget\CheckBox2\CheckBox', [
                    ]
                ]
            ],
        ];
        parent::__construct($fields);
    }

    /**
     * @param string $attribute  идентификатор поля, например fileBlue
     *
     * @return array|null массив строк
     *                    null если не загружен
     */
    private function getLinesFromFile($attribute)
    {
        $isset = false;
        if (isset($this->lines)){
            if (isset($this->lines[$attribute])) {
                $isset = true;
            }
        }
        if (!$isset) {
            $fileModel = UploadedFile::getInstance($this, $attribute);
            if ($fileModel) {
                $data = file_get_contents($fileModel->tempName);
                $rows = explode("\n", $data);
                $this->lines[$attribute] = $rows;
            } else {
                return null;
            }
        }

        return $this->lines[$attribute];
    }

    public function validateFile($attribute, $params)
    {
        $rows = $this->getLinesFromFile($attribute);
        if ($rows) {
            foreach($rows as $row) {
                $items = explode(' ', $row);
                $c = 1;
                foreach ($items as $i) {
                    if (trim($i) != '') {
                        if ($c == 1) {
                            $data = trim($i);
                            $y = substr($data, 6, 4);
                            $m = substr($data, 0, 2);
                            $d = substr($data, 3, 2);
                            if ($y < 1900 or $m < 1 or $m > 12 or $d < 1 or $d > 31){
                                $this->addError($attribute, "Ошибочные данные в файле. Строка ='{$row}'");
                                return;
                            }
                            $c++;
                        } else {
                            $delta = trim($i);
                        }
                    }
                }
            }
        }
    }

    /**
     * Импортирует данные
     *
     * @return array Возвращает отчет об импорте
     * [
     *    'red' => [
     *                'errorStrings' => [
     *                                      '12/12/0000  0.333',
     *                                      '12/12/0000  -0.333',
     *                                  ]
     *             ]
     * ]
     *
     *
     */
    public function import($stock_id)
    {
        if ($this->validate()) {
            // выбираю уже имеющиеся данные
            $dataArray = StockPrognosisRed::query(['stock_id' => $stock_id])->select('date')->column();
            $rows = $this->get('fileRed', $stock_id, $dataArray);
            if (count($rows['insert']) > 0) {
                StockPrognosisRed::batchInsert(['stock_id', 'date', 'delta'], $rows['insert']);
            }
            if ($this->isReplaceExisting) {
                foreach ($rows['update'] as $date => $kurs) {
                    (new Query())->createCommand()->update(StockPrognosisRed::TABLE, ['delta' => $kurs], ['date' => $date, 'stock_id' => $stock_id])->execute();
                }
            }

            // выбираю уже имеющиеся данные
            $dataArray = StockPrognosisBlue::query(['stock_id' => $stock_id])->select('date')->column();
            $rows = $this->get('fileBlue', $stock_id, $dataArray);
            if (count($rows['insert']) > 0) {
                StockPrognosisBlue::batchInsert(['stock_id', 'date', 'delta'], $rows['insert']);
            }
            if ($this->isReplaceExisting) {
                foreach ($rows['update'] as $date => $kurs) {
                    (new Query())->createCommand()->update(StockPrognosisBlue::TABLE, ['delta' => $kurs], ['date' => $date, 'stock_id' => $stock_id])->execute();
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Выбирает из файла данные и возвращает в виде массива
     *
     * @param string $fieldName
     * @param integer $stock_id
     * @param array $dataArray
     *
     * @return array
     * [
     *     'insert' => array массив данных для вставки
     *     'update' => array массив данных для обновления
     * ]
     */
    public function get($fieldName, $stock_id, $dataArray)
    {
        $rows = $this->getLinesFromFile($fieldName);
        if ($rows) {
            $new = [];
            $update = [];
            foreach ($rows as $row) {
                $items = explode(' ', $row);
                $c = 1;
                foreach ($items as $i) {
                    if (trim($i) != '') {
                        if ($c == 1) {
                            $date = trim($i);
                            $date = substr($date, 6, 4) . '-' . substr($date, 0, 2) . '-' . substr($date, 3, 2);
                            $c++;
                        } else {
                            $delta = trim($i);
                        }
                    }
                }
                if (in_array($date, $dataArray)) {
                    $update[ $date ] = $delta;
                } else {
                    $new[] = [
                        $stock_id,
                        $date,
                        $delta,
                    ];
                }
            }

            return [
                'insert' => $new,
                'update' => $update,
            ];
        } else {
            return [
                'insert' => [],
                'update' => [],
            ];
        }
    }
}
