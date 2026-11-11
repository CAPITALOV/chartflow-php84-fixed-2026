<?php

use yii\db\Schema;
use yii\db\Migration;

class m150904_094712_email_change extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_users_email_change` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `code` varchar(60) DEFAULT NULL,
  `date_finish` int DEFAULT NULL,
  `parent_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m150904_094712_email_change cannot be reverted.\n";

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
