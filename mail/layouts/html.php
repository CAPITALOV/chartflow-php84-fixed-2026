<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <table border="0" cellpadding="0" cellpadding="0" width="600" align="center" style="
    border: 1px solid #cccccc;
">
        <tr>
            <td>
                <img src="<?= \yii\helpers\Url::to('/images/share.png', true) ?>" width="300">
            </td>
        <tr>
        </tr>
        <td style="padding: 20px;">
            <p>Здравствуйте!</p>

            <?= $content ?>

        </td>
        <tr>
        </tr>
        <td style="padding: 20px;">
            <p>С уважением Команда сайта KAPITALOV.com</p>
        </td>
        </tr>
    </table>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
