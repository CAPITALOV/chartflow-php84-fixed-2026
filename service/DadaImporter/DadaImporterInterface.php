<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 18.08.2015
 * Time: 14:41
 */

namespace app\service\DadaImporter;


interface DadaImporterInterface {

    /**
     * Производит импорт данных
     *
     * @param string $start дата начала иморта, формат 'yyyy-mm-dd'
     * @param string $end   дата окончания иморта, формат 'yyyy-mm-dd', если не указано то используется сегодняшний день по gmdate('Y-m-d')
     *
     * @return array
     * [
     *   [
     *     ‘date’ => ‘yyyy-mm-dd’
     *     ‘kurs’ => float
     *   ], ...
     * ]
     */
    public function import($start, $end = null);
} 