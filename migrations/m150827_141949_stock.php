<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_141949_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD logo VARCHAR(255) NULL;');
    }

    public function down()
    {
        echo "m150827_141949_stock cannot be reverted.\n";

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
