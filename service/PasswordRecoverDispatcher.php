<?php


namespace app\service;

use \yii\db\Query;

class PasswordRecoverDispatcher extends \cs\services\dispatcher\PasswordRecovery
{
    const TABLE = 'cap_users_recover';
}