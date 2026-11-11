<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_225824_stock extends Migration
{
    public function up()
    {
        $this->execute('update cap_stock set `status`=2 where not(finam_code is null)');
    }

    public function down()
    {
        echo "m150921_225824_stock cannot be reverted.\n";

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
