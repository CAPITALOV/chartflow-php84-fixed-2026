<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\Progress;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
\app\assets\Layout\Asset::register($this);

$this->registerMetaTag(['name' => 'og:image', 'content' => Url::to('/images/share.png', true)]);
$this->registerMetaTag(['name' => 'og:url', 'content' => Url::current()]);
$this->registerMetaTag(['name' => 'og:title', 'content' => $this->title]);
$this->registerMetaTag(['name' => 'og:description', 'content' => 'AI chartflow.ru']);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/js/ajaxJson.js"></script>
    <link rel="shortcut icon" href="/images/kapitalovlogo1-2.png">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="fuelux">
    <?php $this->beginBody()?>
    <?= $this->render('../blocks/topNavigation') ?>
    <div class="container-fluid" style="
    <?php
        if (Yii::$app->requestedRoute != 'cabinet/stock_item3') {
            echo('padding-right:30px; padding-left:30px');
        }else{
            echo('padding-right:0px; padding-left:0px');
        }
       ?> ">

        <div class="col-lg-12" style="margin-top:70px; padding-top: 0; padding-bottom: 40px; padding-right:0; padding-left:0px;">
            <?= $content ?>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="modalInfo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Информация</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (\Yii::$app->user->isGuest) : ?>
        <?= $this->render('_modalLogin') ?>
    <?php endif; ?>
    <footer class="footer" style="position: absolute; width: 100%; ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>© 2002 — <?= date('Y') ?> | RUS.chartflow.ru FINAM прогноз на 90 дней от текущей даты | AI chartflow.ru | <?= $this->render('../blocks/telephone') ?>
</font><a href="https://rus.chartflow.ru">
<font color="#808080">/ Согласие на обработку персональных данных и Соглашение об использовании информации</font></a><font color="#808080">
</b><br>

                    </p>
                </div>

            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
