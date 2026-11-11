<?php

use yii\db\Schema;
use yii\db\Migration;

class m150912_182402_market extends Migration
{
    public function up()
    {
        $text = file_get_contents(\Yii::getAlias('@app/views/cabinet_wallet/1.php'));
        require_once(\Yii::getAlias('@app/app/services/simplehtmldom_1_5/simple_html_dom.php'));

        $doc = str_get_html($text);
        $new = [];
        foreach ($doc->find('market/a') as $i) {
            if ($i->attr['index'] != '0') {
                $new[] = [
                    $i->attr['value'],
                    trim($i->plaintext),
                ];
            }
        }
        \app\models\StockMarket::batchInsert(['id', 'name'], $new);
        $new2 = [];
        $exist = \app\models\Stock::query(['not', ['finam_market' => 1]])->select('finam_em')->column();
        foreach ($doc->find('kurs/ul') as $m) {
            $market = $m->attr['market'];
            foreach ($m->find('a') as $i) {
                $is_add = false;
                if (isset($i->attr['index'])) {
                    if ($i->attr['index'] != '0') {
                        $is_add = true;
                    }
                } else {
                    $is_add = true;
                }
                if (in_array($i->attr['value'], $exist)) {
                    $is_add = false;
                }
                if ($is_add) {
                    $new2[] = [
                        $market,
                        $i->attr['value'],
                        trim($i->plaintext),
                    ];
                }
            }
        }
        \app\models\Stock::batchInsert(['finam_market', 'finam_em', 'name'], $new2);
    }

    public function down()
    {
        echo "m150912_182402_market cannot be reverted.\n";

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
