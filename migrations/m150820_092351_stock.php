<?php

use yii\db\Schema;
use yii\db\Migration;

class m150820_092351_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock CHANGE name name VARCHAR(255) COLLATE utf8_general_ci;');
    }

    public function down()
    {
        echo "m150820_092351_stock cannot be reverted.\n";

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
