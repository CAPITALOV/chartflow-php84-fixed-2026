<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_183148_role extends Migration
{
    public function up()
    {
        $this->insert('cap_user_role_link',['user_id' => 5,'role_id' => 3]);
    }

    public function down()
    {
        echo "m150831_183148_role cannot be reverted.\n";

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
