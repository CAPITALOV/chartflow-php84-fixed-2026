<?php
/**
 * [
 * 'value'    => '/uploads/...',
 * 'original' => '/uploads/...', // оригинал изображения
 * 'formName' => $this->model->formName(), // string
 * 'model'    => $this->model, // \yii\base\Model
 * 'attrId'   => $this->attrId, // attribute id
 * 'attrName' => $this->attrName, // attribute name
 * 'widgetOptions' => $widgetOptions, // widgetOptions опции переданные при конфигурации виджета в widgetOptions
 * ]
 */

/** @var $value */
/** @var $attrId */
/** @var $attrName */
/** @var $widgetOptions */
?>

<div class="row col-lg-12">

    <?php if ($value) { ?>

        <img src="<?= $value ?>" id="<?= $attrId ?>-img">

        <div class="row col-lg-12" style="margin-top: 20px; margin-bottom: 20px;">
            <a class="btn btn-default" id="<?= $attrId ?>-delete" role="button">Удалить</a>
            <input type="file" name="<?= $attrName ?>" id="<?= $attrId ?>" accept="image/*" title="Загрузить">
        </div>
        <div id="<?= $attrId ?>-img_name"></div>

    <?php } else { ?>

        <div class="photo_form_upload blue_button">
            <input type="file" name="<?= $attrName ?>" id="<?= $attrId ?>" accept="image/*" title="Загрузить">
        </div>

        <div id="{$attrId}-img_name"></div>

    <?php } ?>

    <input type="hidden" name="<?= $attrName ?>" id="<?= $attrId ?>-value" value="<?= $value ?>">

</div>


