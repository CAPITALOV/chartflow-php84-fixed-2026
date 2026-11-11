<?php

use yii\db\Schema;
use yii\db\Migration;

class m150912_180122_stock_new extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_stock_market` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');


    }

    public function down()
    {
        echo "m150912_180122_stock_new cannot be reverted.\n";

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
