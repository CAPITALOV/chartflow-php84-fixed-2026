<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_182735_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD index_cl FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD index_rp FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD svecha_open FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD svecha_low FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD svecha_high FLOAT NULL;');
    }

    public function down()
    {
        echo "m150821_182735_stock cannot be reverted.\n";

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
