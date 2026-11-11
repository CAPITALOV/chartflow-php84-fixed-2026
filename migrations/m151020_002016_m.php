<?php

use yii\db\Schema;
use yii\db\Migration;

class m151020_002016_m extends Migration
{
    public function up()
    {
//        $stocks = \app\models\Stock::query()->select('id')->column();
//        $kurs = \app\models\StockKurs::query()->select(['stock_id'])->groupBy('stock_id')->column();
//
//        echo(\yii\helpers\VarDumper::dumpAsString([$stocks, $kurs]));exit;
//        foreach($stocks as $i) {
//            if (!in_array($i, $kurs)) {
//                $this->update(\app\models\Stock::TABLE, ['is_kurs' => 0], ['id' => $i]);
//            }
//        }
    }

    public function down()
    {
        echo "m151020_002016_m cannot be reverted.\n";

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
