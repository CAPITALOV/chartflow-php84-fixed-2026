<?php

use yii\db\Schema;
use yii\db\Migration;

class m150830_052532_chat extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `datetime` int DEFAULT NULL,
  `message` varchar(1000) DEFAULT NULL,
  `direction` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->execute('ALTER TABLE `cap_stock_kurs` ADD INDEX `stock_id` (`stock_id`)');
        $this->execute('ALTER TABLE `cap_stock_prognosis_blue` ADD INDEX `stock_id` (`stock_id`)');
        $this->execute('ALTER TABLE `cap_stock_prognosis_red` ADD INDEX `stock_id` (`stock_id`)');
    }

    public function down()
    {
        echo "m150830_052532_chat cannot be reverted.\n";

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
