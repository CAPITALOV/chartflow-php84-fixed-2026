<?php

use yii\db\Schema;
use yii\db\Migration;

class m151019_221759_m extends Migration
{
    public function up()
    {
        $stocks = \app\models\Stock::query(['finam_code' => null])
            ->select(['id'])
            ->orWhere(['finam_code' => ''])
            ->column();
        $kurs = \app\models\StockKurs::query()->select('stock_id')->groupBy('stock_id')->column();
        $ids = array_intersect($stocks, $kurs);
        $this->delete(\app\models\StockKurs::TABLE, ['in', 'stock_id', $ids]);
    }

    public function down()
    {
        echo "m151019_221759_m cannot be reverted.\n";

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
