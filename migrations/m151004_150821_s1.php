<?php

use yii\db\Schema;
use yii\db\Migration;

class m151004_150821_s1 extends Migration
{
    public function up()
    {
        $ids = \app\models\StockPrognosisRed::query()
            ->select('stock_id')
            ->groupBy('stock_id')
            ->column();
        $this->update('cap_stock', ['status' => null]);
        foreach($ids as $id) {
            \app\models\Stock::find($id)->update(['status' => \app\models\Stock::STATUS_READY]);
        }
    }

    public function down()
    {
        echo "m151004_150821_s1 cannot be reverted.\n";

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
