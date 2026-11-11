<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_112850_money extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_users_stock_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_finish` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m150824_112850_money cannot be reverted.\n";

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
