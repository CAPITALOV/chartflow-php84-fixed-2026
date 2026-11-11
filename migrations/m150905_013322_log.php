<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_013322_log extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE IF NOT EXISTS `log` (
`id` bigint(20) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `log_time` double DEFAULT NULL,
  `prefix` text,
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');
    }

    public function down()
    {
        echo "m150905_013322_log cannot be reverted.\n";

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
