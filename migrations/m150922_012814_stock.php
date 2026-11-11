<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_012814_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD is_send_letter TINYINT NULL;');
    }

    public function down()
    {
        echo "m150922_012814_stock cannot be reverted.\n";

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
