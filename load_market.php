<?php
/**
 * Новый загрузчик рыночных данных.
 *
 *  php scripts/load_market.php catalog          — собрать/обновить каталог тикеров
 *  php scripts/load_market.php kurs             — закачать котировки рынков (1,2,24,28)
 *  php scripts/load_market.php kurs 2           — один рынок
 *  php scripts/load_market.php kurs 4           — один тикер (stock_id)
 */

$configFile = __DIR__ . '/../config/db.php';
if (!is_file($configFile)) { fwrite(STDERR, "Не найден файл {$configFile}\n"); exit(1); }
$cfg = require $configFile;
if (!preg_match('/host=([^;]+)/', $cfg['dsn'], $mH) || !preg_match('/dbname=([^;]+)/', $cfg['dsn'], $mD)) {
    fwrite(STDERR, "Не удалось разобрать dsn: {$cfg['dsn']}\n"); exit(1);
}
$host = $mH[1]; $db = $mD[1]; $user = $cfg['username']; $pass = $cfg['password'];
echo "База: {$db}, хост: {$host}, пользователь: {$user}\n";

define('M_MOEX', 1);   // акции МосБиржи  -> MOEX ISS
define('M_US',   2);   // акции США       -> Yahoo
define('M_ETF',  28);  // ETF             -> Yahoo
define('M_COMM', 24);  // товары, металлы -> Yahoo (фьючерсы)

$COMMODITIES = [
    'GC=F' => 'Gold Futures',    'SI=F' => 'Silver Futures',
    'PL=F' => 'Platinum Futures','PA=F' => 'Palladium Futures',
    'HG=F' => 'Copper Futures',  'CL=F' => 'WTI Crude Oil',
    'BZ=F' => 'Brent Crude Oil', 'NG=F' => 'Natural Gas',
    'RB=F' => 'RBOB Gasoline',   'ZW=F' => 'Wheat',
    'ZC=F' => 'Corn',            'ZS=F' => 'Soybeans',
];

$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8mb4',
    $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function httpGet(string $url): string {
    $code = 0;
    for ($i = 0; $i < 3; $i++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => '',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
        ]);
        $body = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body !== false && $code == 200) return $body;
        usleep(1000000);
    }
    throw new RuntimeException("HTTP failed ({$code}): {$url}");
}

/* ---------------- КАТАЛОГ ---------------- */

function catalog() {
    global $pdo, $COMMODITIES;

    $check = $pdo->prepare("SELECT id FROM cap_stock WHERE finam_market = ? AND finam_code = ?");
    $upd   = $pdo->prepare("UPDATE cap_stock SET is_enabled = 1, name = ? WHERE id = ?");
    $ins   = $pdo->prepare("INSERT INTO cap_stock (name, finam_market, finam_code, status, is_kurs, is_enabled)
                            VALUES (?, ?, ?, 2, 1, 1)");

    $n = 0;
    for ($start = 0; $start < 50000; $start += 100) {
        $url = "https://iss.moex.com/iss/securities.json?engine=stock&market=shares"
             . "&board=TQBR&limit=100&start={$start}&iss.meta=off&iss.only=securities";
        $raw = json_decode(httpGet($url), true);
        if (empty($raw['securities']['data'])) break;
        $c = array_flip($raw['securities']['columns']);
        foreach ($raw['securities']['data'] as $r) {
            $code = $r[$c['SECID']];
            if (!preg_match('/^[A-Z0-9\-]{1,10}$/', $code)) continue;
            $name = $r[$c['SHORTNAME']] ?? $code;
            $title = "RU. {$name} - {$code}";
            $check->execute([M_MOEX, $code]);
            $id = $check->fetchColumn();
            if ($id) $upd->execute([$title, $id]); else { $ins->execute([$title, M_MOEX, $code]); }
            $n++;
        }
        if (count($raw['securities']['data']) < 100) break;
        usleep(300000);
    }
    echo "MOEX каталог: {$n} тикеров\n";

    // США: каталог NASDAQ Trader (NASDAQ + NYSE/AMEX/BATS/IEX)
    $us = [];
    foreach (['nasdaqlisted.txt', 'otherlisted.txt'] as $file) {
        $lines = explode("\n", httpGet("https://www.nasdaqtrader.com/dynamic/SymDir/{$file}"));
        $cols = array_map('trim', explode('|', trim($lines[0])));
        foreach (array_slice($lines, 1) as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, 'File Creation Time') !== false) continue;
            $r = array_combine($cols, array_map('trim', explode('|', $line)));
            $sym  = $r['Symbol'] ?? $r['ACT Symbol'] ?? '';
            $test = $r['Test Issue'] ?? '';
            $etf  = ($r['ETF'] ?? '') === 'Y';
            if ($test !== 'N' || $sym === '' || !preg_match('/^[A-Z]{1,5}$/', $sym)) continue;
            if (isset($us[$sym]) && !$etf) continue;   // приоритет у обычной акции
            $us[$sym] = ['name' => $r['Security Name'] ?? $sym, 'etf' => $etf];
        }
        usleep(500000);
    }
    $n = 0;
    foreach ($us as $sym => $info) {
        $m = $info['etf'] ? M_ETF : M_US;
        $title = "US. {$info['name']} - {$sym}";
        $check->execute([$m, $sym]);
        $id = $check->fetchColumn();
        if ($id) $upd->execute([$title, $id]); else { $ins->execute([$title, $m, $sym]); }
        $n++;
    }
    echo "США каталог: {$n} тикеров\n";

    foreach ($COMMODITIES as $sym => $name) {
        $title = "CM. {$name} - {$sym}";
        $check->execute([M_COMM, $sym]);
        $id = $check->fetchColumn();
        if ($id) $upd->execute([$title, $id]); else { $ins->execute([$title, M_COMM, $sym]); }
    }
    echo "Товары/металлы: " . count($COMMODITIES) . " тикеров\n";
}

