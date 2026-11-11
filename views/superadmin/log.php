<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
$this->title = 'Лог пользователей';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/js/pages/superadmin/components.js', ['depends' => ['yii\grid\GridViewAsset']]);

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <a class="btn btn-primary" href="<?= Url::to(['superadmin/log']); ?>">
        Все
    </a>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Пользователи
    </a>
    <div class="collapse" id="collapseExample" style="margin-top: 20px;">
        <div class="well">
            <?php
            echo GridView::widget($users);
            ?>
        </div>
    </div>


    <h2>Лог</h2>
    <?php
    echo GridView::widget($gridViewOptions);
    ?>



</div>




