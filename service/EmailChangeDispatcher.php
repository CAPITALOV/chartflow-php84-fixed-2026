<?php


namespace app\service;

use \yii\db\Query;

class EmailChangeDispatcher extends \cs\services\dispatcher\EmailChange
{
    const TABLE = 'cap_users_email_change';
}