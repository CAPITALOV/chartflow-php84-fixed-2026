<?php

namespace app\models;

use Yii;

/**
 */
class UserRole extends \cs\base\DbRecord
{
    const TABLE = 'cap_user_role';

    const ROLE_SUPER_ADMIN = 1;
    const ROLE_ADMIN       = 2;
    const ROLE_DESIGNER    = 3;
}