/* ---------------- КОТИРОВКИ ---------------- */

function fetchYahoo(string $sym, int $period1): array {
    $period1 -= 3 * 86400; // буфер: окно не должно начинаться позже последнего бара
    $period1 -= 3 * 86400; // буфер: окно не должно начинаться позже последнего бара
    $p2 = time() + 86400;
    $url = "https://query1.finance.yahoo.com/v8/finance/chart/" . urlencode($sym)
         . "?period1={$period1}&period2={$p2}&interval=1d";
    $res = json_decode(httpGet($url), true)['chart']['result'][0] ?? null;
    if (!$res || empty($res['timestamp'])) return [];
    $q = $res['indicators']['quote'][0];
    $rows = [];
    foreach ($res['timestamp'] as $i => $ts) {
        if (($q['close'][$i] ?? null) === null) continue;
        $rows[] = ['date' => gmdate('Y-m-d', $ts),
            'open' => $q['open'][$i], 'high' => $q['high'][$i],
            'low' => $q['low'][$i], 'close' => $q['close'][$i], 'volume' => $q['volume'][$i]];
    }
    return $rows;
}

function fetchMoex(string $secid, string $from): array {
    $rows = [];
    for ($start = 0; $start < 200000; $start += 100) {
        $url = "https://iss.moex.com/iss/history/engines/stock/markets/shares/boards/TQBR/securities/"
             . urlencode($secid) . "/candles.json?interval=24&from={$from}&start={$start}&iss.meta=off";
        $raw = json_decode(httpGet($url), true);
        if (empty($raw['history']['data'])) break;
        $c = array_flip($raw['history']['columns']);
        foreach ($raw['history']['data'] as $r) {
            if (empty($r[$c['CLOSE']])) continue;
            $rows[] = ['date' => $r[$c['TRADEDATE']],
                'open' => $r[$c['OPEN']], 'high' => $r[$c['HIGH']],
                'low' => $r[$c['LOW']], 'close' => $r[$c['CLOSE']], 'volume' => $r[$c['VOLUME']]];
        }
        if (count($raw['history']['data']) < 100) break;
        usleep(200000);
    }
    return $rows;
}

function saveRows(int $stockId, array $rows): int {
    global $pdo;
    if (!$rows) return 0;
    $vals = []; $p = [];
    foreach ($rows as $r) {
        $vals[] = '(?,?,?,?,?,?,?,?)';
        array_push($p, $stockId, $r['date'], $r['close'], $r['open'], $r['high'], $r['low'], $r['close'], $r['volume']);
    }
    $sql = "INSERT INTO cap_stock_kurs (stock_id, date, kurs, open, high, low, close, volume)
            VALUES " . implode(',', $vals) . "
            ON DUPLICATE KEY UPDATE kurs=VALUES(kurs), open=VALUES(open), high=VALUES(high),
            low=VALUES(low), close=VALUES(close), volume=VALUES(volume)";
    $pdo->prepare($sql)->execute($p);
    return count($rows);
}

function kurs($arg) {
    global $pdo;
    $sql = "SELECT id, finam_market, finam_code FROM cap_stock
            WHERE finam_market IN (1,2,24,28) AND finam_code IS NOT NULL AND finam_code != '' AND is_enabled = 1";
    if ($arg !== '' && ctype_digit($arg) && (int)$arg < 100) $sql .= " AND finam_market = " . (int)$arg;
    elseif ($arg !== '' && ctype_digit($arg)) $sql .= " AND id = " . (int)$arg;

    $ok = 0; $fail = 0; $ins = 0;
    foreach ($pdo->query($sql) as $s) {
        $m = (int)$s['finam_market']; $code = $s['finam_code'];
        try {
            if ($m == M_MOEX) {
                $from = $pdo->query("SELECT COALESCE(MAX(date),'2000-01-01') FROM cap_stock_kurs WHERE stock_id={$s['id']}")->fetchColumn();
                if ($from !== '2000-01-01') $from = date('Y-m-d', strtotime($from) + 86400);
                $rows = fetchMoex($code, $from);
            } else {
                $sym  = preg_replace('/^(US2?\.)/i', '', $code);
                $sym  = preg_replace('/\.US$/i', '', $sym);
                $sym  = str_replace('.', '-', $sym);      // BRK.B -> BRK-B
                $last = $pdo->query("SELECT COALESCE(MAX(date),'1970-01-01') FROM cap_stock_kurs WHERE stock_id={$s['id']}")->fetchColumn();
                $p1   = ($last === '1970-01-01') ? 946684800 : strtotime($last . ' +1 day');
                $rows = fetchYahoo($sym, $p1);
            }
            $n = saveRows((int)$s['id'], $rows);
            $ins += $n; $ok++;
            echo "[ok] {$code} (id={$s['id']}): {$n}\n";
            usleep(400000);
        } catch (Throwable $e) {
            $fail++;
            echo "[fail] {$code} (id={$s['id']}): " . $e->getMessage() . "\n";
        }
    }
    echo "\nИтого: ok={$ok}, fail={$fail}, строк={$ins}\n";
}

$cmd = isset($argv[1]) ? $argv[1] : 'help';
if     ($cmd == 'catalog') catalog();
elseif ($cmd == 'kurs')    kurs(isset($argv[2]) ? $argv[2] : '');
else echo "Usage: php scripts/load_market.php [catalog|kurs] [market|stock_id]\n";
