<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $item->getField('name');

$this->registerJsFile('/js/pages/superadmin_stock/prognosis_edit.js', ['depends' => ['yii\web\JqueryAsset']]);
?>

<h1 class="page-header"><?= $this->title ?></h1>

<table class="table" style="width: auto;">
    <?php
    foreach ($items as $i) {
        ?>
        <tr>
            <td>
                <div class="form-group">
                    <input class="form-control inputDate" type="text" value="<?= $i['date'] ?>" data-id="<?= $i['id'] ?>" style="width: auto;" data-type="date"/>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input class="form-control inputKurs" type="text" value="<?= $i['kurs'] ?>" data-id="<?= $i['id'] ?>" style="width: auto;" data-type="kurs"/>
                </div>
            </td>
        </tr>
    <?php
    }?>
</table>