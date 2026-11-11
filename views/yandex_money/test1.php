<?php

use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $url string */

$this->title = 'Test1';

?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<a href="<?= $url ?>" target="_blank">Авторизоваться</a>