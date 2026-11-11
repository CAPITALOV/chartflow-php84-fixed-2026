<?php

namespace app\models\Form;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\User;

/**
 * AdminUser
 */
class AdminUser extends Model
{
    const TABLE = 'adm_user';

    public $id;
    public $email;
    public $name_first;
    public $name_last;
    public $password;
    public $rating;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email', 'password', 'name_first', 'rating'], 'required'],
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
     * Добавляет запись в таблицу
     *
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
            'rating'   => 0,
//            'password'   => password_hash($this->password, PASSWORD_BCRYPT),
            'password'   => md5($this->password),
        ])->execute();
        $uid = Yii::$app->db->getLastInsertID();
        $command->insert('adm_user_role_link', [
            'user_id'      => $uid,
            'role_id' => User::ROLE_MODERATOR,
        ])->execute();

        return true;
    }

    /**
     * Обновляет запись в таблицу
     *
     */
    public function update()
    {
        $query = new Query();
        $command = $query->createCommand();
        $command->update(self::TABLE, [
            'email'      => $this->email,
            'name_first' => $this->name_first,
            'name_last'  => $this->name_last,
            'rating'     => $this->rating,
        ], ['id' => $this->id])->execute();

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
            $this->rating = $fields['rating'];
        }
    }

    /**
     * Ищет запись в таблице
     * @return AdminUser|null
     */
    public static function find($id) {
        $query = new Query();
        $row = $query->select('*')->from(self::TABLE)->where(['id' => $id])->one();
        if ($row) {
            return new self($row);
        } else {
            return null;
        }
    }
}
