<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $item \app\models\Stock */

$this->title = 'Котировка успешно куплена';
?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<?php if ($item->getStatus() == \app\models\Stock::STATUS_READY) { ?>
    <span class="alert alert-success">Вы усешно оплатили</span>
<?php } else { ?>
    <span class="alert alert-success">Ваш график будет готов в течении от 1 до 5 дней. Уведомление о готовности придет к вам на почту.</span>
<?php } ?>
