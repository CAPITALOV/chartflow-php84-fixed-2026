<?php

use yii\db\Schema;
use yii\db\Migration;

class m150908_214934_stock extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD finam_em int NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD finam_market int NULL;');
        $this->execute('ALTER TABLE galaxysss_4.cap_stock ADD finam_code VARCHAR(10) NULL;');
    }

    public function down()
    {
        echo "m150908_214934_stock cannot be reverted.\n";

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
