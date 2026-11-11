<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * AdminUserForm
 */
class AdminUserForm extends Model
{
    const TABLE = 'adm_user';
    const TABLE_LINK = 'adm_user_role_link';

    public $id;
    public $email;
    public $name_first;
    public $name_last;
    public $password;
    public $roles;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email', 'password', 'name_first', 'roles'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [['name_first', 'name_last'], 'string', 'min' => 2, 'max' => 20],
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
     * Добавляет пользователя в БД
     */
    public function insert()
    {
        $query = new Query();
        $command = $query->createCommand();
        $command->insert(self::TABLE, [
            'email'      => $this->email,
            'name_first' => $this->name_first,
            'name_last'  => $this->name_last,
//            'password'   => password_hash($this->password, PASSWORD_BCRYPT),
            'password'   => md5($this->password),
        ])->execute();
        $uid = Yii::$app->db->getLastInsertID();
        foreach ($this->roles as $roleId) {
            $command->insert(self::TABLE_LINK, [
                'user_id' => $uid,
                'role_id' => $roleId,
            ])->execute();
        }

        return true;
    }

    /**
     * Обновляет запись в таблице
     */
    public function update()
    {
        $query = new Query();
        $command = $query->createCommand();
        $command->update(self::TABLE, [
            'email'      => $this->email,
            'name_first' => $this->name_first,
            'name_last'  => $this->name_last,
        ], ['id' => $this->id])->execute();
        $command->delete(self::TABLE_LINK, ['user_id' => $this->id])->execute();
        foreach ($this->roles as $roleId) {
            $command->insert(self::TABLE_LINK, [
                'user_id' => $this->id,
                'role_id' => $roleId,
            ])->execute();
        }

        return true;
    }

    /**
     * Конструктор
     */
    public function __construct($fields = []) {
        if (count($fields) > 0) {
            $this->id = $fields['id'];
            $this->email = $fields['email'];
            $this->name_first = $fields['name_first'];
            $this->name_last = $fields['name_last'];
            $this->password = $fields['password'];
            $this->roles = $fields['roles'];
        }
    }

    /**
     * Ищет запись в таблице
     * @return AdminUserForm|null
     */
    public static function find($id) {
        $query = new Query();
        $row = $query->select('*')->from(self::TABLE)->where(['id' => $id])->one();
        if ($row) {
            $roles = $query->select('*')->from(self::TABLE_LINK)->where(['user_id' => $id])->all();
            $row['roles'] = ArrayHelper::getColumn($roles, 'role_id');
            return new self($row);
        } else {
            return null;
        }
    }
}
