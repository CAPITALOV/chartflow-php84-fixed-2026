<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_133813_t extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m151008_133813_t cannot be reverted.\n";

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
