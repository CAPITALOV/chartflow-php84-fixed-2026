<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_124314_money extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_users_wallet_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `datetime` int(11) DEFAULT NULL,
  `description` VARCHAR (255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m150824_124314_money cannot be reverted.\n";

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
