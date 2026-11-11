<?php
/**
 * Данные для подключения к импорту данных
 */

namespace app\service\DadaImporter;

class Data
{
    public static $importerData = [
        // сбербанк
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 1,
            'path'     => '/SBERP_150701_150818.txt',
            'params'   => [
                'market'    => 1,
                'em'        => 23,
                'code'      => 'SBERP',       // кодовый шифр продукта
            ],
        ],
        // Aplle
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 4,
            'path'     => '/US2.AAPL_150818_150818.txt',
            'params'   => [
                'market'    => 2,
                'em'        => 20569,
                'code'      => 'US2.AAPL',       // кодовый шифр продукта
            ],
        ],
        // газпром
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 5,
            'path'     => '/GAZP_150818_150818.txt',
            'params'   => [
                'market'    => 1,
                'em'        => 16842,
                'code'      => 'GAZP',       // кодовый шифр продукта
            ],
        ],
        // лукойл
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 6,
            'path'     => '/LKOH_150818_150818.txt',
            'params'   => [
                'market'    => 1,
                'em'        => 8,
                'code'      => 'LKOH',       // кодовый шифр продукта
            ],
        ],
        // Золото
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 8,
            'path'     => '/LKOH_150818_150818.txt',
            'params'   => [
                'market'    => 2,
                'em'        => 18953,
                'code'      => 'comex.GC',       // кодовый шифр продукта
            ],
        ],
        // Нефть
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 7,
            'path'     => '/ICE.BRN_150818_150818.txt',
            'params'   => [
                'market'    => 2,
                'em'        => 19473,
                'code'      => 'ICE.BRN',       // кодовый шифр продукта
            ],
        ],
        // RTSI
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 10,
            'path'     => '/RTSI_150818_150818.txt',
            'params'   => [
                'market'    => 1,
                'em'        => 95,
                'code'      => 'RTSI',       // кодовый шифр продукта
            ],
        ],
        // RTSI
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 9,
            'path'     => '/MICEXINDEXCF_150818_150818.txt',
            'params'   => [
                'market'    => 1,
                'em'        => 13851,
                'code'      => 'MICEXINDEXCF',       // кодовый шифр продукта
            ],
        ],
        // RTSI
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 11,
            'path'     => '/MICEXINDEXCF_150818_150818.txt',
            'params'   => [
                'market'    => 2,
                'em'        => 91,
                'code'      => 'D&J-IND',       // кодовый шифр продукта
            ],
        ],
        // RTSI
        [
            'class' => 'app\service\DadaImporter\Finam',
            'stock_id' => 12,
            'path'     => '/SBER_150818_150818.txt',
            'params'   => [
                'market'    => 1,
                'em'        => 3,
                'code'      => 'SBER',       // кодовый шифр продукта
            ],
        ],
    ];

    /**
     * Выдает данные по запросу stock_id
     * Удаляет переменную `stock_id` из блака данных
     *
     * @param $stock_id
     *
     * @return array массив найденых блоков данных для инициализации импортера
     */
    public static function get($stock_id)
    {
        $ret = [];
        foreach(self::$importerData as $item) {
            if ($item['stock_id'] == $stock_id) {
                unset($item['stock_id']);
                $ret[] = $item;
            }
        }

        return $ret;
    }

    /**
     * Выдает данные по запросу stock_id первый найденный
     * Удаляет переменную `stock_id` из блака данных
     *
     * @param $stock_id
     *
     * @return array данные для инициализации импортера
     */
    public static function getFirst($stock_id)
    {
        foreach(self::$importerData as $item) {
            if ($item['stock_id'] == $stock_id) {
                unset($item['stock_id']);
                return  $item;
            }
        }

        return null;
    }
}