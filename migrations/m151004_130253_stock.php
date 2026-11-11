<?php

use yii\db\Schema;
use yii\db\Migration;

class m151004_130253_stock extends Migration
{
    public function up()
    {
        $ids = \app\models\StockKurs::query()->select('stock_id')
            ->groupBy('stock_id')
            ->column();
        foreach($ids as $id) {
            \app\models\Stock::find($id)->update(['is_kurs' => 1]);
        }
    }

    public function down()
    {
        echo "m151004_130253_stock cannot be reverted.\n";

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
