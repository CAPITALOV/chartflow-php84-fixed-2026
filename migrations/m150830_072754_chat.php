<?php

use yii\db\Schema;
use yii\db\Migration;

class m150830_072754_chat extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_chat_messages DROP direction;');
    }

    public function down()
    {
        echo "m150830_072754_chat cannot be reverted.\n";

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
