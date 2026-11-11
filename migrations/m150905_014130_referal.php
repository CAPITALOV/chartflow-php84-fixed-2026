<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_014130_referal extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_users ADD referal_link VARCHAR(20) NULL;');
        $rows = \app\models\User::query()
            ->select('id')
            ->column();
        foreach($rows as $id) {
            (new \yii\db\Query())->createCommand()->update('cap_users', [
                'referal_link' => \cs\services\Security::generateRandomString(20)
            ], ['id' => $id])->execute();
        }
    }

    public function down()
    {
        echo "m150905_014130_referal cannot be reverted.\n";

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
