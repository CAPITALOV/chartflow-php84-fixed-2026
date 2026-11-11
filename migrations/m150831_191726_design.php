<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_191726_design extends Migration
{
    public function up()
    {
        $this->execute('CREATE TABLE `cap_design` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img1` varchar(255) DEFAULT NULL,
  `img2` varchar(255) DEFAULT NULL,
  `img3` varchar(255) DEFAULT NULL,
  `html` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->insert('cap_design', [
            'id'   => 1,
            'img1' => '',
            'img2' => '',
            'img3' => '',
            'html' => '',
        ]);
    }

    public function down()
    {
        echo "m150831_191726_design cannot be reverted.\n";

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
