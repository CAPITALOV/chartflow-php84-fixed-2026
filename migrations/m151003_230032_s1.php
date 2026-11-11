<?php

use yii\db\Schema;
use yii\db\Migration;

class m151003_230032_s1 extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD is_kurs TINYINT NULL;');

    }

    public function down()
    {
        echo "m151003_230032_s1 cannot be reverted.\n";

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
