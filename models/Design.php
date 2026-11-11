<?php

namespace app\models;

use app\models\NewsItem;
use app\models\User;
use app\services\GsssHtml;
use cs\services\Str;
use cs\services\VarDumper;
use cs\web\Exception;
use Yii;
use yii\base\Model;
use cs\Widget\FileUpload2\FileUpload;
use yii\db\Query;
use yii\helpers\Html;

/**
 */
class Design extends \cs\base\DbRecord
{
    const TABLE = 'cap_design';

    public function getImg($id)
    {
        $v = $this->getField('img' . $id, '');
        if ($v == '') {
            throw new Exception('Не установлена картинка img'.$id);
        }

        return FileUpload::getOriginal($v);
    }

    public function getHtml()
    {
        return $this->getField('html', '');
    }
}
