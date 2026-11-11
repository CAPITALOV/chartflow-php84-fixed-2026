<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_234301_prognoz extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_prognosis ADD index_cl FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_prognosis ADD index_rp FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_prognosis ADD svecha_open FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_prognosis ADD svecha_low FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_prognosis ADD svecha_high FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock DROP index_cl;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock DROP index_rp;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock DROP svecha_open;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock DROP svecha_low;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock DROP svecha_high;');
    }

    public function down()
    {
        echo "m150821_234301_prognoz cannot be reverted.\n";

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
