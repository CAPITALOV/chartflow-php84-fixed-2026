<?php

use yii\db\Schema;
use yii\db\Migration;

class m151004_131838_s1 extends Migration
{
    public function up()
    {
        $this->update('cap_stock', ['is_enabled' => 1]);
    }

    public function down()
    {
        echo "m151004_131838_s1 cannot be reverted.\n";

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
