<?php

namespace app\service\DadaImporter;

use cs\services\Url;
use app\models\Log;
use yii\base\BaseObject;
use yii\db\Query;
use cs\services\VarDumper;
use yii\helpers\ArrayHelper;

class Finam extends BaseObject implements DadaImporterInterface
{
    // Закомментированное - это финам
    // private $url    = 'http://export.finam.ru';
    private $url    = 'https://query1.finance.yahoo.com';

    // public $path    = '/SBERP_150805_150805.txt';
    public $path    = '/v8/finance/chart/';

    public $params;
    
    public $default = [
        'market'    => 1,
        'em'        => 23,
        'p'         => 8,                     // шаг, 8 - по дням
        'f'         => 'SBERP_150805_150805', // название файла
        'e'         => '.txt',                // расширение файла с точкой
        'dtf'       => 1,
        'tmf'       => 1,
        'MSOR'      => 1,
        'mstimever' => 0,
        'sep'       => 1,
        'sep2'      => 1,
        'datf'      => 4,                     // формат выводимых данных 'TICKER, PER, DATE, TIME, OPEN, HIGH, LOW, CLOSE, VOL', ...
        'at'        => 0,                     // использовать заголовок в генерируемом документе? 1 - да, 0 - нет
    ];

    /**
     * @inheritdoc
     */
    public function import($start, $end = null, $stock_id = 0)
    {
        if (is_null($end)) {
            $end = gmdate('Y-m-d');
        }

		echo "Dates: $start - $end\n";

        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $params = ArrayHelper::merge($this->default, $this->params);
        $params['from'] = $start->format('d.m.Y');
        $params['from'] = $start->format('Y-m-d');
        // $params['df'] = (int)$start->format('j');
        $params['mf'] = (int)$start->format('n') - 1;
        $params['yf'] = (int)$start->format('Y');
        // $params['to'] = $end->format('d.m.Y');
        $params['to'] = $end->format('Y-m-d');
        $params['dt'] = (int)$end->format('j');
        $params['mt'] = (int)$end->format('n') - 1;
        $params['yt'] = $end->format('Y');
        $params['cn'] = $params['code'];

        // $u = new Url($this->url . $this->path, $params);
        // $url = (string) $u;

        if (stripos($stock_id, '.INDX')) {
        	$stock_id = '^'.str_replace('.INDX', '', $stock_id);
        }

        $url = $this->url . $this->path . $stock_id . '?symbol=' . $stock_id . '&period1=' . strtotime($params['from']) . '&period2=' . strtotime($params['to']) . '&interval=1d';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);
         
        // $arr = explode("\n", $out);
        $arr = json_decode($out);

        Log::batchInsert([
            'id',
            'level',
            'category',
            'log_time',
            'prefix',
            'message',
        ], array(array(1,0,strtotime($params['to']),11111,'asd',json_encode($arr->chart->result[0]))));
        $ret = [];
        if(isset($arr->chart->result[0]->timestamp)){
       	 foreach ($arr->chart->result[0]->timestamp as $key => $row) {
             if ($row && trim($arr->chart->result[0]->indicators->quote[0]->close[$key]) != 0) {
            // if (trim($row) != '') {
                // $items = explode(',', $row);
                // $ret[] = [
                //     'date' => substr($items[0],0,4) . '-' . substr($items[0],4,2) . '-' . substr($items[0],6,2),
                //     'kurs' => (float)trim($items[4]),
                // ];
                $ret[] = [
                	'date'   => date('Y', $row) . '-' . date('m', $row) . '-' . date('d', $row),
                    'kurs'  => (float)trim($arr->chart->result[0]->indicators->quote[0]->close[$key]),
               	 ];
            // }
               }
        	}
    	}

        print_r($ret);

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function importCandels($start, $end = null, $stock_id = 0)
    {
        if (is_null($end)) {
            $end = gmdate('Y-m-d');
        }
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $params = ArrayHelper::merge($this->default, $this->params);
        // $params['from'] = $start->format('d.m.Y');
        $params['from'] = $start->format('Y-m-d');
        $params['df'] = (int)$start->format('j');
        $params['mf'] = (int)$start->format('n') - 1;
        $params['yf'] = (int)$start->format('Y');
        // $params['to'] = $end->format('d.m.Y');
        $params['to'] = $end->format('Y-m-d');
        $params['dt'] = (int)$end->format('j');
        $params['mt'] = (int)$end->format('n') - 1;
        $params['yt'] = $end->format('Y');
        $params['cn'] = $params['code'];
        $params['datf'] = 5;

        // $u = new Url($this->url . $this->path, $params);
        // $url = (string) $u;
        if (stripos($stock_id, '.INDX')) {
        	$stock_id = '^'.str_replace('.INDX', '', $stock_id);
        }

        $url = $this->url . $this->path . $stock_id . '?symbol=' . $stock_id . '&period1=' . strtotime($params['from']) . '&period2=' . strtotime($params['to']) . '&interval=1d';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($ch);
        curl_close($ch);

        $arr = json_decode($out);
        //  Log::batchInsert([
        //     'id',
        //     'level',
        //     'category',
        //     'log_time',
        //     'prefix',
        //     'message',
        // ], array(array(1,0,$url,strtotime($params['from']),'asd',json_encode($arr->chart->result[0]->timestamp))));
        $ret = [];
        if(isset($arr->chart->result[0]->timestamp)){
       	 foreach ($arr->chart->result[0]->timestamp as $key => $row) {
             if ($row && trim($arr->chart->result[0]->indicators->quote[0]->close[$key]) != 0) {
                // if (trim($row) != '') {
                    // $items = explode(',', $row);
                    // $ret[] = [
                    //     'date'   => substr($items[0], 0, 4) . '-' . substr($items[0], 4, 2) . '-' . substr($items[0], 6, 2),
                    //     'open'   => (float)trim($items[2]),
                    //     'high'   => (float)trim($items[3]),
                    //     'low'    => (float)trim($items[4]),
                    //     'close'  => (float)trim($items[5]),
                    //     'volume' => (float)trim($items[6]),
                    // ];
                     $ret[] = [
                        'date'   => date('Y', $row) . '-' . date('m', $row) . '-' . date('d', $row),
                        'open'   => (float)trim($arr->chart->result[0]->indicators->quote[0]->open[$key]),
                        'high'   => (float)trim($arr->chart->result[0]->indicators->quote[0]->high[$key]),
                        'low'    => (float)trim($arr->chart->result[0]->indicators->quote[0]->low[$key]),
                        'close'  => (float)trim($arr->chart->result[0]->indicators->quote[0]->close[$key]),
                        'volume' => (float)trim($arr->chart->result[0]->indicators->quote[0]->volume[$key]),
                    ];
                // }
              }
            }
        }
        return $ret;
    }
}