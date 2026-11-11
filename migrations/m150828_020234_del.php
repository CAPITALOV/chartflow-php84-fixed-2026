<?php

use yii\db\Schema;
use yii\db\Migration;

class m150828_020234_del extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_kurs DROP delta;');
        $this->execute('DROP TABLE galaxysss_4.cap_stock_prognosis;');
    }

    public function down()
    {
        echo "m150828_020234_del cannot be reverted.\n";

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
