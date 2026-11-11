<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_182625_role extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_user_role_link` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->execute('CREATE TABLE `cap_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `code` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->batchInsert('cap_user_role', ['name','code'], [
            ['Суперадмин', 'superAdmin'],
            ['Админ', 'admin'],
            ['Дизайнер', 'designer'],
        ]);
    }

    public function down()
    {
        echo "m150831_182625_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
