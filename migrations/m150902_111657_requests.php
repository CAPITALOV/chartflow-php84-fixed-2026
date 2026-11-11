<?php

use yii\db\Schema;
use yii\db\Migration;

class m150902_111657_requests extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_requests ADD hash VARCHAR(60) NULL;');
    }

    public function down()
    {
        echo "m150902_111657_requests cannot be reverted.\n";

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
