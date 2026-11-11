<?php

use app\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Translator as T;

?>
<?php if (!\Yii::$app->user->isGuest): ?>
    <div class="hidden-print">
        <?php if (\Yii::$app->user->identity->isAdmin()) { ?>
            <div class="list-group">
                <a href="<?= Url::to(['superadmin_stock/index']) ?>" class="list-group-item">
                    Админ
                </a>
            </div>
        <?php } ?>

        <div class="list-group">
            <a href="<?= Url::to(['cabinet/stock_list']) ?>" class="list-group-item">
                Курсы
            </a>
        </div>


        <div class="list-group">

            <?php
            $items = \app\models\Stock::query()->orderBy(['name' => SORT_ASC])->all();
            $dateFinishList = \app\models\UserStock::query(['user_id' => \Yii::$app->user->getId()])->select([
                'stock_id',
                'date_finish',
            ])->all();
            for ($i = 0; $i < count($items); $i++) {
                $item = &$items[ $i ];
                foreach ($dateFinishList as $row) {
                    if ($row['stock_id'] == $item['id']) {
                        $item['is_paid'] = \Yii::$app->user->identity->isPaid($item['id']);
                    }
                }
                if (!isset($item['is_paid'])) $item['is_paid'] = false;
            }

            for ($i = 0; $i < count($items); $i++) {
                $item = &$items[ $i ];
                $url = Url::to(['cabinet/stock_item3', 'id' => $item['id']]);
                ?>
                <a href="<?= $url ?>" class="list-group-item<?= ($url == Url::current()) ? ' active' : '' ?>">
                    <?= $item['name'] ?>
                    <?php if ($item['is_paid']) { ?>
                        <span class="badge"><i class="glyphicon glyphicon-ok"></i></span>
                    <?php } ?>
                </a>
            <?php } ?>
        </div>
    </div>

<?php endif; ?>
