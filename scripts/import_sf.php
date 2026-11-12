#!/usr/bin/env php
<?php
/**
 * Импорт баров из базы StockForecast (stockforecast.ru) в chartflow.
 *
 * Использование (запускать из каталога нужного сайта):
 *   cd /var/www/chartflow.ru/data/www/rus.chartflow.ru && php scripts/import_sf.php moex
 *   cd /var/www/chartflow.ru/data/www/chartflow.ru      && php scripts/import_sf.php us
 *
 * Пароли не хранятся: читаются из config/db.php текущего сайта
 * и config.php проекта StockForecast на этом же сервере.
 *
 * Правки от 2026-11-12:
 *  - normCode возвращал префикс рынка вместо тикера (баг сопоставления кодов);
 *  - $ROOT фиксируется явно: в cron getcwd() указывает на домашний каталог;
 *  - транзакции с откатом: сбой на одном тикере больше не ломает весь прогон;
 *  - даты нормализуются до Y-m-d: формат MAX(date) и ts может отличаться;
 *  - include -> require для config.php StockForecast;
 *  - бары читаются поточно, без fetchAll всей истории в память.
 */

if (PHP_SAPI !== 'cli') { exit("CLI only\n"); }
if ($argc < 2 || !in_array($argv[1], ['moex', 'us'], true)) {
    fwrite(STDERR, "Использование: php import_sf.php [moex|us]\n");
    exit(1);
}
$market = $argv[1];

// Корни сайтов: текущий определяется по market, SF-проект — общий на сервере
$ROOTS = [
    'moex' => '/var/www/chartflow.ru/data/www/rus.chartflow.ru',
    'us'   => '/var/www/chartflow.ru/data/www/chartflow.ru',
];
$ROOT    = $ROOTS[$market];
$SF_ROOT = '/var/www/stockforecast/data/www/stockforecast.ru';

if (!is_file($ROOT . '/config/db.php'))    { fwrite(STDERR, "Не найден {$ROOT}/config/db.php\n"); exit(1); }
if (!is_file($SF_ROOT . '/config.php'))    { fwrite(STDERR, "Не найден {$SF_ROOT}/config.php\n"); exit(1); }

