<?php
declare(strict_types=1);
/* Названия с бирж + описания для cap_stock:
   name        = "Apple Inc. (AAPL)" / "Сбербанк (SBER)"
   description = "Акции Apple Inc. (США, NASDAQ). Котировки: 1990–2026."
   Рынок 1 (MOEX) — NAME из ISS; рынок 2 (США) — справочник SEC EDGAR.
   Запуск: php scripts/rename_exchange.php          — dry-run
           php scripts/rename_exchange.php --go     — применить */

$cfg = require __DIR__ . '/../config/db.php';
preg_match('/host=([^;]+)/', $cfg['dsn'], $mH);
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $mD);
$pdo = new PDO('mysql:host=' . $mH[1] . ';dbname=' . $mD[1] . ';charset=utf8mb4',
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$go = in_array('--go', $argv, true);

// ВАЖНО: замените на вашу реальную почту — SEC требует честный User-Agent
$UA = 'Chartflow.ru data import admin@chartflow.ru';

$MANUAL = [
    'GC=F' => 'Gold Futures', 'SI=F' => 'Silver Futures', 'BZ=F' => 'Brent Crude Oil',
    'CL=F' => 'WTI Crude Oil', 'NG=F' => 'Natural Gas', 'HG=F' => 'Copper Futures',
    'ZW=F' => 'Wheat', 'ZC=F' => 'Corn', 'ZS=F' => 'Soybeans',
    'GSPC.INDX' => 'S&P 500', 'NDX.INDX' => 'NASDAQ 100', 'DJI.INDX' => 'Dow Jones Industrial Average',
    'IMOEX' => 'Индекс МосБиржи', 'IMOEX.ME' => 'Индекс МосБиржи', 'RTSI' => 'Индекс РТС',
    'BTC-USD' => 'Bitcoin', 'ETH-USD' => 'Ethereum',
];

function httpGet(string $url, string $ua): ?string {
    for ($i = 0; $i < 3; $i++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 45, CURLOPT_USERAGENT => $ua]);
        $body = curl_exec($ch); $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch); curl_close($ch);
        if ($code === 200 && is_string($body) && $body !== '') return $body;
        fwrite(STDERR, "  [http] $url -> code=$code err=$err\n");
        sleep(2);
    }
    return null;
}

// --- 1. Справочник SEC (кэш в /tmp) ---
$secFile = sys_get_temp_dir() . '/company_tickers.json';
if (!is_file($secFile)) {
    echo "Качаю справочник SEC...\n";
    $body = httpGet('https://www.sec.gov/files/company_tickers.json', $UA);
    if ($body === null) { fwrite(STDERR, "SEC недоступен\n"); }
    else file_put_contents($secFile, $body);
}
$secTickers = []; $secExch = [];
if (is_file($secFile)) {
    foreach (json_decode(file_get_contents($secFile), true) as $row) {
        $secTickers[strtoupper($row['ticker'])] = $row['title'];
        $secExch[strtoupper($row['ticker'])] = $row['exchange'] ?? '';
    }
}
echo "SEC: " . count($secTickers) . " тикеров\n";

// --- 2. Названия MOEX (кэш в /tmp) ---
$moexFile = sys_get_temp_dir() . '/moex_names.json';
if (!is_file($moexFile)) {
    $codes = $pdo->query("SELECT DISTINCT UPPER(SUBSTRING_INDEX(finam_code, '.ME', 1)) c FROM cap_stock
        WHERE finam_market = 1 AND finam_code IS NOT NULL AND finam_code <> ''")->fetchAll(PDO::FETCH_COLUMN);
    $codes = array_values(array_filter($codes, fn($c) => preg_match('/^[A-Z0-9-]{1,12}$/', $c)));
    echo "MOEX: запрашиваю " . count($codes) . " бумаг...\n";
    $map = [];
    foreach ($codes as $code) {
        $base = explode('.', $code)[0];
        $body = httpGet('https://iss.moex.com/iss/securities/' . urlencode($base) . '.json', $UA);
        if ($body === null) continue;
        $j = json_decode($body, true);
        if (!isset($j['description']['columns'])) continue;
        $iName = array_search('name',  $j['description']['columns']);
        $iVal  = array_search('value', $j['description']['columns']);
        $full = null; $short = null;
        foreach ($j['description']['data'] as $row) {
            if ($row[$iName] === 'NAME')      $full  = $row[$iVal];
            if ($row[$iName] === 'SHORTNAME') $short = $row[$iVal];
        }
        $val = $full ?: $short;             // полное название в приоритете
        if ($val) $map[$code] = $val;
        usleep(200000);
    }
    file_put_contents($moexFile, json_encode($map, JSON_UNESCAPED_UNICODE));
}
$moex = is_file($moexFile) ? json_decode(file_get_contents($moexFile), true) : [];
echo "MOEX: " . count($moex) . " названий\n";

// --- 3. Периоды истории: один запрос на всё ---
$periods = $pdo->query("SELECT s.id, MIN(k.date) d1, MAX(k.date) d2
    FROM cap_stock s JOIN cap_stock_kurs k ON k.stock_id = s.id
    GROUP BY s.id")->fetchAll(PDO::FETCH_UNIQUE);

// --- 4. Обновление ---
$rows = $pdo->query("SELECT id, finam_market, finam_code, name FROM cap_stock
    WHERE finam_code IS NOT NULL AND finam_code <> '' AND finam_market IN (1,2)
    ORDER BY finam_market, finam_code")->fetchAll(PDO::FETCH_ASSOC);

$upd = $pdo->prepare("UPDATE cap_stock SET name = ?, description = ? WHERE id = ?");
$done = 0; $skip = 0; $samples = [];
foreach ($rows as $r) {
    $code = strtoupper($r['finam_code']);
    $new = $desc = null;

    if ($r['finam_market'] == 1) {
        $base = $moex[rtrim($code, '.ME')] ?? $MANUAL[$code] ?? null;
        if ($base) {
            $new  = $base . ' (' . $code . ')';
            $desc = $base . ' (MOEX, ' . $code . ')';
        }
    } else {
        if (isset($MANUAL[$code])) {
            $new  = $MANUAL[$code] . ' (' . $code . ')';
            $desc = $MANUAL[$code] . ' (' . $code . ')';
        } elseif (isset($secTickers[$code])) {
            $exch = $secExch[$code] !== '' ? $secExch[$code] : 'США';
            $new  = $secTickers[$code] . ' (' . $code . ')';
            $desc = $secTickers[$code] . ' (' . $exch . ', ' . $code . ')';
        }
    }
    if ($new === null) { $skip++; continue; }

    // период истории в конец описания
    if (isset($periods[$r['id']]['d1'])) {
        $desc = rtrim($desc, '.') . '. Котировки: '
            . substr($periods[$r['id']]['d1'], 0, 4) . '–'
            . substr($periods[$r['id']]['d2'], 0, 4) . '.';
    }
    if (mb_strlen($desc) > 255) $desc = mb_substr($desc, 0, 252) . '...';

    if ($new === $r['name']) { $skip++; continue; }
    if (!$go && count($samples) < 20)
        $samples[] = $r['id'] . ": {$r['name']}\n      -> $new\n      desc: $desc";
    if ($go) $upd->execute([$new, $desc, (int)$r['id']]);
    $done++;
}

if (!$go) { echo "\n== DRY RUN, применить: php scripts/rename_exchange.php --go ==\n\n", implode("\n", $samples), "\n\n"; }
echo "К изменению: $done, без названия/уже названы: $skip\n";
