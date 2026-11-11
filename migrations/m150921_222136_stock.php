<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_222136_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD status TINYINT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_requests ADD is_paid TINYINT NULL;');
    }

    public function down()
    {
        echo "m150921_222136_stock cannot be reverted.\n";

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
