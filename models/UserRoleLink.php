<?php

namespace app\models;

use Yii;

/**
 */
class UserRoleLink extends \cs\base\DbRecord
{
    const TABLE = 'cap_user_role_link';

    public static function isRole($userId, $roleId)
    {
        return self::query([
            'user_id' => $userId,
            'role_id' => $roleId,
        ])->exists();
    }
}
