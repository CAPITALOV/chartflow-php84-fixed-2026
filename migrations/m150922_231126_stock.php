<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_231126_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock DROP is_send_letter;');
    }

    public function down()
    {
        echo "m150922_231126_stock cannot be reverted.\n";

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
