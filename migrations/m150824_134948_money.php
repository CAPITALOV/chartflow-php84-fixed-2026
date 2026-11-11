<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_134948_money extends Migration
{
    public function up()
    {
        $this->execute('update cap_users_stock_buy set `date_finish`=null');
        $this->execute('ALTER TABLE galaxysss_4.cap_users_stock_buy CHANGE date_finish date_finish date;');
        $this->execute('ALTER TABLE galaxysss_4.cap_users_stock_buy DROP year;');
        $this->execute('ALTER TABLE galaxysss_4.cap_users_stock_buy DROP month;');
    }

    public function down()
    {
        echo "m150824_134948_money cannot be reverted.\n";

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
