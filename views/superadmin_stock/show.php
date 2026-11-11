<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\UnionCategory;
use yii\db\Query;
use \yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $query yii\db\Query */

$this->title = 'Прогноз';
?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => new ActiveDataProvider([
        'query'      => $query->orderBy(['date' => SORT_ASC]),
        'pagination' => [
            'pageSize' => 20,
        ],
    ]),
]) ?>
