<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_140335_referal extends Migration
{
    public function up()
    {
        // таблица сохраняет регестрируемых по реферальной ссылке
        $this->execute('CREATE TABLE `cap_registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` int DEFAULT NULL,
  `referal_link` varchar(20) DEFAULT NULL,
  `is_paid` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        echo "m150905_140335_referal cannot be reverted.\n";

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
