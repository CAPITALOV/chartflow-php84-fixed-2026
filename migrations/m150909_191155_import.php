<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_191155_import extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE galaxysss_4.cap_stock CHANGE finam_code finam_code varchar(20);');
        $arr = \app\models\Stock::query()->all();

        foreach($arr as $item) {
            $stock = new \app\models\Stock($item);
            foreach(\app\service\DadaImporter\Data::$importerData as $data) {
                if ($item['id'] == $data['stock_id']) {
                    $params = $data['params'];
                    $stock->update([
                        'finam_em'     => $params['em'],
                        'finam_market' => $params['market'],
                        'finam_code'   => $params['code'],
                    ]);
                }
            }
        }
    }

    public function down()
    {
        echo "m150909_191155_import cannot be reverted.\n";

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
