<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_134738_money extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_users_stock_buy ADD year int NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_users_stock_buy ADD month int NULL;');
    }

    public function down()
    {
        echo "m150824_134738_money cannot be reverted.\n";

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
