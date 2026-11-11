<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_200543_import extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_kurs ADD `open` FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_kurs ADD `high` FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_kurs ADD `low` FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_kurs ADD `close` FLOAT NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock_kurs ADD `volume` FLOAT NULL;');
    }

    public function down()
    {
        echo "m150909_200543_import cannot be reverted.\n";

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