/* ---------- база chartflow ---------- */
$cfg = require $ROOT . '/config/db.php';
preg_match('/host=([^;]+)/',  $cfg['dsn'], $h);
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $d);
$chart = new PDO(
    'mysql:host=' . ($h[1] ?: '127.0.0.1') . ';port=3306;dbname=' . $d[1] . ';charset=utf8',
    $cfg['username'],
    $cfg['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

/* ---------- база StockForecast ---------- */
require $SF_ROOT . '/config.php';
$pass = defined('DB_PASS') ? constant('DB_PASS')
      : (defined('DB_PASSWORD') ? constant('DB_PASSWORD') : '');
if ($pass === '') { fwrite(STDERR, "Не нашёл пароль в config.php StockForecast\n"); exit(1); }
$sf = new PDO(
    'mysql:host=127.0.0.1;port=3306;dbname=' . DB_NAME . ';charset=utf8mb4',
    DB_USER,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

/* ---------- нормализация кодов ---------- */
function normCode(string $c): string {
    $c = strtoupper(trim($c));
    // срезаем только известные префиксы рынков: US2.AAPL -> AAPL, ICE.BRN -> BRN,
    // GDAX.BTC-USD -> BTC-USD. Цифровые (9684.T) и точечные (BRK.A) не трогаем.
    if (preg_match('/^(US\d*|ICE|GDAX|COMEX|SPB)\.(.+)$/', $c, $m)) $c = $m[2];
    return $c;
}

// нормализация даты до Y-m-d на случай отличий форматов между базами
function normDate(?string $d): ?string {
    if ($d === null || $d === '') return null;
    return substr($d, 0, 10);
}

// рынок SF -> «свой» finam_market в chartflow
$pref = ['moex' => 1, 'us' => 25][$market];

// Ручной маппинг: тикер SF => id в cap_stock (id проверены запросом!)
$manual = [
    'us'   => ['GSPC' => 777, 'DJI' => 11, 'NDX' => 721],
    'moex' => [],
];

$stocks = $chart->query('SELECT id, finam_code, finam_market FROM cap_stock')->fetchAll(PDO::FETCH_ASSOC);
$byCode = [];
foreach ($stocks as $s) {
    if ($s['finam_code'] === null || $s['finam_code'] === '') continue;
    $same = ((int)$s['finam_market'] === $pref) ? 1 : 0;
    $byCode[normCode($s['finam_code'])][$same][] = $s;
}

function findStock(array $byCode, string $ticker, array &$dupWarn, array $manual, string $market): ?array {
    if (isset($manual[$market][$ticker])) {
        return ['id' => (int)$manual[$market][$ticker], 'finam_code' => 'manual:'.$ticker, 'finam_market' => 0];
    }
    $c = normCode($ticker);
    if (!isset($byCode[$c])) return null;
    if (!empty($byCode[$c][1])) {
        if (count($byCode[$c][1]) > 1) $dupWarn[] = $c;
        return $byCode[$c][1][0];
    }
    return null; // кросс-рыночный fallback отключён: слишком легко перепутать бумаги
}

$tickers = $sf->prepare('SELECT id, ticker FROM stock_map WHERE market = ?');
$tickers->execute([$market]);

$getLast = $chart->prepare('SELECT MAX(date) FROM cap_stock_kurs WHERE stock_id = ?');
$hasRow  = $chart->prepare('SELECT id FROM cap_stock_kurs WHERE stock_id = ? AND date = ?');
$upd     = $chart->prepare('UPDATE cap_stock_kurs SET kurs = ?, open = ?, high = ?, low = ?, close = ? WHERE stock_id = ? AND date = ?');
$ins     = $chart->prepare('INSERT INTO cap_stock_kurs (stock_id, date, kurs, open, high, low, close, volume) VALUES (?, ?, ?, ?, ?, ?, ?, NULL)');
$bars    = $sf->prepare('SELECT ts, open, high, low, close FROM history_bars WHERE stock_id = ? AND ts >= ? ORDER BY ts');

$unmatched = []; $dupWarn = [];
$imported = 0; $updated = 0; $t0 = time();

while ($t = $tickers->fetch(PDO::FETCH_ASSOC)) {
    $stock = findStock($byCode, $t['ticker'], $dupWarn, $manual, $market);
    if (!$stock) { $unmatched[] = $t['ticker']; continue; }
    $sid = (int)$stock['id'];

    $getLast->execute([$sid]);
    $last = normDate($getLast->fetchColumn());

    $bars->execute([$t['id'], $last ?: '1990-01-01']);

    $nIns = $nUpd = 0;
    try {
        $chart->beginTransaction();
        while ($b = $bars->fetch(PDO::FETCH_ASSOC)) {
            $ts = normDate($b['ts']);
            if ($last !== null && $ts === $last) {
                $hasRow->execute([$sid, $ts]);
                if ($hasRow->fetch()) {
                    $upd->execute([$b['close'], $b['open'], $b['high'], $b['low'], $b['close'], $sid, $ts]);
                    $nUpd++;
                    continue;
                }
            }
            $ins->execute([$sid, $ts, $b['close'], $b['open'], $b['high'], $b['low'], $b['close']]);
            $nIns++;
        }
        $chart->commit();
    } catch (Throwable $e) {
        if ($chart->inTransaction()) $chart->rollBack();
        fwrite(STDERR, "Ошибка на {$t['ticker']} (stock_id={$sid}): " . $e->getMessage() . "\n");
        continue;
    }
    $imported += $nIns; $updated += $nUpd;
    printf("%-12s -> stock_id=%d  +%d новых, %d обновл\n", $t['ticker'], $sid, $nIns, $nUpd);
}

printf("\nИтого: вставлено %d, обновлено %d, за %d сек\n", $imported, $updated, time() - $t0);
if ($dupWarn)   echo "Внимание, несколько совпадений по коду: " . implode(', ', array_unique($dupWarn)) . "\n";
if ($unmatched) echo "Не сопоставлено (" . count($unmatched) . "): " . implode(', ', $unmatched) . "\n";
