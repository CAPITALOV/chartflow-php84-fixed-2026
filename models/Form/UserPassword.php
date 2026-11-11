<?php

namespace app\models\Form;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\VarDumper;

/**
 * UserPassword
 */
class UserPassword extends Model
{
    const TABLE = 'adm_user';

    /** @var integer $id идентификатор записи */
    public $id;
    public $password;

    /** @var array $fields все названия полей таблицы */
    private static $fields = ['password'];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'], 'string', 'min' => 2, 'max' => 20],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Обновляет запись в таблицу
     * @return boolean результат операции
     */
    public function update()
    {
        $query = new Query();
        $command = $query->createCommand();
        $fields = [
//            'password'   => password_hash($this->password, PASSWORD_BCRYPT),
            'password'   => md5($this->password),
        ];
        $command->update(self::TABLE, $fields, ['id' => $this->id])->execute();

        return true;
    }

    /**
     * Конструктор
     */
    public function __construct($fields = []) {
        if (count($fields) > 0) {
            foreach ($fields as $key => $value) {
                $this->$key = $fields[$key];
            }
        }
    }

    /**
     * Ищет запись в таблице
     * @return UserPassword|null
     */
    public static function find($id) {
        return new self(['id' => $id]);
    }
}
