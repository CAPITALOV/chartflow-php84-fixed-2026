<?php

use yii\db\Schema;
use yii\db\Migration;

class m150902_103401_requests extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `stock_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `datetime` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m150902_103401_requests cannot be reverted.\n";

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
