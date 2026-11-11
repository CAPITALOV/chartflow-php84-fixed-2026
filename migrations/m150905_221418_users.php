<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_221418_users extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_users CHANGE email email VARCHAR(50) COLLATE latin1_general_ci;');
    }

    public function down()
    {
        echo "m150905_221418_users cannot be reverted.\n";

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
