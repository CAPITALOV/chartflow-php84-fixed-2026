<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_235328_prognoz extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_stock_prognosis_red` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `delta` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->execute('CREATE TABLE `cap_stock_prognosis_blue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `delta` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m150821_235328_prognoz cannot be reverted.\n";

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
