<?php

use yii\db\Schema;
use yii\db\Migration;

class m150830_061641_chat extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_chat_messages ADD user_id_to int NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_chat_messages CHANGE user_id user_id_from INT;');
    }

    public function down()
    {
        echo "m150830_061641_chat cannot be reverted.\n";

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
