<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_141436_referal extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_registration ADD user_id int NULL;');
    }

    public function down()
    {
        echo "m150905_141436_referal cannot be reverted.\n";

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
