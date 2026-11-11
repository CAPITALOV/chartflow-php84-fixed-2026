<?php

use yii\db\Schema;
use yii\db\Migration;

class m151004_131007_stock extends Migration
{
    public function up()
    {
        $this->execute('drop table tbChats');
        $this->execute('drop table tbManagers');
        $this->execute('drop table tbMessages');
    }

    public function down()
    {
        echo "m151004_131007_stock cannot be reverted.\n";

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
